<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Registro;
use App\EspecieSiembra;

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
					'lote'
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
						for($i=0;$i<count($registros); $i++){
								if($registros[$i]->tipo_registro == 0)$registros[$i]->nombre_registro = 'Muestreo';
								if($registros[$i]->tipo_registro == 1)$registros[$i]->nombre_registro = 'Pesca';        
								if($registros[$i]->tipo_registro == 2)$registros[$i]->nombre_registro = 'Mortalidad Inicial';        
								
						}
				}
            
        return $registros;
        
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
        $c3 = 'registros.id'; $op2 = '!='; $c4 = '-1';
        $c5 = 'registros.id'; $op3 = '!='; $c6 = '-1';
        $c7 = 'registros.id'; $op4 = '!='; $c8 = '-1';
        $c9 = 'registros.id'; $op5 = '!='; $c10 = '-1';
        $c11 = "registros.peso_ganado"; $op6 = '>='; $c12 = '0';
        $c13 = "registros.peso_ganado"; $op7 = '>='; $c14 = '0';
				$c15 = 'lote'; $op8 = '!='; $c16 = '-1';
        
        if($request['f_siembra']!='-1'){$c1="registros.id_siembra"; $op1='='; $c2= $request['f_siembra'];}
        if($request['f_actividad']!='-1'){$c3="tipo_registro"; $op2='='; $c4= $request['f_actividad'];}
        if($request['f_fecha_d']!='-1'){$c5="fecha_registro"; $op3='>='; $c6= $request['f_fecha_d'];}
        if($request['f_fecha_h']!='-1'){$c7="fecha_registro"; $op4='>='; $c8= $request['f_fecha_h'];}
        if($request['f_especie']!='-1'){$c9="especies.id"; $op5='='; $c10= $request['f_especie'];}
        if($request['f_peso_d']!='-1'){$c11="peso_ganado"; $op6='>='; $c12= $request['f_peso_d'];}
        if($request['f_peso_h']!='-1'){$c13="peso_ganado"; $op7='<='; $c14= $request['f_peso_h'];}
				if($request['f_lote']!='-1'){$c15="lote"; $op8='='; $c16= $request['f_lote'];}
        
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
					'lote'
        )
        ->join('especies',
        'registros.id_especie', 'especies.id')
        ->join('siembras', 'registros.id_siembra', 'siembras.id')
				->leftJoin('especies_siembra', function($join){
					$join->on('registros.id_especie', '=', 'especies_siembra.id_especie')->on('registros.id_siembra', '=', 'especies_siembra.id_siembra');
				})
        ->where($c1, $op1, $c2)
        ->where($c3, $op2, $c4)
        ->where($c5, $op3, $c6)
        ->where($c7, $op4, $c8)
        ->where($c9, $op5, $c10)
        ->where('peso_ganado', $op6, $c12)
        ->where('peso_ganado', $op7, $c14)
				->where($c15, $op8, $c16)
        ->orderBy('fecha_registro', 'desc')        
        ->get();
                    
        if(count($registros)>0){
            for($i=0;$i<count($registros); $i++){
                if($registros[$i]->tipo_registro == 0)$registros[$i]->nombre_registro = 'Muestreo';
                if($registros[$i]->tipo_registro == 1)$registros[$i]->nombre_registro = 'Pesca';        
                if($registros[$i]->tipo_registro == 2)$registros[$i]->nombre_registro = 'Mortalidad Inicial';        
               
            }
        }
            
        return $registros;
        
        
    }
}
