<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\RecursoNecesario;
use App\RecursoSiembra;
use App\Alimento;
use App\Recursos;
use App\Siembra;
use App\Actividad;


class RecursoNecesarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $minutos_hombre = Recursos::select()->where('recurso','Minutos hombre')->orWhere('recurso','Minuto hombre')->orWhere('recurso','Minutos')->first();
        
        $recursosNecesarios = RecursoNecesario::orderBy('fecha_ra', 'desc')
        ->join('recursos_siembras', 'recursos_necesarios.id', 'recursos_siembras.id_registro')
        ->leftJoin('recursos', 'recursos_necesarios.id_recurso','recursos.id')
        ->join('siembras', 'recursos_siembras.id_siembra', 'siembras.id')
        ->join('actividades','recursos_necesarios.tipo_actividad','actividades.id')
        ->where('tipo_actividad', '!=', '1')
        ->where('estado',1)
        ->get();
        
        
        $promedioRecursos = array();        
        $summh = 0;
        $sumtmh =0;
        $sumcr =0;
        $sumc=0;
        $sumctr=0;
        
        if(count($recursosNecesarios)>0){
            for($i=0;$i<count($recursosNecesarios); $i++){     
                $recursosNecesarios[$i]->costo_total_recurso = $recursosNecesarios[$i]->cantidad_recurso * $recursosNecesarios[$i]->costo;
                $recursosNecesarios[$i]->total_minutos_hombre = $recursosNecesarios[$i]->minutos_hombre * $minutos_hombre->costo;
                $summh += $recursosNecesarios[$i]->minutos_hombre;  
                $sumtmh += $recursosNecesarios[$i]->total_minutos_hombre;  
                $sumcr += $recursosNecesarios[$i]->cantidad_recurso;
                $sumc += $recursosNecesarios[$i]->costo;
                $sumctr += $recursosNecesarios[$i]->costo_total_recurso;
            }
            $promedioRecursos['tmh'] = $summh;   
            $promedioRecursos['ttmh'] = $sumtmh;   
            $promedioRecursos['tcr'] = $sumcr;
            $promedioRecursos['tc'] = $sumc;
            $promedioRecursos['ctr'] = $sumctr;
        }
        
        return ['recursosNecesarios' => $recursosNecesarios, 'promedioRecursos' => $promedioRecursos];
    }
    public function  alimentacion()
    {
        //
        $minutos_hombre = Recursos::select()->where('recurso','Minutos hombre')->orWhere('recurso','Minuto hombre')->orWhere('recurso','Minutos')->first();
        $recursosNecesarios = RecursoNecesario::orderBy('fecha_ra', 'desc')
        ->join('recursos_siembras', 'recursos_necesarios.id', 'recursos_siembras.id_registro')
        ->join('alimentos', 'recursos_necesarios.id_alimento','alimentos.id')
        ->join('siembras', 'recursos_siembras.id_siembra', 'siembras.id')
        ->join('actividades','recursos_necesarios.tipo_actividad','actividades.id')
        ->where('tipo_actividad', '=', '1')
        ->where('estado',1)
        ->get();
        
        $promedioRecursos = array();        
        $summh = 0;       
        $cantm=0;
        $cantt=0;
        $alid=0;
        $coskg=0;
        $cta=0;
        $icb=0;
        
        if(count($recursosNecesarios)>0){
            for($i=0;$i<count($recursosNecesarios); $i++){        
                $recursosNecesarios[$i]->costo_total_alimento = ($recursosNecesarios[$i]->cant_tarde + $recursosNecesarios[$i]->cant_manana) * $recursosNecesarios[$i]->costo_kg;
                $recursosNecesarios[$i]->total_minutos_hombre = $recursosNecesarios[$i]->minutos_hombre * $minutos_hombre->costo;
                $recursosNecesarios[$i]->alimento_dia = $recursosNecesarios[$i]->cant_tarde + $recursosNecesarios[$i]->cant_manana;
                if($recursosNecesarios[$i]->conv_alimenticia > 0){
                    $recursosNecesarios[$i]->incr_bio_acum_conver = $recursosNecesarios[$i]->alimento_dia / $recursosNecesarios[$i]->conv_alimenticia;                                        
                    $recursosNecesarios[$i]->conv_alimenticia = number_format($recursosNecesarios[$i]->conv_alimenticia,2,',','');                   
                }
                $summh += $recursosNecesarios[$i]->minutos_hombre;  
                $cantm+=$recursosNecesarios[$i]->cant_manana;
                $cantt+=$recursosNecesarios[$i]->cant_tarde;
                $alid+=$recursosNecesarios[$i]->alimento_dia;
                $coskg+=$recursosNecesarios[$i]->costo_kg;
                $cta+=$recursosNecesarios[$i]->costo_total_alimento;
                $icb+=$recursosNecesarios[$i]->incr_bio_acum_conver;               
                $recursosNecesarios[$i]->incr_bio_acum_conver = number_format($recursosNecesarios[$i]->incr_bio_acum_conver,2,',','');                
            }
            $promedioRecursos['tmh'] = $summh;                           
            $promedioRecursos['cman'] = $cantm;
            $promedioRecursos['ctar'] = $cantt;
            $promedioRecursos['alid'] = $alid;
            $promedioRecursos['coskg'] = $coskg;
            $promedioRecursos['cta'] = $cta;
            $promedioRecursos['icb'] = $icb;
            $icb = number_format($icb,2,',','');
        }
        // print_r($recursosNecesarios);
    
        return ['recursosNecesarios' => $recursosNecesarios,'promedioRecursos'=>$promedioRecursos ];
    }
    public function siembraxAlimentacion($id)
    {
        //
        $minutos_hombre = Recursos::select()->where('recurso','Minutos hombre')->orWhere('recurso','Minuto hombre')->orWhere('recurso','Minutos')->first();
        $recursosNecesarios = RecursoNecesario::orderBy('fecha_ra', 'desc')
        ->join('recursos_siembras', 'recursos_necesarios.id', 'recursos_siembras.id_registro')
        ->join('alimentos', 'recursos_necesarios.id_alimento','alimentos.id')
        ->join('siembras', 'recursos_siembras.id_siembra', 'siembras.id')
        ->join('actividades','recursos_necesarios.tipo_actividad','actividades.id')
        ->where('id_siembra', '=', $id)
        ->where('tipo_actividad', '=', '1')
        ->get();
        if(count($recursosNecesarios)>0){
            for($i=0;$i<count($recursosNecesarios); $i++){        
                $recursosNecesarios[$i]->costo_total_alimento = ($recursosNecesarios[$i]->cant_tarde + $recursosNecesarios[$i]->cant_manana) * $recursosNecesarios[$i]->costo_kg;
                $recursosNecesarios[$i]->total_minutos_hombre = $recursosNecesarios[$i]->minutos_hombre * $minutos_hombre->costo;
                $recursosNecesarios[$i]->alimento_dia = $recursosNecesarios[$i]->cant_tarde + $recursosNecesarios[$i]->cant_manana;
            }
        }
        
        return ['recursosNecesarios' => $recursosNecesarios];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $c_alim = RecursoNecesario::select()->orderBy('id', 'desc')->first();
        // print_r($c_alim->conv_alimenticia);
        
        $recursoNecesario = new RecursoNecesario();
        $recursoNecesario->id_recurso = $request['id_recurso'];
        $recursoNecesario->id_alimento =  $request['id_alimento'];
        $recursoNecesario->tipo_actividad = $request['tipo_actividad'];
        $recursoNecesario->fecha_ra = $request['fecha_ra'];
        $recursoNecesario->minutos_hombre = $request['minutos_hombre'];
        $recursoNecesario->horas_hombre = ($request['minutos_hombre']/60);
        $recursoNecesario->cantidad_recurso = $request['cantidad_recurso'];
        $recursoNecesario->cant_manana = $request['cant_manana'];
        $recursoNecesario->cant_tarde = $request['cant_tarde'];
        if($request['conv_alimenticia'] == ''){
            $recursoNecesario->conv_alimenticia = $c_alim->conv_alimenticia;
        }else{
            $recursoNecesario->conv_alimenticia = $request['conv_alimenticia'];
        }
        $recursoNecesario->detalles = $request['detalles'];
        $recursoNecesario->save();
        
        if($request['tipo_actividad'] == '1'){
            $siembras = Siembra::findOrFail($request['id_siembra']);
            $siembras->fecha_alimento = $request['fecha_ra'];
            $siembras->save();
            
            $recursoSiembra = new RecursoSiembra();
            $recursoSiembra->id_registro = $recursoNecesario->id;
            $recursoSiembra->id_siembra =$request['id_siembra'];            
            $recursoSiembra->save();      
        }else{      
           foreach ($request->id_siembra as $siembra){
           
                $recursoSiembra = new RecursoSiembra();
                $recursoSiembra->id_registro = $recursoNecesario->id;
                $recursoSiembra->id_siembra = $siembra;            
                $recursoSiembra->save();            
            }
        }
            
       return ($request);
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
        //
        $recursoNecesario = RecursoNecesario::where('id', $request['id_registro'])->firstOrFail()
        ->update([
            'cant_manana' => $request['cant_manana'],
            'cant_tarde'=> $request['cant_tarde'],
            'id_alimento' => $request['id_alimento'],
        ]);
        
        return ['recursoNecesario' => $recursoNecesario];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        RecursoNecesario::destroy($id);
        $rxs = RecursoSiembra::where('id_registro', $id)->delete();
        return 'eliminado';
        
    }
    public function searchResults(Request $request){
    
        $minutos_hombre = Recursos::select()->where('recurso','Minutos hombre')->orWhere('recurso','Minuto hombre')->orWhere('recurso','Minutos')->first();
        
        $c1 = "recursos_necesarios.id"; $op1="!="; $c2 = "-1";
        $c3 = "recursos_necesarios.id"; $op2="!=";  $c4="-3";
        $c5 = "recursos_necesarios.id"; $op3="!=";  $c6="-1";
        $c7 = "recursos_necesarios.id"; $op4="!=";  $c8="-1";
        $c9 = "recursos_necesarios.id"; $op5="!=";  $c10="-1";
        $c11 = 'recursos_necesarios.id'; $op6 = '!='; $c12 = '-1';
        $c13 = 'recursos_necesarios.id'; $op7 = '!='; $c14 = '-1';
        
        if($request['tipo_actividad']!='-1'){
            $c1="tipo_actividad"; $op1='='; $c2= $request['tipo_actividad'];
        }
        elseif ($request['tipo_actividad']=='-1') {
            $c1="tipo_actividad"; $op1='!='; $c2= '1';
        } 
            
        if($request['fecha_ra1']!='-3'){$c3="fecha_ra"; $op2='>='; $c4=$request['fecha_ra1'];}
        if($request['fecha_ra2']!='-1'){$c5="fecha_ra"; $op3='<='; $c6=$request['fecha_ra2'];}
        if($request['f_siembra']!='-1'){$c7="siembras.id"; $op4='='; $c8= $request['f_siembra'];}
        if(isset($request['alimento_s']) && $request['alimento_s']!='-1'){$c9="id_alimento"; $op5='='; $c10= $request['alimento_s'];}
        if(isset($request['recurso_s']) &&  ($request['recurso_s']!='-1')){$c11="id_recurso"; $op6='='; $c12= $request['recurso_s'];}
        if($request['f_siembra']!='-1'){$c13="siembras.id"; $op7='='; $c14= $request['f_siembra'];}
        
        $recursosNecesarios = RecursoNecesario::orderBy('fecha_ra', 'desc')
        ->join('recursos_siembras', 'recursos_necesarios.id', 'recursos_siembras.id_registro')
        ->rightJoin('siembras', 'recursos_siembras.id_siembra', 'siembras.id')
        ->leftJoin('alimentos', 'recursos_necesarios.id_alimento','alimentos.id')    
        ->leftJoin('recursos', 'recursos_necesarios.id_recurso','recursos.id')
        ->join('actividades','recursos_necesarios.tipo_actividad','actividades.id')
        ->where($c1, $op1, $c2)
        ->where($c3, $op2, $c4)
        ->where($c5, $op3, $c6)
        ->where($c7, $op4, $c8)
        ->where($c9, $op5, $c10)
        ->where($c11, $op6, $c12)
        ->where($c13, $op7, $c14)
        ->get();
        
        $promedioRecursos = array();        
        $summh = 0;
        $sumtmh =0;
        $sumcr =0;
        $sumc=0;
        $sumctr=0;
        $cantm=0;
        $cantt=0;
        $alid=0;
        $coskg=0;
        $cta=0;
        $icb=0;
        
        if(count($recursosNecesarios)>0){
            for($i=0;$i<count($recursosNecesarios); $i++){        
               
                $recursosNecesarios[$i]->costo_total_recurso = $recursosNecesarios[$i]->cantidad_recurso * $recursosNecesarios[$i]->costo;
                $recursosNecesarios[$i]->total_minutos_hombre = $recursosNecesarios[$i]->minutos_hombre * $minutos_hombre->costo;
                
                $recursosNecesarios[$i]->costo_total_alimento = ($recursosNecesarios[$i]->cant_tarde + $recursosNecesarios[$i]->cant_manana) * $recursosNecesarios[$i]->costo_kg;               
                $recursosNecesarios[$i]->alimento_dia = $recursosNecesarios[$i]->cant_tarde + $recursosNecesarios[$i]->cant_manana;
                if($recursosNecesarios[$i]->conv_alimenticia > 0){
                    $recursosNecesarios[$i]->incr_bio_acum_conver = $recursosNecesarios[$i]->alimento_dia / $recursosNecesarios[$i]->conv_alimenticia;                                        
                    $recursosNecesarios[$i]->conv_alimenticia = number_format($recursosNecesarios[$i]->conv_alimenticia,2,',','');                    
                }                
                
                $summh += $recursosNecesarios[$i]->minutos_hombre;  
                $sumtmh += $recursosNecesarios[$i]->total_minutos_hombre;  
                $sumcr += $recursosNecesarios[$i]->cantidad_recurso;
                $sumc += $recursosNecesarios[$i]->costo;
                $sumctr += $recursosNecesarios[$i]->costo_total_recurso;
                $cantm+=$recursosNecesarios[$i]->cant_manana;
                $cantt+=$recursosNecesarios[$i]->cant_tarde;
                $alid+=$recursosNecesarios[$i]->alimento_dia;
                $coskg+=$recursosNecesarios[$i]->costo_kg;
                $cta+=$recursosNecesarios[$i]->costo_total_alimento;
                $icb+=$recursosNecesarios[$i]->incr_bio_acum_conver;                
                $recursosNecesarios[$i]->incr_bio_acum_conver = number_format($recursosNecesarios[$i]->incr_bio_acum_conver,2,',','');
                
            }
            $promedioRecursos['tmh'] = $summh;   
            $promedioRecursos['ttmh'] = $sumtmh;   
            $promedioRecursos['tcr'] = $sumcr;
            $promedioRecursos['tc'] = $sumc;
            $promedioRecursos['ctr'] = $sumctr;
            $promedioRecursos['cman'] = $cantm;
            $promedioRecursos['ctar'] = $cantt;
            $promedioRecursos['alid'] = $alid;
            $promedioRecursos['coskg'] = $coskg;
            $promedioRecursos['cta'] = $cta;
            $promedioRecursos['icb'] = $icb;
            $icb = number_format($icb,2,',','');
        }
        $recursosSiembra = RecursoSiembra::select('recursos_siembras.id as id', 'id_registro', 'id_siembra', 'id_recurso', 'id_alimento', 'fecha_ra','minutos_hombre', 'cant_manana', 'cant_tarde', 'detalles', 'tipo_actividad', 'recursos_necesarios.id as idrn', 'nombre_siembra', 'alimento', 'recurso')
        ->join('recursos_necesarios', 'recursos_siembras.id_registro', 'recursos_necesarios.id')
        ->join('siembras', 'recursos_siembras.id_siembra', 'siembras.id')
        ->join('recursos', 'recursos_necesarios.id_recurso', 'recursos.id')
        ->join('alimentos', 'recursos_necesarios.id_alimento','alimentos.id')        
        ->get();
        
        $registrosxSiembra=array();
        
        foreach($recursosSiembra as $rs){
            $registrosxSiembra[$rs['id_registro']][$rs['id']] = array(
                'id_registro' => $rs['id_registro'],
                'id_siembra' => $rs['id_siembra'],
                'id_recurso' => $rs['id_recurso'],
                'id_alimento' => $rs['id_alimento'],
                'fecha_ra'=>$rs['fecha_ra'],
                'minutos_hombre' => $rs['minutos_hombre'],
                'cant_manana' => $rs['cant_manana'],
                'cant_tarde'=>$rs['cant_tarde'],
                'detalles' => $rs['detalles'],
                'tipo_actividad'=> $rs['tipo_actividad'],
                'idrn' => $rs['idrn']
            );
        }
        
        return ['recursosNecesarios' => $recursosNecesarios, 'promedioRecursos'=>$promedioRecursos];
   
    }
}
