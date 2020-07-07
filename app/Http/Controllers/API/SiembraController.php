<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\EspecieSiembra;
use App\Siembra;

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
        //$especieSiembras = EspecieSiembra::all();
        $especie = Siembra::all();
        return $especie;
        
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
            // 'id_siembra' => 'required',            
            // 'id_especie' => 'required',
            // 'cantidad' => 'required',
            // 'peso_inicial' => 'required'
        ]);
       
        // exit;die;
        $siembra = new Siembra();
        $siembra->id_contenedor = $request->siembra['id_contenedor'];
        $siembra->fecha_inicio = $request->siembra['fecha_inicio'];       
        $siembra->estado = 1;
        $siembra->save();
        
        foreach($request->especies as $especie){
            $especieSiembra = new EspecieSiembra();
            $especieSiembra->id_siembra = $siembra->id;
            $especieSiembra->id_especie = $especie['id_especie'];
            $especieSiembra->cantidad =  $especie['cantidad'];
            $especieSiembra->peso_inicial = $especie['peso_inicial'];
            $especieSiembra->save();
        }
        
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
        $especieSiembras = EspecieSiembra::findOrFail($id);
        $especieSiembras->update($request->all());
        // $siembra = Siembra::findOrFail($id);
        // $siembra->update($request->all());
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
        Siembra::destroy(id);
        return 'eliminado';
    }
}