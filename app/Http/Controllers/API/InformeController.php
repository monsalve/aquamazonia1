<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\EspecieSiembra;
use App\Siembra;
use App\RecursoSiembra;
use App\RecursoNecesario;
use App\Recursos;
use App\Registro;
use App\Actividad;
use Illuminate\Support\Facades\DB;

class InformeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $horas_hombre = Recursos::select()->where('recurso','Hora hombre')->orWhere('recurso','Horas hombre')->get();
        
        $recursosNecesarios = RecursoNecesario::orderBy('fecha_ra', 'desc')
        ->leftJoin('recursos', 'recursos_necesarios.id_recurso', 'recursos.id')
        ->leftJoin('alimentos', 'recursos_necesarios.id_alimento','alimentos.id')
        ->join('recursos_siembras', 'recursos_necesarios.id', 'recursos_siembras.id_registro')
        ->join('siembras', 'recursos_siembras.id_siembra', 'siembras.id')
        ->join('actividades','recursos_necesarios.tipo_actividad','actividades.id')
        ->select('recursos.id as idr', 'alimentos.id as ida', 'recursos_necesarios.id as id','actividad', 'horas_hombre', 'id_recurso', 'id_alimento', 'fecha_ra', 'horas_hombre', 'cant_manana', 'cant_tarde', 'detalles', 'tipo_actividad', 'recurso', 'alimento', 'recursos.costo as costo_r', 'alimentos.costo_kg as costo_a', 'nombre_siembra', 'estado', 'cantidad_recurso')
        ->get();
            
        $acumula=0;
        $acumula2=0;
        $acumula3=0;
        
        if(count($recursosNecesarios)>0){
            for($i=0;$i<count($recursosNecesarios); $i++){
                
                $acumula+=($recursosNecesarios[$i]->cantidad_recurso * $recursosNecesarios[$i]->costo_r);
                $recursosNecesarios[$i]->costo_r_acum = number_format($acumula, 2, ',', '');
                
                $recursosNecesarios[$i]->costo_total_alimento = ($recursosNecesarios[$i]->cant_tarde + $recursosNecesarios[$i]->cant_manana) * $recursosNecesarios[$i]->costo_a; 
                
                $acumula2+=(($recursosNecesarios[$i]->cant_tarde + $recursosNecesarios[$i]->cant_manana) * $recursosNecesarios[$i]->costo_a);
                $recursosNecesarios[$i]->costo_a_acum = number_format($acumula2, 2, ',', '');
                // $acumula2+=$recursosNecesarios[$i]->costo_total_alimento;
                // $recursosNecesarios[$i]->costo_a_acum = number_format($acumula2, 2, ',', '');
                
                $recursosNecesarios[$i]->costo_horash = $recursosNecesarios[$i]->horas_hombre*$horas_hombre[0]->costo;
                $acumula3+=$recursosNecesarios[$i]->costo_horash;
                $recursosNecesarios[$i]->costo_h_acum = number_format($acumula3, 2, ',', '');
            }
        }
        $recursosSiembras = RecursoSiembra::select('recursos_siembras.id as id', 'id_registro', 'id_siembra', 'id_recurso', 'id_alimento', 'fecha_ra', 'horas_hombre', 'cant_manana', 'cant_tarde', 'detalles', 'tipo_actividad', 'recursos_necesarios.id as idrn', 'nombre_siembra', 'alimento', 'recurso', 'estado','cantidad_recurso')
        ->join('recursos_necesarios', 'recursos_siembras.id_registro', 'recursos_necesarios.id')
        ->join('siembras', 'recursos_siembras.id_siembra', 'siembras.id')
        ->join('recursos', 'recursos_necesarios.id_recurso', 'recursos.id')
        ->join('alimentos', 'recursos_necesarios.id_alimento','alimentos.id')
        ->get();
       
        return ['recursosNecesarios' => $recursosNecesarios, 'recursosSiembras' => $recursosSiembras];
    }


    public function informeRecursos(Request $request)
    {
        $horas_hombre = Recursos::select()->where('recurso','Hora hombre')->orWhere('recurso','Horas hombre')->get();
        
        $c1 = 'tipo_actividad'; $op1 = '!='; $c2 = '-1';
        $c3 = 'tipo_actividad'; $op2 = '!='; $c4 = '-1';
        $c5 = 'tipo_actividad'; $op3 = '!='; $c6 = '-1';
        $c7 = 'tipo_actividad'; $op4 = '!='; $c8 = '-1';
        $c9 = 'tipo_actividad'; $op5 = '!='; $c10 = '-1';
        $c11 = 'tipo_actividad'; $op6 = '!='; $c12 = '-1';
        $c13 = 'tipo_actividad'; $op7 = '!='; $c14 = '-1';
       
        if($request['estado_s']!='-1'){$c1="estado"; $op1='='; $c2= $request['estado_s'];}
        if($request['actividad_s']!='-1'){$c3="tipo_actividad"; $op2='='; $c4= $request['actividad_s'];}
        if($request['alimento_s']!='-1'){$c5="id_alimento"; $op3='='; $c6= $request['alimento_s'];}
        if($request['recurso_s']!='-1'){$c7="id_recurso"; $op4='='; $c8= $request['recurso_s'];}
        if($request['fecha_ra1']!='-1'){$c9="fecha_ra"; $op5='>='; $c10=$request['fecha_ra1'];}
        if($request['fecha_ra2']!='-1'){$c11="fecha_ra"; $op6='<='; $c12=$request['fecha_ra2'];}        
        if($request['f_siembra']!='-1'){$c13="siembras.id"; $op7='='; $c14= $request['f_siembra'];}
        
        $recursosNecesarios = RecursoNecesario::orderBy('fecha_ra', 'desc')
        ->leftJoin('recursos', 'recursos_necesarios.id_recurso', 'recursos.id')
        ->leftJoin('alimentos', 'recursos_necesarios.id_alimento','alimentos.id')
        ->join('recursos_siembras', 'recursos_necesarios.id', 'recursos_siembras.id_registro')
        ->join('siembras', 'recursos_siembras.id_siembra', 'siembras.id')
        ->join('actividades','recursos_necesarios.tipo_actividad','actividades.id')
        ->select('recursos.id as idr', 'alimentos.id as ida', 'recursos_necesarios.id as id','actividad', 'horas_hombre', 'id_recurso', 'id_alimento', 'fecha_ra', 'horas_hombre', 'cant_manana', 'cant_tarde', 'detalles', 'tipo_actividad', 'recurso', 'alimento', 'recursos.costo as costo_r', 'alimentos.costo_kg as costo_a', 'nombre_siembra', 'siembras.estado as estado','cantidad_recurso')
        ->where($c1, $op1, $c3)
        ->where($c3, $op2, $c4)
        ->where($c5, $op3, $c6)
        ->where($c7, $op4, $c8)
        ->where($c9, $op5, $c10)
        ->where($c11, $op6, $c12)
        ->where($c13, $op7, $c14)
        ->get();
        //  return 'Hola';    
        $acumula=0;
        $acumula2=0;
        $acumula3=0;
        
        if(count($recursosNecesarios)>0){
            for($i=0;$i<count($recursosNecesarios); $i++){
                
                $acumula+=($recursosNecesarios[$i]->cantidad_recurso * $recursosNecesarios[$i]->costo_r);
                $recursosNecesarios[$i]->costo_r_acum = number_format($acumula, 2, ',', '');
                
                $recursosNecesarios[$i]->costo_total_alimento = ($recursosNecesarios[$i]->cant_tarde + $recursosNecesarios[$i]->cant_manana) * $recursosNecesarios[$i]->costo_a; 
                
                $acumula2+=$recursosNecesarios[$i]->costo_total_alimento;
                $recursosNecesarios[$i]->costo_a_acum = number_format($acumula2, 2, ',', '');
                
                $recursosNecesarios[$i]->costo_horash = $recursosNecesarios[$i]->horas_hombre*$horas_hombre[0]->costo;
                $acumula3+=$recursosNecesarios[$i]->costo_horash;
                $recursosNecesarios[$i]->costo_h_acum = number_format($acumula3, 2, ',', '');
            }
        }
       
        return ['recursosNecesarios' => $recursosNecesarios ];
        // return redirect()->route('informe-excel',  ['recursosNecesarios' => $recursosNecesarios ]);        
    }
    public function traerInformes(){
        $horas_hombre = Recursos::select()->where('recurso','Hora hombre')->orWhere('recurso','Horas hombre')->get();

        $recursosNecesarios = RecursoNecesario::orderBy('fecha_ra', 'desc')
        ->join('recursos', 'recursos_necesarios.id_recurso', 'recursos.id')
        ->join('alimentos', 'recursos_necesarios.id_alimento','alimentos.id')
        ->join('recursos_siembras', 'recursos_necesarios.id', 'recursos_siembras.id_registro')
        ->join('siembras', 'recursos_siembras.id_siembra', 'siembras.id')
        ->join('actividades','recursos_necesarios.tipo_actividad','actividades.id')
        ->select('recursos_necesarios.id as id', 'horas_hombre', 'id_recurso', 'id_alimento','actividad', 'fecha_ra', 'cant_manana', 'cant_tarde', 'detalles', 'tipo_actividad', 'recurso', 'alimento', 'recursos.costo as costo_r', 'alimentos.costo_kg as costo_a', 'nombre_siembra', 'estado', 'cantidad_recurso')
        ->get();
        
        $acumula=0;
        $acumula2=0;
        $acumula3=0;
        
        if(count($recursosNecesarios)>0){
            for($i=0;$i<count($recursosNecesarios); $i++){
                
                $acumula+=$recursosNecesarios[$i]->costo_r;
                $recursosNecesarios[$i]->costo_r_acum = $acumula;
                
                $recursosNecesarios[$i]->costo_total_alimento = ($recursosNecesarios[$i]->cant_tarde + $recursosNecesarios[$i]->cant_manana) * $recursosNecesarios[$i]->costo_a; 
                
                $acumula2+=$recursosNecesarios[$i]->costo_total_alimento;
                $recursosNecesarios[$i]->costo_a_acum = $acumula2;
                
                $recursosNecesarios[$i]->costo_horash = $recursosNecesarios[$i]->horas_hombre*$horas_hombre[0]->costo;
                $acumula3+=$recursosNecesarios[$i]->costo_horash;
                $recursosNecesarios[$i]->costo_h_acum = $acumula3;
            }
        }
        return ['recursosNecesarios' => $recursosNecesarios];
    }
    public function filtroInformes(Request $request){
        $horas_hombre = Recursos::select()->where('recurso','Hora hombre')->orWhere('recurso','Horas hombre')->get();
    
        $c1 = 'tipo_actividad'; $op1 = '!='; $c2 = '-1';
        $c3 = 'tipo_actividad'; $op2 = '!='; $c4 = '-1';
        $c5 = 'tipo_actividad'; $op3 = '!='; $c6 = '-1';
        $c7 = 'tipo_actividad'; $op4 = '!='; $c8 = '-1';
        $c9 = 'tipo_actividad'; $op5 = '!='; $c10 = '-1';
        $c11 = 'tipo_actividad'; $op6 = '!='; $c12 = '-1';
        $c13 = 'tipo_actividad'; $op7 = '!='; $c14 = '-1';
        
        if($request['estado_s']!='-1'){$c1="estado"; $op1='='; $c2= $request['estado_s'];}
        if($request['actividad_s']!='-1'){$c3="tipo_actividad"; $op2='='; $c4= $request['actividad_s'];}
        if($request['alimento_s']!='-1'){$c5="id_alimento"; $op3='='; $c6= $request['alimento_s'];}
        if($request['recurso_s']!='-1'){$c7="id_recurso"; $op4='='; $c8= $request['recurso_s'];}
        if($request['fecha_ra1']!='-1'){$c9="fecha_ra"; $op5='>='; $c10=$request['fecha_ra1'];}
        if($request['fecha_ra2']!='-1'){$c11="fecha_ra"; $op6='<='; $c12=$request['fecha_ra2'];}        
        if($request['f_siembra']!='-1'){$c13="siembras.id"; $op7='='; $c14= $request['f_siembra'];}
    
        $recursosNecesarios = RecursoNecesario::orderBy('fecha_ra', 'desc')
        ->leftJoin('recursos', 'recursos_necesarios.id_recurso', 'recursos.id')
        ->leftJoin('alimentos', 'recursos_necesarios.id_alimento','alimentos.id')
        ->join('recursos_siembras', 'recursos_necesarios.id', 'recursos_siembras.id_registro')
        ->join('siembras', 'recursos_siembras.id_siembra', 'siembras.id')
        ->join('actividades','recursos_necesarios.tipo_actividad','actividades.id')
        ->select('recursos.id as idr', 'alimentos.id as ida', 'recursos_necesarios.id as id', 'actividad','horas_hombre', 'id_recurso', 'id_alimento', 'fecha_ra', 'horas_hombre', 'cant_manana', 'cant_tarde', 'detalles', 'tipo_actividad', 'recurso', 'alimento', 'recursos.costo as costo_r', 'alimentos.costo_kg as costo_a', 'nombre_siembra', 'estado', 'cantidad_recurso')
        ->where($c1, $op1, $c2)
        ->where($c3, $op2, $c4)
        ->where($c5, $op3, $c6)
        ->where($c7, $op4, $c8)
        ->where($c9, $op5, $c10)
        ->where($c11, $op6, $c12)
        ->where($c13, $op7, $c14)
        ->get();
        
        $acumula=0;
        $acumula2=0;
        $acumula3=0;
        
        if(count($recursosNecesarios)>0){
            for($i=0;$i<count($recursosNecesarios); $i++){
                
                $acumula+=($recursosNecesarios[$i]->cantidad_recurso * $recursosNecesarios[$i]->costo_r);
                $recursosNecesarios[$i]->costo_r_acum = number_format($acumula, 2, ',', '');
                
                $recursosNecesarios[$i]->costo_total_alimento = ($recursosNecesarios[$i]->cant_tarde + $recursosNecesarios[$i]->cant_manana) * $recursosNecesarios[$i]->costo_a; 
                
                $acumula2+=(($recursosNecesarios[$i]->cant_tarde + $recursosNecesarios[$i]->cant_manana) * $recursosNecesarios[$i]->costo_a);
                $recursosNecesarios[$i]->costo_a_acum = number_format($acumula2, 2, ',', '');
                        
                $recursosNecesarios[$i]->costo_horash = $recursosNecesarios[$i]->horas_hombre*$horas_hombre[0]->costo;
                $acumula3+=$recursosNecesarios[$i]->costo_horash;
                $recursosNecesarios[$i]->costo_h_acum = number_format($acumula3, 2, ',', '');
            }
        }
        
        $recursosSiembras = RecursoSiembra::select('recursos_siembras.id as id', 'id_registro', 'id_siembra', 'estado', 'nombre_siembra')
        ->join('recursos_necesarios', 'recursos_siembras.id_registro', 'recursos_necesarios.id')
        ->join('siembras', 'recursos_siembras.id_siembra', 'siembras.id')
        ->where('estado', $op1, $c2)
        ->get();
        

        return ['recursosNecesarios' => $recursosNecesarios, 'recursosSiembras' => $recursosSiembras];
    }    
    public function traerExistencias(){
    
        $existencias = EspecieSiembra::select(
            'cant_actual',
            'contenedor',
            'capacidad',
            'especies_siembra.cantidad as cantidad_inicial',            
            'especie',
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
             
        $var1 = 0;
        $var2 = 0;
        $var3 = 0;
        $var4 = 0;
        $sal_bio = 0;
        $bio_acum  = 0;
        $diff = 0 ;
        
        $registros = Registro::select()
            ->join('siembras', 'registros.id_siembra', 'siembras.id' )->where('siembras.estado','=','1')
            ->get();
            
        if(count($existencias)>0){
        
            for($i=0;$i<count($existencias); $i++){
                
                
                $existencias[$i]->biomasa_disponible = ((($existencias[$i]->peso_actual)*($existencias[$i]->cant_actual)) / 1000);
                $existencias[$i]->biomasa_disponible = number_format($existencias[$i]->biomasa_disponible,2,',','');

                $bio_dispo = ((($existencias[$i]->peso_actual)*($existencias[$i]->cant_actual)) / 1000);
                
            // var_dump(number_format((float)$existencias[$i]->biomasa_disponible,2,',',''));
                
                for($j=0;$j<count($registros); $j++){                   
                
                    if(count($registros)>0){
                        if($existencias[$i]->id_siembra == $registros[$j]->id_siembra && $existencias[$i]->id_especie == $registros[$j]->id_especie ){   
                        
                            $int_tiempo =Registro::select('fecha_registro')
                                ->orderBy('fecha_registro', 'desc')
                                ->where('id_siembra', $existencias[$i]->id_siembra)
                                ->where('id_especie', $existencias[$i]->id_especie)
                                ->limit(1)
                                ->get();
                            $date1 = new \DateTime($existencias[$i]->fecha_inicio);
                            $date2 = new \DateTime($int_tiempo[0]->fecha_registro);
                            $diff = $date1->diff($date2);
                      
                            $existencias[$i]->intervalo_tiempo  = $diff->days;
                            // $existencias[$i]->salida_biomasa += $registros[$j]->biomasa;                                 
                            $existencias[$i]->salida_biomasa += $registros[$j]->biomasa;
                            
                            $bio_acum += $registros[$j]->biomasa;
                            $existencias[$i]->biomasa_acumulada = number_format($bio_acum, 2, ',','');
                            $existencias[$i]->mortalidad += $registros[$j]->mortalidad;
                            $existencias[$i]->mortalidad_kg =  (number_format((($existencias[$i]->mortalidad * $existencias[$i]->peso_actual)/1000),2, ',',''));
                            $existencias[$i]->mortalidad_porcentaje =  (number_format((($existencias[$i]->mortalidad * 100)/$existencias[$i]->cantidad_inicial),2, ',',''));
                            $var2 = ($var1 * $existencias[$i]->peso_actual )/1000;
                            $existencias[$i]->mortalidad_kg_au = (number_format(($var2),2,',',''));
                            $existencias[$i]->salida_animales = (number_format((($existencias[$i]->salida_biomasa * 1000)/$existencias[$i]->peso_actual),2, ',',''));                    
                            $existencias[$i]->densidad_final = (number_format(($existencias[$i]->cant_actual/$existencias[$i]->capacidad),2, ',',''));
                            $existencias[$i]->carga_final = (number_format(($bio_dispo/$existencias[$i]->capacidad), 2, ',',''));
                        }                        
                    }                    
                }
                
            }               
        }           
        
        return ['existencias'=> $existencias];
        
    }

    public function filtroExistencias(Request $request){
        $c1 = "siembras.id"; $op1 = '!='; $c2 = '-1';
        $c3 = "siembras.id"; $op2 = '!='; $c4 = '-1';
        $c5 = "siembras.id"; $op3 = '!='; $c6 = '-1';
        $c7 = "siembras.id"; $op4 = '!='; $c8 = '-1';
        
        if($request['f_siembra']!='-1'){$c1="siembras.id"; $op1='='; $c2= $request['f_siembra'];}
        if($request['f_especie']!='-1'){$c3="especies.id"; $op2='='; $c4= $request['f_especie'];}
        if($request['f_inicio_d']!='-1'){$c5="fecha_inicio"; $op3='>='; $c6= $request['f_inicio_d'];}
        if($request['f_inicio_h']!='-1'){$c7="fecha_inicio"; $op4='<='; $c8= $request['f_inicio_h'];}
        
        $existencias = EspecieSiembra::select(
            'cant_actual',
            'contenedor',
            'capacidad',
            'especies_siembra.cantidad as cantidad_inicial',            
            'especie',
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
        ->join('especies', 'especies_siembra.id_especie', 'especies.id')
        ->join('contenedores', 'siembras.id_contenedor', 'contenedores.id' )
        ->where('siembras.estado', '=', 1)
        ->where($c1, $op1, $c2)
        ->where($c3, $op2, $c4)
        ->where($c5, $op3, $c6)
        ->where($c7, $op4, $c8)
        ->get();
        
        $var1 = 0;
        $var2 = 0;
        $var3 = 0;
        $sal_bio = 0;
        $bio_acum  = 0;
        $int_tiempo = 0;
        $registros = Registro::select()->get();
         
        if(count($existencias)>0){
        
            for($i=0;$i<count($existencias); $i++){
                
                
                $existencias[$i]->biomasa_disponible = ((($existencias[$i]->peso_actual)*($existencias[$i]->cant_actual)) / 1000);                
                $existencias[$i]->biomasa_disponible = number_format($existencias[$i]->biomasa_disponible,2,',','');

                $bio_dispo = ((($existencias[$i]->peso_actual)*($existencias[$i]->cant_actual)) / 1000);
                
            // var_dump(number_format((float)$existencias[$i]->biomasa_disponible,2,',',''));
                
                for($j=0;$j<count($registros); $j++){                   
                
                    if(count($registros)>0){
                        if($existencias[$i]->id_siembra == $registros[$j]->id_siembra && $existencias[$i]->id_especie == $registros[$j]->id_especie ){   
                        
                            $int_tiempo =Registro::select('fecha_registro')
                                ->orderBy('fecha_registro', 'desc')
                                ->where('id_siembra', $existencias[$i]->id_siembra)
                                ->where('id_especie', $existencias[$i]->id_especie)
                                ->limit(1)
                                ->get();
                            $date1 = new \DateTime($existencias[$i]->fecha_inicio);
                            $date2 = new \DateTime($int_tiempo[0]->fecha_registro);
                            $diff = $date1->diff($date2);
                      
                            $existencias[$i]->intervalo_tiempo  = $diff->days;
                            $existencias[$i]->salida_biomasa += $registros[$j]->biomasa;
                            
                            $bio_acum += $registros[$j]->biomasa;
                            $existencias[$i]->biomasa_acumulada = number_format($bio_acum, 2, ',','');
                            $existencias[$i]->mortalidad += $registros[$j]->mortalidad;
                            $existencias[$i]->mortalidad_kg =  (number_format((($existencias[$i]->mortalidad * $existencias[$i]->peso_actual)/1000),2, ',',''));
                            $existencias[$i]->mortalidad_porcentaje =  (number_format((($existencias[$i]->mortalidad * 100)/$existencias[$i]->cantidad_inicial),2, ',',''));
                            $var2 = ($var1 * $existencias[$i]->peso_actual )/1000;
                            $existencias[$i]->mortalidad_kg_au = (number_format(($var2),2,',',''));
                            $existencias[$i]->salida_animales = (number_format((($existencias[$i]->salida_biomasa * 1000)/$existencias[$i]->peso_actual),2, ',',''));                    
                            $existencias[$i]->densidad_final = (number_format(($existencias[$i]->cant_actual/$existencias[$i]->capacidad),2, ',',''));
                            $existencias[$i]->carga_final = (number_format(($bio_dispo/$existencias[$i]->capacidad), 2, ',',''));
                        }                        
                    }                    
                }
                
            }               
        }           
                           
        return ['existencias'=> $existencias, $registros];
    }
    public function traerExistenciasDetalle(){
        $siembras = Siembra::select()->get();
        
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
            'horas_hombre',
            'costo_kg as costo_alimento',
            'costo as costo_recurso'
            )
            ->join('recursos_siembras', 'recursos_necesarios.id', 'recursos_siembras.id_registro')
            ->leftJoin('alimentos', 'recursos_necesarios.id_alimento','alimentos.id')
            ->leftJoin('recursos', 'recursos_necesarios.id_recurso', 'recursos.id')
            // ->join('siembras', 'recursos_siembras.id_siembra', 'siembras.id')
            ->get();
        $hh = Recursos::select()->where('recurso','Hora hombre')->orWhere('recurso','Horas hombre')->first();
            
        // print_r($hh->costo);
        // exit;
     
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
                    for($j=0;$j<count($existencias); $j++){
                        $existencias[$j]->biomasa_disponible = ((($existencias[$j]->peso_actual)*($existencias[$j]->cant_actual)) / 1000);
                        $bio_dispo = ((($existencias[$j]->peso_actual)*($existencias[$j]->cant_actual)) / 1000);
                        
                        if($existencias[$j]->id_siembra == $siembras[$i]->id){
                            $existencias[$j]->cant_actual_especie += $existencias[$j]->cant_actual;
                            $siembras[$i]->cantidad_inicial += $existencias[$j]->cantidad_inicial;
                            $siembras[$i]->peso_inicial += $existencias[$j]->peso_inicial;
                            $siembras[$i]->cant_actual += $existencias[$j]->cant_actual;                            
                            $siembras[$i]->peso_actual += $existencias[$j]->peso_actual;
                            $siembras[$i]->biomasa_disponible += $existencias[$j]->biomasa_disponible;                            
                            $siembras[$i]->densidad_final = (number_format(($siembras[$i]->cant_actual/$existencias[$j]->capacidad),2, ',',''));
                            $siembras[$i]->carga_final = (number_format(($siembras[$i]->biomasa_disponible/$existencias[$j]->capacidad), 2, ',',''));
                            
                            for($k=0;$k<count($registros); $k++){     
                                
                                // && $existencias[$j]->id_especie == $registros[$k]->id_especie 
                                if($existencias[$j]->id_siembra == $registros[$k]->id_siembra ){   
                                
                                    $int_tiempo =Registro::select('fecha_registro')
                                        ->orderBy('fecha_registro', 'desc')
                                        ->where('id_siembra', $existencias[$j]->id_siembra)
                                        ->where('id_especie', $existencias[$j]->id_especie)
                                        ->limit(1)
                                        ->get();
                                    $date1 = new \DateTime($existencias[$j]->fecha_inicio);
                                    $date2 = new \DateTime($int_tiempo[0]->fecha_registro);
                                    $diff = $date1->diff($date2);
                              
                                    $existencias[$j]->intervalo_tiempo  = $diff->days;
                                    $existencias[$j]->salida_biomasa += $registros[$k]->biomasa; 
                                    
                                    // $siembras[$i]->mortalidad = $existencias[$j]->mortalidad;
                                    if($existencias[$j]->id_especie == $registros[$k]->id_especie){
                                        $existencias[$j]->mortalidad += $registros[$k]->mortalidad;    
                                        $existencias[$j]->mortalidad_kg =  (($existencias[$j]->mortalidad * $existencias[$j]->peso_actual)/1000);
                                        
                                        $existencias[$j]->salida_biomasa_especie += $registros[$k]->biomasa; 
                                        $existencias[$j]->salida_animales = (($existencias[$j]->salida_biomasa_especie * 1000)/$existencias[$j]->peso_actual);
                                    }                                  
                                }                                                        
                            }
                            $siembras[$i]->mortalidad += $existencias[$j]->mortalidad;
                            $siembras[$i]->mortalidad_kg += $existencias[$j]->mortalidad_kg;                            
                            $siembras[$i]->mortalidad_porcentaje = (($siembras[$i]->mortalidad*100)/$siembras[$i]->cantidad_inicial);
                            $siembras[$i]->salida_biomasa = $existencias[$j]->salida_biomasa;
                            $siembras[$i]->salida_animales += $existencias[$j]->salida_animales;                            
                        }                     
                    }
                }
                 for($l=0;$l<count($recursos_necesarios); $l++){
                    if($siembras[$i]->id == $recursos_necesarios[$l]->id_siembra){
                        $siembras[$i]->horas_hombre += $recursos_necesarios[$l]->horas_hombre;
                        $recursos_necesarios[$l]->costo_total_recurso =  $recursos_necesarios[$l]->cantidad_recurso *  $recursos_necesarios[$l]->costo_recurso;
                        $siembras[$i]->costo_total_recurso += $recursos_necesarios[$l]->costo_total_recurso;
                        
                        $recursos_necesarios[$l]->costo_total_alimento = ($recursos_necesarios[$l]->cant_tarde + $recursos_necesarios[$l]->cant_manana)*$recursos_necesarios[$l]->costo_alimento;
                        $siembras[$i]->costo_total_alimento += $recursos_necesarios[$l]->costo_total_alimento;                        
                       
                        // $costo_tot = $costo_horash + $costo_total_recurso + $costo_total_alimento;
                        // $costo_horash = number_format($costo_horash, 2, ',', '');
                        // $costo_total_recurso = number_format($costo_total_recurso, 2, ',', '');
                        // $costo_total_alimento = number_format($costo_total_alimento, 2, ',', '');
                        // $costo_tot = number_format($costo_tot, 2, ',', '');
                    }
                 }
                 $siembras[$i]->costo_horas_hombre += ($siembras[$i]->horas_hombre * $hh->costo );
                 $siembras[$i]->costo_total_siembra = ($siembras[$i]->costo_horas_hombre + $siembras[$i]->costo_total_alimento + $siembras[$i]->costo_total_recurso);
                 
                // recursos_necesarios
                $aux_regs[]=[
                    "nombre_siembra"=>$siembras[$i]->nombre_siembra,
                    "fecha_inicio" => $siembras[$i]->fecha_inicio,
                    "cantidad_inicial" => $siembras[$i]->cantidad_inicial,
                    "peso_inicial" => $siembras[$i]->peso_inicial,
                    "peso_actual"=>$siembras[$i]->peso_actual,
                    "cant_actual" => $siembras[$i]->cant_actual,
                    "biomasa_disponible" => $siembras[$i]->biomasa_disponible,
                    "salida_biomasa" => $siembras[$i]->salida_biomasa,
                    "mortalidad" => $siembras[$i]->mortalidad,
                    "mortalidad_kg" => $siembras[$i]->mortalidad_kg,
                    "mortalidad_porcentaje" => $siembras[$i]->mortalidad_porcentaje ,
                    "salida_animales"=>$siembras[$i]->salida_animales,
                    "densidad_final" => $siembras[$i]->densidad_final,
                    "carga_final" => $siembras[$i]->carga_final,
                    "horas_hombre" => $siembras[$i]->horas_hombre,
                    "costo_horash" => $siembras[$i]->costo_horas_hombre ,
                    "costo_total_recurso" => $siembras[$i]->costo_total_recurso ,
                    "costo_total_alimento" => $siembras[$i]->costo_total_alimento,
                    "costo_tot" => $siembras[$i]->costo_total_siembra 
                ];
                 
                
            }               
        }  
        
    
        return ['existencias'=> $aux_regs];
    }
    public function traerExistenciasDetallexxxxxx() {
        
        $existencias = EspecieSiembra::select(
                'siembras.id as id',        
                DB::raw('SUM(cant_actual) as cant_actual'),
                'contenedor',
                'capacidad',                     
                DB::raw('SUM(especies_siembra.cantidad) as cantidad_inicial'),
                'especies_siembra.id_siembra as id_siembra',          
                'fecha_inicio',            
                'nombre_siembra',                        
                DB::raw('SUM(peso_inicial) as peso_inicial'),            
                DB::raw('SUM(peso_actual) as peso_actual'),
            )
            ->join('siembras', 'especies_siembra.id_siembra', 'siembras.id' )
            ->join('contenedores', 'siembras.id_contenedor', 'contenedores.id' )
            ->where('siembras.estado', '=', 1)
            ->orderBy('siembras.nombre_siembra')
            ->groupBy('siembras.id')
            ->groupBy('contenedor')
            ->groupBy('fecha_inicio')
            ->groupBy('nombre_siembra')        
            ->groupBy('capacidad')
            ->groupBy('especies_siembra.id_siembra')
            ->get();
		
        $aux_regs = array();
        
        foreach($existencias as $exist ) {
            $especies_siembra = EspecieSiembra::select()
            ->join('siembras', 'especies_siembra.id_siembra', 'siembras.id' )
            ->where('siembras.id', $exist->id)
            ->get();
            $sum_bio = 0;
            if(count($especies_siembra)>0){        
                for($i=0;$i<count($especies_siembra); $i++){
                    $especies_siembra[$i]->biomasa_disponible = ((($especies_siembra[$i]->peso_actual)*($especies_siembra[$i]->cant_actual)) / 1000);
                    $sum_bio +=$especies_siembra[$i]->biomasa_disponible;
                    $biomasa_disponible = number_format($sum_bio,2,',','');
                }
            }
            
            $registros = Registro::select(
                'siembras.id',
                DB::raw('SUM(biomasa) as salida_biomasa'),
                DB::raw('SUM(mortalidad) as mortalidad'),
            )
            ->join('siembras', 'registros.id_siembra', 'siembras.id' )->where('siembras.estado','=','1')
            ->where('siembras.id','=',$exist->id)
            ->groupBy('siembras.id')
            ->first();
			$mortalidad_kg = 0;	$mortalidad_porcentaje=0;	$salida_animales=0;
			$salida_biomasa = 0; $mortalidad = 0;
			
			$mortalidad = $registros->mortalidad;
			$salida_biomasa = $registros->salida_biomasa;
			// print_r( $exist->peso_actual);
			// if(isset($registros->id)){
                
				$mortalidad_kg =  (number_format((($registros->mortalidad * $exist->peso_actual)/1000),2, ',',''));
				$mortalidad_porcentaje =  (number_format((($registros->mortalidad * 100)/$exist->cantidad_inicial),2, ',',''));
			    $salida_animales = (number_format((($registros->salida_biomasa * 1000)/$exist->peso_actual),2, ',',''));
			// }
            $densidad_final = (number_format(($exist->cant_actual/$exist->capacidad),2, ',',''));

            $bio_dispo = ((($exist->peso_actual)*($exist->cant_actual)) / 1000);
            $carga_final = (number_format(($bio_dispo/$exist->capacidad), 2, ',',''));
        
            $recursosNecesarios = RecursoNecesario::orderBy('fecha_ra', 'desc')
                ->join('recursos_siembras', 'recursos_necesarios.id', 'recursos_siembras.id_registro')
                ->leftJoin('alimentos', 'recursos_necesarios.id_alimento','alimentos.id')
                ->leftJoin('recursos', 'recursos_necesarios.id_recurso', 'recursos.id')
                ->join('siembras', 'recursos_siembras.id_siembra', 'siembras.id')
                ->where('recursos_siembras.id_siembra','=',$exist->id)
                ->where('estado',1)
                ->get();
            $horas_hombre = 0;
            $costo_horash  = 0;
            $costo_total_recurso = 0;
            $costo_total_alimento = 0;
            $hh = Recursos::select()->where('recurso','Hora hombre')->orWhere('recurso','Horas hombre')->get();
            
         
            if(count($recursosNecesarios)>0){
                for($i=0;$i<count($recursosNecesarios); $i++){   
                    $costo_total_recurso += $recursosNecesarios[$i]->cantidad_recurso * $recursosNecesarios[$i]->costo;
                    $costo_total_alimento += ($recursosNecesarios[$i]->cant_tarde + $recursosNecesarios[$i]->cant_manana) * $recursosNecesarios[$i]->costo_kg;
                    $costo_horash += $recursosNecesarios[$i]->horas_hombre * $hh[0]->costo;
                    $horas_hombre += $recursosNecesarios[$i]->horas_hombre;
                    // $costo_horash = $recursosNecesarios[$i]->horas_hombre * $hh[0]->costo;
                    
                }
            }
            $costo_tot = $costo_horash + $costo_total_recurso + $costo_total_alimento;
            $costo_horash = number_format($costo_horash, 2, ',', '');
            $costo_total_recurso = number_format($costo_total_recurso, 2, ',', '');
            $costo_total_alimento = number_format($costo_total_alimento, 2, ',', '');
            $costo_tot = number_format($costo_tot, 2, ',', '');
            
            $aux_regs[]=["nombre_siembra"=>$exist->nombre_siembra,
            "fecha_inicio" => $exist->fecha_inicio,
            "cantidad_inicial" => $exist->cantidad_inicial,
            "peso_inicial" => $exist->peso_inicial,
            "peso_actual"=>$exist->peso_actual,
            "cant_actual" => $exist->cant_actual,
            "biomasa_disponible" => $biomasa_disponible ,
            "salida_biomasa" => $salida_biomasa, 
            "mortalidad" => $mortalidad,
            "mortalidad_kg" => $mortalidad_kg,
            "mortalidad_porcentaje" => $mortalidad_porcentaje ,
            "salida_animales"=>$salida_animales,
            "densidad_final" => $densidad_final,
            "carga_final" => $carga_final,
            "horas_hombre" => $horas_hombre,
            "costo_horash" => $costo_horash ,
            "costo_total_recurso" => $costo_total_recurso ,
            "costo_total_alimento" => $costo_total_alimento,
            "costo_tot" => $costo_tot ];
        }
        
        return ['existencias'=> $aux_regs];
        
    }
    public function filtroExistenciasDetalle(Request $request) {
        $c1 = "siembras.id"; $op1 = '!='; $c2 = '-1';
        // $c3 = "siembras.id"; $op2 = '!='; $c4 = '-1';
        $c5 = "siembras.id"; $op3 = '!='; $c6 = '-1';
        $c7 = "siembras.id"; $op4 = '!='; $c8 = '-1';
        $c9 = "siembras.id"; $op5 = '!='; $c10 = '-1';
        $c11 = "siembras.id"; $op6 = '!='; $c12 = '-1';
        
        if($request['f_siembra']!='-1'){$c1="siembras.id"; $op1='='; $c2= $request['f_siembra'];}
        // if($request['f_especie']!='-1'){$c3="especies.id"; $op2='='; $c4= $request['f_especie'];}
        if($request['f_inicio_d']!='-1'){$c5="fecha_inicio"; $op3='>='; $c6= $request['f_inicio_d'];}
        if($request['f_inicio_h']!='-1'){$c7="fecha_inicio"; $op4='<='; $c8= $request['f_inicio_h'];}
     

        $existencias = EspecieSiembra::select(
            'siembras.id as id',
            DB::raw('SUM(cant_actual) as cant_actual'),
            'contenedor',
            'capacidad',                     
            DB::raw('SUM(especies_siembra.cantidad) as cantidad_inicial'),
            'especies_siembra.id_siembra as id_siembra',          
            'fecha_inicio',            
            'nombre_siembra',                        
            DB::raw('SUM(peso_inicial) as peso_inicial'),            
            DB::raw('SUM(peso_actual) as peso_actual'),
            )
        
        ->join('siembras', 'especies_siembra.id_siembra', 'siembras.id' )
        ->join('contenedores', 'siembras.id_contenedor', 'contenedores.id' )
        ->where('siembras.estado', '=', 1)
        ->where($c1, $op1, $c2)
        ->where($c5, $op3, $c6)
        ->where($c7, $op4, $c8)
        ->orderBy('siembras.nombre_siembra')
        ->groupBy('siembras.id')
        ->groupBy('contenedor')
        ->groupBy('fecha_inicio')
        ->groupBy('nombre_siembra')        
        ->groupBy('capacidad')
        ->groupBy('especies_siembra.id_siembra')
        ->get();

        $aux_regs = array();
        $biomasa_disponible = 0;
        
        foreach($existencias as $exist ) {
            $especies_siembra = EspecieSiembra::select()
            ->join('siembras', 'especies_siembra.id_siembra', 'siembras.id' )
            ->where('siembras.id', $exist->id)
            ->get();
            $sum_bio = 0;
            if(count($especies_siembra)>0){        
                for($i=0;$i<count($especies_siembra); $i++){
                    $especies_siembra[$i]->biomasa_disponible = ((($especies_siembra[$i]->peso_actual)*($especies_siembra[$i]->cant_actual)) / 1000);
                    $sum_bio +=$especies_siembra[$i]->biomasa_disponible;
                    $biomasa_disponible = number_format($sum_bio,2,',','');
                }
            }
            
            $registros = Registro::select(
                'siembras.id',
                DB::raw('SUM(biomasa) as salida_biomasa'),
                DB::raw('SUM(mortalidad) as mortalidad'),
            )
            ->join('siembras', 'registros.id_siembra', 'siembras.id' )->where('siembras.estado','=','1')
            ->where('siembras.id','=',$exist->id)
            ->groupBy('siembras.id')
            ->first();
			
			
			$mortalidad_kg = 0;	$mortalidad_porcentaje=0;	$salida_animales=0;
			$salida_biomasa = 0; $mortalidad = 0;
			
			if(isset($registros->id)){
				$mortalidad = $registros->mortalidad;
				$mortalidad_kg =  (number_format((($registros->mortalidad * $exist->peso_actual)/1000),2, ',',''));
				$mortalidad_porcentaje =  (number_format((($registros->mortalidad * 100)/$exist->cantidad_inicial),2, ',',''));            
				$salida_animales = (number_format((($registros->salida_biomasa * 1000)/$exist->peso_actual),2, ',',''));
			}
			
            $densidad_final = (number_format(($exist->cant_actual/$exist->capacidad),2, ',',''));

            $bio_dispo = ((($exist->peso_actual)*($exist->cant_actual)) / 1000);
            $carga_final = (number_format(($bio_dispo/$exist->capacidad), 2, ',',''));
        
            $recursosNecesarios = RecursoNecesario::orderBy('fecha_ra', 'desc')
            ->join('recursos_siembras', 'recursos_necesarios.id', 'recursos_siembras.id_registro')
            ->leftJoin('alimentos', 'recursos_necesarios.id_alimento','alimentos.id')
            ->leftJoin('recursos', 'recursos_necesarios.id_recurso', 'recursos.id')
            ->join('siembras', 'recursos_siembras.id_siembra', 'siembras.id')
            ->where('recursos_siembras.id_siembra','=',$exist->id)
            ->where('estado',1)
            ->get();
            $horas_hombre = 0;
            $costo_horash  = 0;
            $costo_total_recurso = 0;
            $costo_total_alimento = 0;
			
            $hh = Recursos::select()->where('recurso','Hora hombre')->orWhere('recurso','Horas hombre')->get();
            
         
            if(count($recursosNecesarios)>0){
                for($i=0;$i<count($recursosNecesarios); $i++){   
                    $costo_total_recurso += $recursosNecesarios[$i]->cantidad_recurso * $recursosNecesarios[$i]->costo;
                    $costo_total_alimento += ($recursosNecesarios[$i]->cant_tarde + $recursosNecesarios[$i]->cant_manana) * $recursosNecesarios[$i]->costo_kg;
                    $costo_horash += $recursosNecesarios[$i]->horas_hombre * $hh[0]->costo;
                    $horas_hombre += $recursosNecesarios[$i]->horas_hombre;
                }
            }
            $costo_tot = $costo_horash + $costo_total_recurso + $costo_total_alimento;
            $costo_horash = number_format($costo_horash, 2, ',', '');
            $costo_total_recurso = number_format($costo_total_recurso, 2, ',', '');
            $costo_total_alimento = number_format($costo_total_alimento, 2, ',', '');
            $costo_tot = number_format($costo_tot, 2, ',', '');
            $ban_pasa=1;
            if(isset($request['f_biomasa_h']) &&  $sum_bio >= $request['f_biomasa_h']){
				$ban_pasa=0;
			}else{$ban_pasa=1;}
			
            if($ban_pasa == 0){
                $aux_regs[]=["nombre_siembra"=>$exist->nombre_siembra,
                "fecha_inicio" => $exist->fecha_inicio,
                "cantidad_inicial" => $exist->cantidad_inicial,
                "peso_inicial" => $exist->peso_inicial,
                "peso_actual"=>$exist->peso_actual,
                "cant_actual" => $exist->cant_actual,
                "biomasa_disponible" => $biomasa_disponible ,
                "salida_biomasa" => $salida_biomasa,
                "mortalidad" => $mortalidad,
                "mortalidad_kg" => $mortalidad_kg,
                "mortalidad_porcentaje" => $mortalidad_porcentaje ,
                "salida_animales"=>$salida_animales,
                "densidad_final" => $densidad_final,
                "carga_final" => $carga_final,
                "horas_hombre" => $horas_hombre,
                "costo_horash" => $costo_horash ,
                "costo_total_recurso" => $costo_total_recurso ,
                "costo_total_alimento" => $costo_total_alimento,
                "costo_tot" => $costo_tot ];
            }
        }
        return ['existencias'=> $aux_regs];
    }
}
