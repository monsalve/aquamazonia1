<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegistroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $siembra = Siembra::select('siembras.id as id', 'id_contenedor','contenedor','fecha_inicio', 'ini_descanso', 'fin_descanso','siembras.estado as estado')
        ->join('contenedores','siembras.id_contenedor','contenedores.id')
        ->where('siembras.estado','=',1)
        ->get();
        
          $peces = EspecieSiembra::select('especies_siembra.id as id','id_siembra','id_especie','cantidad','peso_inicial','cant_actual',  'peso_actual', 'especies.especie as especie')
                  ->join('especies','especies_siembra.id_especie','especies.id')                    
                  ->get();
          $pxs = array();
          
          foreach($peces as $p) {
          $pxs[$p->id_siembra][$p->id] = $p;
          }                
          
          return ["siembra"=> $siembra, "pecesSiembra" =>  $peces];
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
}
