<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Contenedor;

class ContenedorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contenedores = Contenedor::all();
        return $contenedores;
        //
    }

    public function listadoContenedores()
    {
        return Contenedor::all()->chunk(18);
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
        $val = $request->validate([
            'contenedor' => 'required',
            'capacidad' => 'required',
            'estado' => 'required'

        ]);
        $contenedor = Contenedor::create([
            'contenedor' => $request['contenedor'],
            'capacidad' => $request['capacidad'],
            'estado' => $request['estado']
        ]);
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
        $contenedor = Contenedor::findOrFail($id);
        $contenedor->update($request->all());
        return 'ok';
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
        Contenedor::destroy($id);
        return 'ok';
    }
}
