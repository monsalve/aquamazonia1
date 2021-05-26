<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Registro;
use App\EspecieSiembra;
use App\RecursoNecesario;
use App\Siembra;
use App\Recursos;


class InformeRegistroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $registros = Registro::select(
					'registros.id as id',
					'registros.id_siembra',
					'fecha_registro',              
					'tipo_registro',
					'peso_ganado',
					'mortalidad',
					'registros.cantidad',
					'biomasa',
					'especies.especie as especie',
					'especies.id as id_especie',
					'nombre_siembra', 
					'lote',
          'especies_siembra.cant_actual as cantidad_actual',
          'especies_siembra.peso_actual as peso_actual',
        )
				->join('especies',
				'registros.id_especie', 'especies.id')
				->join('siembras', 'registros.id_siembra', 'siembras.id')
				->leftJoin('especies_siembra', function($join){
					$join->on('registros.id_especie', '=', 'especies_siembra.id_especie')->on('registros.id_siembra', '=', 'especies_siembra.id_siembra');
				})
				->orderBy('fecha_registro', 'desc')
				->get();

        if(count($registros)>0){
          foreach($registros as $registro){
            
            $registro->biomasa_disponible = ($registro->peso_actual * $registro->cantidad_actual)/1000;
            $registro->bio_dispo_alimen = $this->BiomasaAlimento($registro->id_siembra)['bio_dispo_alimen'];
            if($registro->tipo_registro == 0)$registro->nombre_registro = 'Muestreo';
            if($registro->tipo_registro == 1)$registro->nombre_registro = 'Pesca';        
            if($registro->tipo_registro == 2)$registro->nombre_registro = 'Mortalidad Inicial';

            $registro->biomasa_disponible = number_format($registro->biomasa_disponible,2,'.','');
            $registro->bio_dispo_alimen = number_format($registro->bio_dispo_alimen,2,'.','');

          }
        }
        return $registros;
        
    }


    public function BiomasaAlimento($id_siembra){
      $siembras = Siembra::select(
				'siembras.id as id', 
				'capacidad', 
				'nombre_siembra',
				'id_contenedor',
				'fecha_inicio',
				'ini_descanso',
				'siembras.estado',
				'fin_descanso'
			)
      ->where('siembras.id', $id_siembra)
			->join('contenedores', 'siembras.id_contenedor', 'contenedores.id')
			->get();
      
      $existencias = EspecieSiembra::select(
				'cant_actual',
				'contenedor',
				'capacidad',
				'especies_siembra.cantidad as cantidad_inicial',
				'especies_siembra.id_especie as id_especie',
				'especies_siembra.id_siembra as id_siembra',
				'fecha_inicio',
				'nombre_siembra',
				'peso_inicial',
				'peso_actual',
			)
      ->orderBy('especies_siembra.id_siembra')
      ->orderBy('especies_siembra.id_especie')
      ->join('siembras', 'especies_siembra.id_siembra', 'siembras.id' )
      ->join('contenedores', 'siembras.id_contenedor', 'contenedores.id')
      ->join('especies', 'especies_siembra.id_especie', 'especies.id')
      ->where('siembras.estado', '=', 1)
      ->get();

      $registros = Registro::select()
				->join('siembras', 'registros.id_siembra', 'siembras.id' )->where('siembras.estado','=','1')
				->get();
          
      $recursos_necesarios = RecursoNecesario::select(
				'recursos_necesarios.id as id',
				'recursos_siembras.id_registro as id_registro',
				'id_siembra',
				'id_alimento',
				'id_recurso',
				'cantidad_recurso',
				'cant_manana',
				'cant_tarde',
				'conv_alimenticia',
				'minutos_hombre',
				'horas_hombre',
				'costo_kg as costo_alimento',
				'costo as costo_recurso'
				)
				->join('recursos_siembras', 'recursos_necesarios.id', 'recursos_siembras.id_registro')
				->leftJoin('alimentos', 'recursos_necesarios.id_alimento','alimentos.id')
				->leftJoin('recursos', 'recursos_necesarios.id_recurso', 'recursos.id')
				->get();
				
      $mh = Recursos::select()->where('recurso','Minutos hombre')->orWhere('recurso','Minuto hombre')->orWhere('recurso','Minutos')->first();
          
     	$data = array();
      $diff = 0 ;
      
      if(count($siembras)>0){
        for($i=0;$i<count($siembras); $i++){
          // Especies en la siembra
          
          if(count($existencias)>0){

              $contador_esp=0; 
              for($j=0;$j<count($existencias); $j++){
                  
                  $existencias[$j]->biomasa_disponible = ((($existencias[$j]->peso_actual)*($existencias[$j]->cant_actual)) / 1000);
                  $existencias[$j]->biomasa_inicial =  ((($existencias[$j]->peso_inicial)*($existencias[$j]->cantidad_inicial)) / 1000);
                  $bio_dispo = ((($existencias[$j]->peso_actual)*($existencias[$j]->cant_actual)) / 1000);
                  
                  if($siembras[$i]->id == $existencias[$j]->id_siembra ){
                      $contador_esp++;
                      $existencias[$j]->cant_actual_especie += $existencias[$j]->cant_actual;
                      $siembras[$i]->cantidad_inicial += $existencias[$j]->cantidad_inicial;
                      $siembras[$i]->peso_ini += $existencias[$j]->peso_inicial;
                      $siembras[$i]->cant_actual += $existencias[$j]->cant_actual;                            
                      $siembras[$i]->peso_actual += $existencias[$j]->peso_actual;
                      $siembras[$i]->biomasa_inicial += $existencias[$j]->biomasa_inicial;
                      $siembras[$i]->biomasa_disponible += $existencias[$j]->biomasa_disponible;                            
                      $siembras[$i]->densidad_final = (number_format(($siembras[$i]->cant_actual/$existencias[$j]->capacidad),2, ',',''));
                      $siembras[$i]->carga_final = (number_format(($siembras[$i]->biomasa_disponible/$existencias[$j]->capacidad), 2, ',',''));
                      
                      for($k=0;$k<count($registros); $k++){     
                    
                          if($existencias[$j]->id_siembra == $registros[$k]->id_siembra ){ 
                          
                              $int_tiempo =Registro::select('fecha_registro')
                                  ->orderBy('fecha_registro', 'desc')
                                  ->where('id_siembra', $existencias[$j]->id_siembra)
                                  ->where('id_especie', $existencias[$j]->id_especie)
                                  ->first();

                              $date1 = new \DateTime($existencias[$j]->fecha_inicio);
                              if (isset($int_tiempo['fecha_registro'])) {
                                $date2 = new \DateTime($int_tiempo['fecha_registro']);
                              } else {
                                $date2 = new \DateTime();
                              }
                              $diff = $date1->diff($date2);
                              $existencias[$j]->intervalo_tiempo  = $diff->days;

                              $existencias[$j]->salida_biomasa += $registros[$k]->biomasa; 
                              
                              // $siembras[$i]->mortalidad = $existencias[$j]->mortalidad;
                              if($existencias[$j]->id_especie == $registros[$k]->id_especie){
                                  $registros[$k]->mortalidad_kg = (($registros[$k]->mortalidad * $registros[$k]->peso_ganado)/1000);
                              
                                  $existencias[$j]->mortalidad += $registros[$k]->mortalidad;    
                                  // $existencias[$j]->mortalidad_kg =  (($existencias[$j]->mortalidad * $existencias[$j]->peso_ganado)/1000);
                                  $existencias[$j]->mortalidad_kg += $registros[$k]->mortalidad_kg;
                                  $existencias[$j]->salida_biomasa_especie += $registros[$k]->biomasa; 
                                  $existencias[$j]->salida_animales = (($existencias[$j]->salida_biomasa_especie * 1000)/$existencias[$j]->peso_actual);
                                  $existencias[$j]->peso_incremento = $existencias[$j]->peso_actual -  $existencias[$j]->peso_inicial;
                                  $existencias[$j]->incremento_biomasa = (($existencias[$j]->peso_incremento * $existencias[$j]->cant_actual)/1000);
                                  $existencias[$j]->ganancia_peso_dia = $existencias[$j]->peso_incremento/$existencias[$j]->intervalo_tiempo;
                                  $existencias[$j]->mortalidad_porcentaje =  (($existencias[$j]->mortalidad*100)/$existencias[$j]->cantidad_inicial);
                              }                                    
                          }   
                      }
                      $siembras[$i]->mortalidad += $existencias[$j]->mortalidad;
                      $siembras[$i]->mortalidad_kg += $existencias[$j]->mortalidad_kg;                            
                      $siembras[$i]->mortalidad_porcentaje = (($siembras[$i]->mortalidad*100)/$siembras[$i]->cantidad_inicial);                          
                      $siembras[$i]->salida_biomasa = $existencias[$j]->salida_biomasa;
                      $siembras[$i]->salida_animales += $existencias[$j]->salida_animales;       
                      $siembras[$i]->incremento_biomasa += $existencias[$j]->incremento_biomasa;
                      $siembras[$i]->intervalo_tiempo = $existencias[$j]->intervalo_tiempo;     
                      $siembras[$i]->porc_supervivencia_final = (( $siembras[$i]->salida_animales * 100) / $siembras[$i]->cantidad_inicial);
                  }     
              }
          }
          for($l=0;$l<count($recursos_necesarios); $l++){
            if($siembras[$i]->id == $recursos_necesarios[$l]->id_siembra){
              $siembras[$i]->minutos_hombre += $recursos_necesarios[$l]->minutos_hombre;
              $siembras[$i]->horas_hombre += $recursos_necesarios[$l]->horas_hombre;
              $recursos_necesarios[$l]->costo_total_recurso =  $recursos_necesarios[$l]->cantidad_recurso *  $recursos_necesarios[$l]->costo_recurso;
              $siembras[$i]->costo_total_recurso += $recursos_necesarios[$l]->costo_total_recurso;
              $recursos_necesarios[$l]->cantidad_total_alimento = $recursos_necesarios[$l]->cant_tarde + $recursos_necesarios[$l]->cant_manana;
              $siembras[$i]->cantidad_total_alimento +=  $recursos_necesarios[$l]->cantidad_total_alimento;
              $recursos_necesarios[$l]->costo_total_alimento = ($recursos_necesarios[$l]->cant_tarde + $recursos_necesarios[$l]->cant_manana)*$recursos_necesarios[$l]->costo_alimento;
              $siembras[$i]->costo_total_alimento += $recursos_necesarios[$l]->costo_total_alimento;    
              
              if($recursos_necesarios[$l]->conv_alimenticia > 0){
                $recursos_necesarios[$l]->incr_bio_acum_conver = $recursos_necesarios[$l]->cantidad_total_alimento / $recursos_necesarios[$l]->conv_alimenticia;
                $recursos_necesarios[$l]->conv_alimenticia = number_format($recursos_necesarios[$l]->conv_alimenticia,2,',','');
                $siembras[$i]->incr_bio_acum_conver +=  $recursos_necesarios[$l]->incr_bio_acum_conver;
              }
            }
          }
          $siembras[$i]->costo_minutos_hombre += ($siembras[$i]->minutos_hombre * $mh->costo );
          $siembras[$i]->costo_total_siembra = ($siembras[$i]->costo_minutos_hombre + $siembras[$i]->costo_total_alimento + $siembras[$i]->costo_total_recurso);
          
          if(($siembras[$i]->salida_biomasa)>0){
              $siembras[$i]->costo_produccion = $siembras[$i]->costo_total_siembra/ $siembras[$i]->salida_biomasa;
          }else{
          $siembras[$i]->costo_produccion = 0;
          }

          if ($siembras[$i]->incr_bio_acum_conver > 0) {
            $siembras[$i]->conversion_alimenticia = ($siembras[$i]->cantidad_total_alimento) / ($siembras[$i]->incr_bio_acum_conver);
          }

          if( $siembras[$i]->incremento_biomasa>0){
            $siembras[$i]->conversion_alimenticia_siembra = $siembras[$i]->cantidad_total_alimento /  $siembras[$i]->incremento_biomasa;                     
          }

          if (($siembras[$i]->biomasa_disponible - $siembras[$i]->biomasa_inicial)>0) {
            $siembras[$i]->conversion_alimenticia_parcial = $siembras[$i]->cantidad_total_alimento / ($siembras[$i]->biomasa_disponible - $siembras[$i]->biomasa_inicial);
          }else{
            $siembras[$i]->conversion_alimenticia_parcial=0;
          }
    
          $siembras[$i]->bio_dispo_conver = ($siembras[$i]->biomasa_inicial + $siembras[$i]->incr_bio_acum_conver) - ($siembras[$i]->biomasa_disponible + $siembras[$i]->mortalidad_kg);
          $siembras[$i]->bio_dispo_alimen = (($siembras[$i]->incr_bio_acum_conver + $siembras[$i]->biomasa_inicial) - ($siembras[$i]->salida_biomasa + $siembras[$i]->mortalidad_kg));
          
          if(($siembras[$i]->bio_dispo_alimen)>0){
            $siembras[$i]->costo_produccion_parcial = $siembras[$i]->costo_total_siembra/ $siembras[$i]->bio_dispo_alimen;
          }else{
          $siembras[$i]->costo_produccion_parcial = 0;
          }

          if($siembras[$i]->salida_animales>0 && $siembras[$i]->intervalo_tiempo>0){
              $siembras[$i]->ganancia_peso_dia = ((($siembras[$i]->salida_biomasa*1000)/$siembras[$i]->salida_animales)/$siembras[$i]->intervalo_tiempo);
          }

          $siembras[$i]->contador_esp = $contador_esp;

          if (($siembras[$i]->contador_esp)>0) {
            $siembras[$i]->peso_inicial = $siembras[$i]->peso_ini/$siembras[$i]->contador_esp;
          }else{
            $siembras[$i]->peso_inicial = 0;
          }

          if (($siembras[$i]->contador_esp)>0) {
            $siembras[$i]->peso_actual_esp = $siembras[$i]->peso_actual/$siembras[$i]->contador_esp;
          }else{
            $siembras[$i]->peso_actual_esp = 0;
          }     
        
          $data=[
            "id" => $siembras[$i]->id,
              "biomasa_inicial" => $siembras[$i]->biomasa_inicial,
              "biomasa_disponible" => $siembras[$i]->biomasa_disponible,
              'bio_dispo_conver' =>$siembras[$i]->bio_dispo_conver,
              'bio_dispo_alimen' =>$siembras[$i]->bio_dispo_alimen,
              "carga_final" => $siembras[$i]->carga_final,
              "cantidad_inicial" => $siembras[$i]->cantidad_inicial,
              "cant_actual" => $siembras[$i]->cant_actual,
              "costo_minutosh" => $siembras[$i]->costo_minutos_hombre ,
              "costo_total_recurso" => $siembras[$i]->costo_total_recurso ,
              'cantidad_total_alimento' => $siembras[$i]->cantidad_total_alimento,
              "costo_total_alimento" => $siembras[$i]->costo_total_alimento,
              "costo_tot" => $siembras[$i]->costo_total_siembra,
              "costo_produccion" => $siembras[$i]->costo_produccion,
              "costo_produccion_parcial" => $siembras[$i]->costo_produccion_parcial,
              'conversion_alimenticia' => $siembras[$i]->conversion_alimenticia,
              'conversion_alimenticia_siembra' => $siembras[$i]->conversion_alimenticia_siembra,
              'conversion_alimenticia_parcial' => $siembras[$i]->conversion_alimenticia_parcial,
              "densidad_final" => $siembras[$i]->densidad_final,
              'ganancia_peso_dia'=>$siembras[$i]->ganancia_peso_dia,
              "fecha_inicio" => $siembras[$i]->fecha_inicio,    
              "horas_hombre" => $siembras[$i]->horas_hombre,
              "minutos_hombre" => $siembras[$i]->minutos_hombre,                  
              'incr_bio_acum_conver' =>$siembras[$i]->incr_bio_acum_conver,
              'incremento_biomasa' => $siembras[$i]->incremento_biomasa,
              'intervalo_tiempo' => $siembras[$i]->intervalo_tiempo,
              "mortalidad" => $siembras[$i]->mortalidad,
              "mortalidad_kg" => $siembras[$i]->mortalidad_kg,
              "mortalidad_porcentaje" => $siembras[$i]->mortalidad_porcentaje ,
              "nombre_siembra"=>$siembras[$i]->nombre_siembra,                   
              "peso_inicial" => $siembras[$i]->peso_inicial,
              "peso_actual"=>$siembras[$i]->peso_actual_esp,
              "salida_animales"=>$siembras[$i]->salida_animales,
              "salida_biomasa" => $siembras[$i]->salida_biomasa,
              "porc_supervivencia_final" => $siembras[$i]->porc_supervivencia_final, 
              'capacidad' => $siembras[$i]->capacidad
              
          ];
        }               
      }  
      
  
      return $data;
  }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request )
    {
        //
        $registro = Registro::destroy($id);
    }
    
    public function filtroRegistros(Request $request){
        $c1 = 'registros.id'; $op1 = '!='; $c2 = '-1';
        $c3 = 'registros.tipo_registro'; $op2 = '!='; $c4 = '-1';
        $c5 = 'registros.id'; $op3 = '!='; $c6 = '-1';
        $c7 = 'registros.id'; $op4 = '!='; $c8 = '-1';
        $c9 = 'registros.id'; $op5 = '!='; $c10 = '-1';
        $c11 = "registros.peso_ganado"; $op6 = '!='; $c12 = '0';
        $c13 = "registros.peso_ganado"; $op7 = '!='; $c14 = '0';
				$c15 = 'lote'; $op8 = '!='; $c16 = '-1';

        $estado_siembra = '-1';
        $filtro_estado_siembra = '!=';
        
        if($request['f_siembra']!='-1'){$c1="registros.id_siembra"; $op1='='; $c2= $request['f_siembra'];}
        if($request['f_actividad']!='-1'){$c3="registros.tipo_registro"; $op2='='; $c4= $request['f_actividad'];}
        if($request['f_fecha_d']!='-1'){$c5="fecha_registro"; $op3='>='; $c6= $request['f_fecha_d'];}
        if($request['f_fecha_h']!='-1'){$c7="fecha_registro"; $op4='<='; $c8= $request['f_fecha_h'];}
        if($request['f_especie']!='-1'){$c9="especies.id"; $op5='='; $c10= $request['f_especie'];}
        if($request['f_peso_d']!='-1'){$c11="peso_ganado"; $op6='>='; $c12= $request['f_peso_d'];}
        if($request['f_peso_h']!='-1'){$c13="peso_ganado"; $op7='<='; $c14= $request['f_peso_h'];}
				if($request['f_lote']!='-1'){$c15="lote"; $op8='='; $c16= $request['f_lote'];}
        if($request['f_estado']!='-1'){$filtro_estado_siembra = '=';$estado_siembra= $request['f_estado'];}
        
        $registros = Registro::select(
					'registros.id as id',
					'registros.id_siembra',
					'fecha_registro',              
					'tipo_registro',
					'peso_ganado',
					'mortalidad',
					'registros.cantidad',
					'biomasa',
					'especies.especie as especie',
					'especies.id as id_especie',
					'nombre_siembra', 
					'lote',
          'especies_siembra.cant_actual as cantidad_actual',
          'especies_siembra.peso_actual as peso_actual',
          'siembras.estado as estado'
        )
        ->join('especies',
        'registros.id_especie', 'especies.id')
        ->join('siembras', 'registros.id_siembra', 'siembras.id')
				->join('especies_siembra', function($join){
					$join->on('registros.id_especie', '=', 'especies_siembra.id_especie')->on('registros.id_siembra', '=', 'especies_siembra.id_siembra');
				})
        ->where($c1, $op1, $c2)
        ->where($c3, $op2, $c4)
        ->where($c5, $op3, $c6)
        ->where($c7, $op4, $c8)
        ->where($c9, $op5, $c10)
        // ->where('peso_ganado', $op6, $c12)
        // ->where('peso_ganado', $op7, $c14)
				->where($c15, $op8, $c16)
        ->where('siembras.estado', $filtro_estado_siembra, $estado_siembra )
        ->orderBy('fecha_registro', 'desc')        
        ->get();
                    
        if(count($registros)>0){
          foreach($registros as $registro){
            $registro->biomasa_disponible = ($registro->peso_actual * $registro->cantidad_actual)/1000;
            $registro->bio_dispo_alimen = $this->BiomasaAlimento($registro->id_siembra)['bio_dispo_alimen'];
            if($registro->tipo_registro == 0)$registro->nombre_registro = 'Muestreo';
            if($registro->tipo_registro == 1)$registro->nombre_registro = 'Pesca';        
            if($registro->tipo_registro == 2)$registro->nombre_registro = 'Mortalidad Inicial';

            $registro->biomasa_disponible = number_format($registro->biomasa_disponible,2,'.','');
            $registro->bio_dispo_alimen = number_format($registro->bio_dispo_alimen,2,'.','');
          }
        }
            
        return $registros;
        
        
    }
}
