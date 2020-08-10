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
        $registros = Registro::select('registros.id as id', 'id_siembra','fecha_registro', 'tiempo', 'tipo_registro', 'peso_ganado', 'mortalidad', 'cantidad', 'estado', 'biomasa', 'cantidad', 'especies.especie as especie')
            ->join('especies', 'registros.id_especie', 'especies.id')
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
        //
        // $especie = Especie::create([
        //     'especie' => $request['especie'],            
        //     'descripcion' => $request['descripcion'],            
        // ]);
        
        foreach($request->campos as $campo){
            $exs = EspecieSiembra::where('id_siembra', $campo['id_siembra'])->where('id_especie', $campo['id_especie'])->first();
            if($campo['mortalidad'] > 0){
                $exs->cant_actual= $exs->cant_actual -$campo['mortalidad'];
            }
            if($campo['cantidad'] > 0){
                $exs->cant_actual= $exs->cant_actual -$campo['cantidad'];
            }
            if($campo['peso_ganado'] > 0){
                // $exs->peso_actual= floatval($exs->peso_actual) + floatval($campo['peso_ganado']);
                $exs->peso_actual = ($campo['peso_ganado']);
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
        $registro = Registro::destroy($id);
    }
}
