<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Recursos;
use App\HistorialRecurso;

class RecursosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $recursos = Recursos::orderBy('recurso', 'asc')->get();
        return $recursos;
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
            'recurso' => 'required',
            'unidad' => 'required',
            'costo' => 'required'
        ]);
        $recursos = Recursos::create([
            'recurso' => $request['recurso'],
            'unidad' => $request['unidad'],
            'costo' => $request['costo']
        ]);

        HistorialRecurso::create([
            'id_recurso' => $recursos['id'],
            'costo' => $recursos['costo'],
            'fecha_registro' => date('Y-m-d')
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
        $recurso = Recursos::findOrFail($id);
        $recurso->update($request->all());

        HistorialRecurso::create([
            'id_recurso' => $request['id'],
            'costo' => $request['costo'],
            'fecha_registro' => date('Y-m-d')
        ]);

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
        Recursos::destroy($id);
        HistorialRecurso::where('id_recurso', $id)->delete();

        return 'eliminado';
    }
}
