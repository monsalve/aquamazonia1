<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\EspecieSiembra;
use App\Siembra;
use App\RecursoSiembra;
use App\RecursoNecesario;
use App\Recurso;
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
        
        $recursosNecesarios = RecursoNecesario::orderBy('fecha_ra', 'desc')
        ->leftJoin('recursos', 'recursos_necesarios.id_recurso', 'recursos.id')
        ->leftJoin('alimentos', 'recursos_necesarios.id_alimento','alimentos.id')
        ->join('recursos_siembras', 'recursos_necesarios.id', 'recursos_siembras.id_registro')
        ->join('siembras', 'recursos_siembras.id_siembra', 'siembras.id')
        ->join('actividades','recursos_necesarios.tipo_actividad','actividades.id')
        ->select('recursos.id as idr', 'alimentos.id as ida', 'recursos_necesarios.id as id','actividad', 'horas_hombre', 'id_recurso', 'id_alimento', 'fecha_ra', 'horas_hombre', 'cant_manana', 'cant_tarde', 'detalles', 'tipo_actividad', 'recurso', 'alimento', 'recursos.costo as costo_r', 'alimentos.costo_kg as costo_a', 'nombre_siembra', 'estado')
        ->get();
            
        $acumula=0;
        $acumula2=0;
        $acumula3=0;
        
        if(count($recursosNecesarios)>0){
            for($i=0;$i<count($recursosNecesarios); $i++){
                
                $acumula+=$recursosNecesarios[$i]->costo_r;
                $recursosNecesarios[$i]->costo_r_acum = number_format($acumula, 2, ',', '');
                
                $recursosNecesarios[$i]->costo_total_alimento = ($recursosNecesarios[$i]->cant_tarde + $recursosNecesarios[$i]->cant_manana) * $recursosNecesarios[$i]->costo_a; 
                
                $acumula2+=$recursosNecesarios[$i]->costo_total_alimento;
                $recursosNecesarios[$i]->costo_a_acum = number_format($acumula2, 2, ',', '');
                
                $recursosNecesarios[$i]->costo_horash = $recursosNecesarios[$i]->horas_hombre*3000;
                $acumula3+=$recursosNecesarios[$i]->costo_horash;
                $recursosNecesarios[$i]->costo_h_acum = number_format($acumula3, 2, ',', '');
            }
        }
        $recursosSiembras = RecursoSiembra::select('recursos_siembras.id as id', 'id_registro', 'id_siembra', 'id_recurso', 'id_alimento', 'fecha_ra', 'horas_hombre', 'cant_manana', 'cant_tarde', 'detalles', 'tipo_actividad', 'recursos_necesarios.id as idrn', 'nombre_siembra', 'alimento', 'recurso', 'estado')
        ->join('recursos_necesarios', 'recursos_siembras.id_registro', 'recursos_necesarios.id')
        ->join('siembras', 'recursos_siembras.id_siembra', 'siembras.id')
        ->join('recursos', 'recursos_necesarios.id_recurso', 'recursos.id')
        ->join('alimentos', 'recursos_necesarios.id_alimento','alimentos.id')
        ->get();
        
        return ['recursosNecesarios' => $recursosNecesarios, 'recursosSiembras' => $recursosSiembras];
    }
    public function informeRecursos(Request $request)
    {

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
        ->select('recursos.id as idr', 'alimentos.id as ida', 'recursos_necesarios.id as id','actividad', 'horas_hombre', 'id_recurso', 'id_alimento', 'fecha_ra', 'horas_hombre', 'cant_manana', 'cant_tarde', 'detalles', 'tipo_actividad', 'recurso', 'alimento', 'recursos.costo as costo_r', 'alimentos.costo_kg as costo_a', 'nombre_siembra', 'siembras.estado as estado')
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
                
                $acumula+=$recursosNecesarios[$i]->costo_r;
                $recursosNecesarios[$i]->costo_r_acum = $acumula;
                
                $recursosNecesarios[$i]->costo_total_alimento = ($recursosNecesarios[$i]->cant_tarde + $recursosNecesarios[$i]->cant_manana) * $recursosNecesarios[$i]->costo_a; 
                
                $acumula2+=$recursosNecesarios[$i]->costo_total_alimento;
                $recursosNecesarios[$i]->costo_a_acum = $acumula2;
                
                $recursosNecesarios[$i]->costo_horash = $recursosNecesarios[$i]->horas_hombre*3000;
                $acumula3+=$recursosNecesarios[$i]->costo_horash;
                $recursosNecesarios[$i]->costo_h_acum = $acumula3;
            }
        }
       
        return ['recursosNecesarios' => $recursosNecesarios ];
        // return redirect()->route('informe-excel',  ['recursosNecesarios' => $recursosNecesarios ]);        
    }
    public function traerInformes(){

        $recursosNecesarios = RecursoNecesario::orderBy('fecha_ra', 'desc')
        ->join('recursos', 'recursos_necesarios.id_recurso', 'recursos.id')
        ->join('alimentos', 'recursos_necesarios.id_alimento','alimentos.id')
        ->join('recursos_siembras', 'recursos_necesarios.id', 'recursos_siembras.id_registro')
        ->join('siembras', 'recursos_siembras.id_siembra', 'siembras.id')
        ->join('actividades','recursos_necesarios.tipo_actividad','actividades.id')
        ->select('recursos_necesarios.id as id', 'horas_hombre', 'id_recurso', 'id_alimento','actividad', 'fecha_ra', 'cant_manana', 'cant_tarde', 'detalles', 'tipo_actividad', 'recurso', 'alimento', 'recursos.costo as costo_r', 'alimentos.costo_kg as costo_a', 'nombre_siembra', 'estado')
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
                
                $recursosNecesarios[$i]->costo_horash = $recursosNecesarios[$i]->horas_hombre*3000;
                $acumula3+=$recursosNecesarios[$i]->costo_horash;
                $recursosNecesarios[$i]->costo_h_acum = $acumula3;
            }
        }
        return ['recursosNecesarios' => $recursosNecesarios];
    }
    public function filtroInformes(Request $request){
    
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
        ->select('recursos.id as idr', 'alimentos.id as ida', 'recursos_necesarios.id as id', 'actividad','horas_hombre', 'id_recurso', 'id_alimento', 'fecha_ra', 'horas_hombre', 'cant_manana', 'cant_tarde', 'detalles', 'tipo_actividad', 'recurso', 'alimento', 'recursos.costo as costo_r', 'alimentos.costo_kg as costo_a', 'nombre_siembra', 'estado')
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
                
                $acumula+=$recursosNecesarios[$i]->costo_r;
                $recursosNecesarios[$i]->costo_r_acum = $acumula;
                
                $recursosNecesarios[$i]->costo_total_alimento = ($recursosNecesarios[$i]->cant_tarde + $recursosNecesarios[$i]->cant_manana) * $recursosNecesarios[$i]->costo_a; 
                
                $acumula2+=$recursosNecesarios[$i]->costo_total_alimento;
                $recursosNecesarios[$i]->costo_a_acum = $acumula2;
                
                $recursosNecesarios[$i]->costo_horash = $recursosNecesarios[$i]->horas_hombre*3000;
                $acumula3+=$recursosNecesarios[$i]->costo_horash;
                $recursosNecesarios[$i]->costo_h_acum = $acumula3;
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
}
