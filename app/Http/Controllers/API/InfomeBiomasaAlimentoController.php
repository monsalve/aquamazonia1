<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\EspecieSiembra;
use App\Siembra;
use App\RecursoNecesario;
use App\Recursos;
use App\Registro;
use App\Actividad;
use Illuminate\Support\Facades\DB;

class InfomeBiomasaAlimentoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

  public function index(){
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
          
     	$aux_regs = array();
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
                              $date2 = new \DateTime($int_tiempo['fecha_registro']);
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
          
          $siembras[$i]->conversion_alimenticia_siembra = number_format($siembras[$i]->conversion_alimenticia_siembra,2,',','');
          $siembras[$i]->biomasa_disponible = number_format($siembras[$i]->biomasa_disponible,2,',','');
          $siembras[$i]->mortalidad_kg = number_format($siembras[$i]->mortalidad_kg,2,',','');
          $siembras[$i]->salida_animales = number_format($siembras[$i]->salida_animales,2,',','');
          $siembras[$i]->incremento_biomasa = number_format($siembras[$i]->incremento_biomasa,2,',','');
          $siembras[$i]->bio_dispo_conver = number_format($siembras[$i]->bio_dispo_conver,2,',','');
          $siembras[$i]->bio_dispo_alimen = number_format($siembras[$i]->bio_dispo_alimen,2,',','');
          $siembras[$i]->incr_bio_acum_conver = number_format($siembras[$i]->incr_bio_acum_conver,2,',','');
          $siembras[$i]->ganancia_peso_dia = number_format($siembras[$i]->ganancia_peso_dia,2,',','');
          $siembras[$i]->peso_inicial = number_format($siembras[$i]->peso_inicial,2,',','');
          $siembras[$i]->mortalidad_porcentaje = number_format($siembras[$i]->mortalidad_porcentaje,2,',','');
          $siembras[$i]->peso_actual_esp = number_format($siembras[$i]->peso_actual_esp,2,',','');
          $siembras[$i]->horas_hombre = number_format($siembras[$i]->horas_hombre,2,',','');
          $siembras[$i]->conversion_alimenticia_parcial = number_format($siembras[$i]->conversion_alimenticia_parcial,2,',','');
          $siembras[$i]->costo_produccion_parcial = number_format($siembras[$i]->costo_produccion_parcial,2,',','');
          $siembras[$i]->costo_produccion = number_format($siembras[$i]->costo_produccion,2,',','');
          $siembras[$i]->porc_supervivencia_final = number_format($siembras[$i]->porc_supervivencia_final,2,',','');
          $siembras[$i]->costo_total_recurso = number_format($siembras[$i]->costo_total_recurso,2,',','');
          // recursos_necesarios
          $aux_regs[]=[
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
      
  
      return ['existencias'=> $aux_regs];
  }

  public function filtroBiomasaAlimento(Request $request){
  
      $c1 = "siembras.id"; $op1 = '!='; $c2 = '-1';       
      $c5 = "siembras.id"; $op3 = '!='; $c6 = '-1';
      $c7 = "siembras.id"; $op4 = '!='; $c8 = '-1';     
      
      if($request['f_siembra']!='-1'){$c1="siembras.id"; $op1='='; $c2= $request['f_siembra'];}    
      if($request['f_inicio_d']!='-1'){$c5="fecha_inicio"; $op3='>='; $c6= $request['f_inicio_d'];}
      if($request['f_inicio_h']!='-1'){$c7="fecha_inicio"; $op4='<='; $c8= $request['f_inicio_h'];}
      
      $siembras = Siembra::select()
      ->where($c1, $op1, $c2)
      ->where('siembras.estado', '=', 1)
      ->where($c5, $op3, $c6)
      ->where($c7, $op4, $c8)
      ->get();
      
      $existencias = EspecieSiembra::select(
          'cant_actual',
          'contenedor',
          'capacidad',
          'especies_siembra.cantidad as cantidad_inicial',            
          // 'especie',
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
      ->join('contenedores', 'siembras.id_contenedor', 'contenedores.id' )
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
          
      $mh = Recursos::select()->where('recurso','Minutos hombre')->orWhere('recurso','Minuto hombre')->orWhere('recurso','Minutos')->first();;
          
      $sal_bio = 0;
      $bio_acum  = 0;
      $bio_dispo = 0;
      $sum_bio_dispo=0;
    
      $aux_regs = array();
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
                                $date2 = new \DateTime($int_tiempo['fecha_registro']);
                                $diff = $date1->diff($date2);
                          
                                $existencias[$j]->intervalo_tiempo  = $diff->days;
                                $existencias[$j]->salida_biomasa += $registros[$k]->biomasa;
                                if($existencias[$j]->id_especie == $registros[$k]->id_especie){
                                    $registros[$k]->mortalidad_kg = (($registros[$k]->mortalidad * $registros[$k]->peso_ganado)/1000);
                                    $existencias[$j]->mortalidad += $registros[$k]->mortalidad;
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
              if( $siembras[$i]->incremento_biomasa>0){
                $siembras[$i]->conversion_alimenticia_siembra = $siembras[$i]->cantidad_total_alimento /  $siembras[$i]->incremento_biomasa;
              }else{
                $siembras[$i]->conversion_alimenticia_siembra = 0;
              }

              $siembras[$i]->bio_dispo_conver = ($siembras[$i]->biomasa_inicial + $siembras[$i]->incr_bio_acum_conver) - ($siembras[$i]->biomasa_disponible + $siembras[$i]->mortalidad_kg);  
              $siembras[$i]->bio_dispo_alimen = (($siembras[$i]->incr_bio_acum_conver + $siembras[$i]->biomasa_inicial) - ($siembras[$i]->salida_biomasa + $siembras[$i]->mortalidad_kg));

							if(($siembras[$i]->bio_dispo_alimen)>0){
								$siembras[$i]->costo_produccion_parcial = $siembras[$i]->costo_total_siembra/ $siembras[$i]->bio_dispo_alimen;
							}else{
							$siembras[$i]->costo_produccion_parcial = 0;
							}

              if (($siembras[$i]->biomasa_disponible - $siembras[$i]->biomasa_inicial)>0) {
                $siembras[$i]->conversion_alimenticia_parcial = $siembras[$i]->cantidad_total_alimento / ($siembras[$i]->biomasa_disponible - $siembras[$i]->biomasa_inicial);
              }else{
                $siembras[$i]->conversion_alimenticia_parcial=0;
              }
              $siembras[$i]->contador_esp = $contador_esp;
              $siembras[$i]->peso_inicial = $siembras[$i]->peso_ini/$siembras[$i]->contador_esp;
              $siembras[$i]->peso_actual_esp = $siembras[$i]->peso_actual/$siembras[$i]->contador_esp;              
              $siembras[$i]->conversion_alimenticia_siembra = number_format($siembras[$i]->conversion_alimenticia_siembra,2,',','');
              $siembras[$i]->biomasa_disponible = number_format($siembras[$i]->biomasa_disponible,2,',','');
              $siembras[$i]->mortalidad_kg = number_format($siembras[$i]->mortalidad_kg,2,',','');
              $siembras[$i]->salida_animales = number_format($siembras[$i]->salida_animales,2,',','');
              $siembras[$i]->incremento_biomasa = number_format($siembras[$i]->incremento_biomasa,2,',','');
              $siembras[$i]->bio_dispo_conver = number_format($siembras[$i]->bio_dispo_conver,2,',','');
              $siembras[$i]->bio_dispo_alimen = number_format($siembras[$i]->bio_dispo_alimen,2,',','');
              $siembras[$i]->incr_bio_acum_conver = number_format($siembras[$i]->incr_bio_acum_conver,2,',','');
              $siembras[$i]->ganancia_peso_dia = number_format($siembras[$i]->ganancia_peso_dia,2,',','');
              $siembras[$i]->peso_inicial = number_format($siembras[$i]->peso_inicial,2,',','');
              $siembras[$i]->mortalidad_porcentaje = number_format($siembras[$i]->mortalidad_porcentaje,2,',','');
              $siembras[$i]->peso_actual_esp = number_format($siembras[$i]->peso_actual_esp,2,',','');
              $siembras[$i]->horas_hombre = number_format($siembras[$i]->horas_hombre,2,',','');
              $siembras[$i]->conversion_alimenticia_parcial = number_format($siembras[$i]->conversion_alimenticia_parcial,2,',','');
              $siembras[$i]->costo_produccion = number_format($siembras[$i]->costo_produccion,2,',','');
							$siembras[$i]->costo_produccion_parcial = number_format($siembras[$i]->costo_produccion_parcial,2,',','');
              $siembras[$i]->porc_supervivencia_final = number_format($siembras[$i]->porc_supervivencia_final,2,',','');
              $siembras[$i]->costo_total_recurso = number_format($siembras[$i]->costo_total_recurso,2,',','');
              // recursos_necesarios
              $aux_regs[]=[
                  "biomasa_inicial" => $siembras[$i]->biomasa_inicial,
                  "biomasa_disponible" => $siembras[$i]->biomasa_disponible,
                  'bio_dispo_conver' =>$siembras[$i]->bio_dispo_conver,
                  'bio_dispo_alimen' =>$siembras[$i]->bio_dispo_alimen,
                  "carga_final" => $siembras[$i]->carga_final,
                  "cantidad_inicial" => $siembras[$i]->cantidad_inicial,
                  "cant_actual" => $siembras[$i]->cant_actual,
                  "costo_minutosh" => $siembras[$i]->costo_minutos_hombre ,
                  "costo_total_recurso" => $siembras[$i]->costo_total_recurso,
                  'cantidad_total_alimento' => $siembras[$i]->cantidad_total_alimento,
                  "costo_total_alimento" => $siembras[$i]->costo_total_alimento,
                  "costo_tot" => $siembras[$i]->costo_total_siembra,
                  "costo_produccion" => $siembras[$i]->costo_produccion,
									"costo_produccion_parcial" => $siembras[$i]->costo_produccion_parcial,
                  'conversion_alimenticia_parcial' => $siembras[$i]->conversion_alimenticia_parcial,
                  'conversion_alimenticia_siembra' => $siembras[$i]->conversion_alimenticia_siembra,
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
              ];
          }               
      }
      return ['existencias'=> $aux_regs];
  }
}
