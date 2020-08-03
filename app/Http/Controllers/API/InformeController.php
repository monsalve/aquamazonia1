<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Siembra;
use App\RecursoSiembra;
use App\RecursoNecesario;
use App\Recurso;

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
        ->join('recursos', 'recursos_necesarios.id_recurso', 'recursos.id')
        ->join('alimentos', 'recursos_necesarios.id_alimento','alimentos.id')
        ->select('recursos.id as idr', 'alimentos.id as ida', 'recursos_necesarios.id as id', 'horas_hombre', 'id_recurso', 'id_alimento', 'fecha_ra', 'horas_hombre', 'cant_manana', 'cant_tarde', 'detalles', 'tipo_actividad', 'recurso', 'alimento', 'recursos.costo as costo_r', 'alimentos.costo_kg as costo_a')
        ->get();
            
        $acumula=0;
        $acumula2=0;
        $acumula3=0;
        
        if(count($recursosNecesarios)>0){
            for($i=0;$i<count($recursosNecesarios); $i++){
                $acumula+=$recursosNecesarios[$i]->costo_r;
                $recursosNecesarios[$i]->costo_r_acum = $acumula;
                $acumula2+=$recursosNecesarios[$i]->costo_a;
                $recursosNecesarios[$i]->costo_a_acum = $acumula2;
                $recursosNecesarios[$i]->costo_horash = $recursosNecesarios[$i]->horas_hombre*3000;
                $acumula3+=$recursosNecesarios[$i]->costo_horash;
                $recursosNecesarios[$i]->costo_h_acum = $acumula3;
                
            }
        }
        $recursosSiembras = RecursoSiembra::select('recursos_siembras.id as id', 'id_registro', 'id_siembra', 'id_recurso', 'id_alimento', 'fecha_ra', 'horas_hombre', 'cant_manana', 'cant_tarde', 'detalles', 'tipo_actividad', 'recursos_necesarios.id as idrn', 'nombre_siembra', 'alimento', 'recurso', 'estado')
        ->join('recursos_necesarios', 'recursos_siembras.id_registro', 'recursos_necesarios.id')
        ->join('siembras', 'recursos_siembras.id_siembra', 'siembras.id')
        ->join('recursos', 'recursos_necesarios.id_recurso', 'recursos.id')
        ->join('alimentos', 'recursos_necesarios.id_alimento','alimentos.id')
        ->get();
        
        return ['recursosNecesarios' => $recursosNecesarios, 'recursosSiembras' => $recursosSiembras];
        // return ['siembras'=>$siembras, 'recursosSiembrass'=>$recursosSiembras, 'recursosNecesarios' => $recursosNecesarios,'especies'=>$especies];
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
    }
    
    public function filtroInformes(Request $request){
    
        $c1 = 'tipo_actividad'; $op1 = '!='; $c2 = '-1';
        $c3 = 'tipo_actividad'; $op2 = '!='; $c4 = '-1';
        $c5 = 'tipo_actividad'; $op3 = '!='; $c6 = '-1';
        $c7 = 'tipo_actividad'; $op4 = '!='; $c8 = '-1';
        $c9 = 'tipo_actividad'; $op5 = '!='; $c10 = '-1';
        $c11 = 'tipo_actividad'; $op6 = '!='; $c12 = '-1';
        
        if($request['estado_s']!='-1'){$c1="estado"; $op1='='; $c2= $request['estado_s'];}
        if($request['actividad_s']!='-1'){$c3="tipo_actividad"; $op2='='; $c4= $request['actividad_s'];}
        if($request['alimento_s']!='-1'){$c5="id_alimento"; $op3='='; $c6= $request['alimento_s'];}
        if($request['recurso_s']!='-1'){$c7="id_recurso"; $op4='='; $c8= $request['recurso_s'];}
        if($request['fecha_ra1']!='-1'){$c9="fecha_ra"; $op5='>='; $c10=$request['fecha_ra1'];}
        if($request['fecha_ra2']!='-1'){$c11="fecha_ra"; $op6='<='; $c12=$request['fecha_ra2'];}        
    
        $recursosNecesarios = RecursoNecesario::orderBy('fecha_ra', 'desc')
        ->rightJoin('recursos', 'recursos_necesarios.id_recurso', 'recursos.id')
        ->rightJoin('alimentos', 'recursos_necesarios.id_alimento','alimentos.id')
        ->select('recursos_necesarios.id as id', 'horas_hombre', 'id_recurso', 'id_alimento', 'fecha_ra', 'horas_hombre', 'cant_manana', 'cant_tarde', 'detalles', 'tipo_actividad', 'recurso', 'alimento', 'recursos.costo as costo_r', 'alimentos.costo_kg as costo_a')
        ->where($c3, $op2, $c4)
        ->where($c5, $op3, $c6)
        ->where($c7, $op4, $c8)
        ->where($c9, $op5, $c10)
        ->where($c11, $op6, $c12)
        ->get();
        
        $acumula=0;
        $acumula2=0;
        $acumula3=0;
        
        if(count($recursosNecesarios)>0){
            for($i=0;$i<count($recursosNecesarios); $i++){
                $acumula+=$recursosNecesarios[$i]->costo_r;
                $recursosNecesarios[$i]->costo_r_acum = $acumula;
                $acumula2+=$recursosNecesarios[$i]->costo_a;
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
}
