<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Registro;

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
        $registros = Registro::all();
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
        //
        // $especie = Especie::create([
        //     'especie' => $request['especie'],            
        //     'descripcion' => $request['descripcion'],            
        // ]);
        
        foreach($request->campos as $campo){
            $registro = Registro::create([
                'id_especie' =>$campo['id_especie'],
                'id_siembra' => $campo['id_siembra'],
                'fecha_registro' => $request['fecha_registro'],
                'tiempo' => $request['tiempo'],
                'tipo_registro' => $request['tipo_registro'],
                'peso_ganado' => $campo['peso_ganado'],
                'mortalidad' => $campo['mortalidad'],
                'biomasa' => $campo['biomasa'],
                'cantidad' => $campo['cantidad'],               
            
            ]);
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
