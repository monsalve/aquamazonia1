<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Registro;
use App\EspecieSiembra;

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
        $registros = Registro::select('registros.id as id', 'id_siembra','fecha_registro', 'tiempo', 'tipo_registro', 'peso_ganado', 'mortalidad', 'cantidad', 'estado', 'biomasa', 'cantidad', 'especies.especie as especie', 'especies.id as id_especie' )
            ->join('especies', 'registros.id_especie', 'especies.id')
            ->orderBy('registros.id', 'desc')
            ->get();
        return $registros;
        
    }
    public function registrosxSiembra($id)
    {
        //
        $registros = Registro::select('registros.id as id', 'id_siembra','fecha_registro', 'tiempo', 'tipo_registro', 'peso_ganado', 'mortalidad', 'cantidad', 'estado', 'biomasa', 'cantidad', 'especies.especie as especie', 'especies.id as id_especie')
            ->join('especies', 'registros.id_especie', 'especies.id')
            ->where('id_siembra', '=', $id)
            ->orderBy('registros.id', 'desc')
            ->get();
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
       
        foreach($request->campos as $campo){
            // $mensajes = array();
            $exs = EspecieSiembra::where('id_siembra', $campo['id_siembra'])->where('id_especie', $campo['id_especie'])->first();
            
            if(($campo['mortalidad'] > 0) && ($campo['mortalidad'] < $exs->cant_actual) ){
                $exs->cant_actual= $exs->cant_actual -$campo['mortalidad'];
                $mensajes[]= 'Datos guardados correctamente';
            }else{ $mensajes[]= 'Esta cifra no puede ser mayor';}
            
            if($campo['cantidad'] > 0 && $campo['cantidad'] < $exs->cant_actual){
                $exs->cant_actual= $exs->cant_actual - $campo['cantidad'];
                $mensajes[]= 'Datos guardados correctamente';
            }else{ $mensajes[]= 'Esta cifra no puede ser mayor'; }
            
            if($campo['peso_ganado'] > $exs->cant_actual){
                $exs->peso_actual = ($campo['peso_ganado']);
                $mensajes[]= 'Datos guardados correctamente';
            }
            $exs->save();
            $registro = Registro::create([
                'id_especie' =>$campo['id_especie'],
                'id_siembra' => $campo['id_siembra'],
                'fecha_registro' => $request['fecha_registro'],
                'tiempo' => $request['tiempo'],
                'tipo_registro' => $request['tipo_registro'],
                'peso_ganado' => $campo['peso_ganado'],
                'mortalidad' => $campo['mortalidad'],
                'biomasa' => $campo['biomasa'],
                'cantidad' => $campo['cantidad']
            ]);
            
            return ($mensajes);
            
        }
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
        $registro = Registro::destroy($id);
     
        $exs = EspecieSiembra::where('id_siembra', $request['campos']['id_siembra'])->where('id_especie', $request['campos']['id_especie'])->first();
        if($request['campos']['mortalidad'] > 0){
            $exs->cant_actual= $exs->cant_actual + $request['campos']['mortalidad'];
        }
        if($request['campos']['cantidad'] > 0){
            $exs->cant_actual= $exs->cant_actual + $request['campos']['cantidad'];
        }
        $exs->save();
       
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
}
