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
        $horas_hombre = Recursos::select()->where('recurso','Hora hombre')->orWhere('recurso','Horas hombre')->get();
        
        $recursosNecesarios = RecursoNecesario::orderBy('fecha_ra', 'desc')
        ->join('recursos_siembras', 'recursos_necesarios.id', 'recursos_siembras.id_registro')
        ->leftJoin('recursos', 'recursos_necesarios.id_recurso','recursos.id')
        ->join('siembras', 'recursos_siembras.id_siembra', 'siembras.id')
        ->join('actividades','recursos_necesarios.tipo_actividad','actividades.id')
        ->where('tipo_actividad', '!=', '1')
        ->where('estado',1)
        ->get();
        if(count($recursosNecesarios)>0){
            for($i=0;$i<count($recursosNecesarios); $i++){     
                $recursosNecesarios[$i]->costo_total_recurso = $recursosNecesarios[$i]->cantidad_recurso * $recursosNecesarios[$i]->costo;
                $recursosNecesarios[$i]->total_horas_hombre = $recursosNecesarios[$i]->horas_hombre * $horas_hombre[0]->costo;
            }
        }
        // print_r('hola'.$horas_hombre);
        return ['recursosNecesarios' => $recursosNecesarios];
    }
    public function  alimentacion()
    {
        //
        $horas_hombre = Recursos::select()->where('recurso','Hora hombre')->orWhere('recurso','Horas hombre')->get();
        $recursosNecesarios = RecursoNecesario::orderBy('fecha_ra', 'desc')
        ->join('recursos_siembras', 'recursos_necesarios.id', 'recursos_siembras.id_registro')
        ->join('alimentos', 'recursos_necesarios.id_alimento','alimentos.id')
        ->join('siembras', 'recursos_siembras.id_siembra', 'siembras.id')
        ->join('actividades','recursos_necesarios.tipo_actividad','actividades.id')
        ->where('tipo_actividad', '=', '1')
        ->where('estado',1)
        ->get();
        
        if(count($recursosNecesarios)>0){
            for($i=0;$i<count($recursosNecesarios); $i++){        
                $recursosNecesarios[$i]->costo_total_alimento = ($recursosNecesarios[$i]->cant_tarde + $recursosNecesarios[$i]->cant_manana) * $recursosNecesarios[$i]->costo_kg;
                $recursosNecesarios[$i]->total_horas_hombre = $recursosNecesarios[$i]->horas_hombre * $horas_hombre[0]->costo;
            }
        }
    
        return ['recursosNecesarios' => $recursosNecesarios];
    }
    public function siembraxAlimentacion($id)
    {
        //
        $recursosNecesarios = RecursoNecesario::orderBy('fecha_ra', 'desc')
        ->join('recursos_siembras', 'recursos_necesarios.id', 'recursos_siembras.id_registro')
        ->join('alimentos', 'recursos_necesarios.id_alimento','alimentos.id')
        ->join('siembras', 'recursos_siembras.id_siembra', 'siembras.id')
        ->join('actividades','recursos_necesarios.tipo_actividad','actividades.id')
        ->where('id_siembra', '=', $id)
        ->where('tipo_actividad', '=', '1')
        ->get();
        
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
        $recursoNecesario = new RecursoNecesario();
        $recursoNecesario->id_recurso = $request['id_recurso'];
        $recursoNecesario->id_alimento =  $request['id_alimento'];
        $recursoNecesario->tipo_actividad = $request['tipo_actividad'];
        $recursoNecesario->fecha_ra = $request['fecha_ra'];
        $recursoNecesario->horas_hombre = $request['horas_hombre'];
        $recursoNecesario->cantidad_recurso = $request['cantidad_recurso'];
        $recursoNecesario->cant_manana = $request['cant_manana'];
        $recursoNecesario->cant_tarde = $request['cant_tarde'];
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
    
        $horas_hombre = Recursos::select()->where('recurso','Hora hombre')->orWhere('recurso','Horas hombre')->get();
        
        $c1 = "recursos_necesarios.id"; $op1="!="; $c2 = "-1";
        $c3 = "recursos_necesarios.id"; $op2="!=";  $c4="-3";
        $c5 = "recursos_necesarios.id"; $op3="!=";  $c6="-1";
        $c7 = "recursos_necesarios.id"; $op4="!=";  $c8="-1";
        $c9 = "recursos_necesarios.id"; $op5="!=";  $c10="-1";
        $c11 = 'recursos_necesarios.id'; $op6 = '!='; $c12 = '-1';
        $c13 = 'recursos_necesarios.id'; $op7 = '!='; $c14 = '-1';
        
        if($request['tipo_actividad']!='-1'){$c1="tipo_actividad"; $op1='='; $c2= $request['tipo_actividad'];}
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
        
        if(count($recursosNecesarios)>0){
            for($i=0;$i<count($recursosNecesarios); $i++){        
                $recursosNecesarios[$i]->costo_total_alimento = ($recursosNecesarios[$i]->cant_tarde + $recursosNecesarios[$i]->cant_manana) * $recursosNecesarios[$i]->costo_kg;
                $recursosNecesarios[$i]->total_horas_hombre = $recursosNecesarios[$i]->horas_hombre * $horas_hombre[0]->costo;
            }
        }
        $recursosSiembra = RecursoSiembra::select('recursos_siembras.id as id', 'id_registro', 'id_siembra', 'id_recurso', 'id_alimento', 'fecha_ra', 'horas_hombre', 'cant_manana', 'cant_tarde', 'detalles', 'tipo_actividad', 'recursos_necesarios.id as idrn', 'nombre_siembra', 'alimento', 'recurso')
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
                'horas_hombre' => $rs['horas_hombre'],
                'cant_manana' => $rs['cant_manana'],
                'cant_tarde'=>$rs['cant_tarde'],
                'detalles' => $rs['detalles'],
                'tipo_actividad'=> $rs['tipo_actividad'],
                'idrn' => $rs['idrn']
            );
        }
        
        return ['recursosNecesarios' => $recursosNecesarios, 'recursosSiembra' => $recursosSiembra, 'registrosxSiembra' => $registrosxSiembra];
   
    }
}
