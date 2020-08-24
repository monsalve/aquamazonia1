<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\EspecieSiembra;
use App\Siembra;
use App\Contenedor;
use App\Registro;
use Illuminate\Support\Facades\DB;


class InformeSiembraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $siembras = Siembra::select('siembras.id as id', 'nombre_siembra', 'id_contenedor','contenedor','fecha_inicio', 'ini_descanso', 'fin_descanso','siembras.estado as estado', 'fecha_alimento', 'lote', 'cant_actual', 'especie', 'peso_actual', 'especies.id as id_esp')
            ->join('especies_siembra', 'siembras.id', 'especies_siembra.id_siembra')
            ->join('especies', 'especies_siembra.id_especie', 'especies.id')
            ->join('contenedores','siembras.id_contenedor','contenedores.id')            
            ->where('siembras.estado','=',1)
            ->orderBy('siembras.id', 'desc')
            ->get();
        $mortalidad_siembra = array();
        foreach($siembras as $s) {
            $aux_mortalidad = Registro::select( DB::raw('SUM(mortalidad) as mortalidad'),'id_especie')
                        ->where('id_siembra','=',$s->id)
                        ->where('estado','=','1')
                        ->where('tipo_registro','!=','1')
                        ->groupBy('id_especie')
                        ->get();
            foreach($aux_mortalidad as $a_mort) {
                $mortalidad_siembra[$s->id][$a_mort->id_especie] =  $a_mort->mortalidad;
            }           
            
        }
              
        $fecha_actual = date('Y-m-d');
        $lotes = EspecieSiembra::select('lote')->distinct()->get();
        
        return [
            "siembras"=> $siembras,        
            'lotes' => $lotes,
            'fecha_actual'=> $fecha_actual,
            'mortalidad_siembra'=> $mortalidad_siembra
        ];
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
    public function filtroSiembras(Request $request){
      $c1 = "siembras.id"; $op1 = '!='; $c2 = '-1';
      $c3 = "siembras.id"; $op2 = '!='; $c4 = '-1';
      $c5 = "siembras.id"; $op3 = '!='; $c6 = '-1';
      $c7 = "siembras.id"; $op4 = '!='; $c8 = '-1';
      $c9 = "siembras.id"; $op5 = '!='; $c10 = '-1';
      $c11 = "siembras.id"; $op6 = '!='; $c12 = '-1';
      
      if($request['f_siembra']!='-1'){$c1="siembras.id"; $op1='='; $c2= $request['f_siembra'];}
      if($request['f_especie']!='-1'){$c3="especies.id"; $op2='='; $c4= $request['f_especie'];}
      if($request['f_lote']!='-1'){$c5="lote"; $op3='='; $c6= $request['f_lote'];}
      if($request['f_inicio_d']!='-1'){$c7="fecha_inicio"; $op4='>='; $c8= $request['f_inicio_d'];}
      if($request['f_inicio_h']!='-1'){$c9="fecha_inicio"; $op5='<='; $c10= $request['f_inicio_h'];}
      if($request['f_estado_s']!='-1'){$c11="siembras.estado"; $op6='='; $c12= $request['f_estado_s'];}
  
      $siembras = Siembra::select('siembras.id as id', 'nombre_siembra', 'id_contenedor','contenedor','fecha_inicio', 'ini_descanso', 'fin_descanso','siembras.estado as estado', 'lote', 'especies.id', 'especie', 'cantidad', 'peso_inicial', 'cant_actual', 'peso_actual', 'fecha_alimento')
          ->join('contenedores','siembras.id_contenedor','contenedores.id')
          ->join('especies_siembra', 'siembras.id', 'especies_siembra.id_siembra') 
          ->join('especies', 'especies_siembra.id_especie', 'especies.id')
          ->where($c1, $op1, $c2)
          ->where($c3, $op2, $c4)
          ->where($c5, $op3, $c6)
          ->where($c7, $op4, $c8)
          ->where($c9, $op5, $c10)
          ->where($c11, $op6, $c12)
          ->orderBy('siembras.id', 'desc')
          ->get();
          
      return ['siembras' => $siembras];
    }   
}

