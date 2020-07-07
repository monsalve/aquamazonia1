<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\EspecieSiembra;

class SiembraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $siembras = EspecieSiembra::all();
        return $siembras;
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
            'id_siembra' => 'required',            
            'id_especie' => 'required',
            'cantidad' => 'required',
            'peso_inicial' => 'required'
        ]);
        $siembra = EspecieSiembra::create([
            'id_siembra' => $request['id_siembra'],            
            'id_especie' => $request['id_especie'],
            'cantidad' => $request['cantidad'],
            'peso_inicial' => $request['peso_inicial']
        ]);
        
    }

    /**
     * Display the specified unidad.
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
        $siembra = EspecieSiembra::findOrFail($id);
        $siembra->update($request->all());
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
        EspecieSiembra::destroy($id);
        return 'eliminado';
    }
}