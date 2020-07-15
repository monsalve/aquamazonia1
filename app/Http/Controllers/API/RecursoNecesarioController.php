<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\RecursoNecesario;
use App\RecursoSiembra;

class RecursoNecesarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        // $recursosNecesarios = RecursoNecesario::all();
        $recursosNecesarios = RecursoNecesario::select('recursos_necesarios.id','id_recurso', 'id_alimento', 'tipo_actividad', 'fecha_ra', 'horas_hombre', 'cant_manana', 'cant_tarde', 'detalles', 'id_siembra', 'id_registro', 'nombre_siembra')
        ->join('recursos_siembras', 'recursos_necesarios.id', 'recursos_siembras.id_registro')   
        ->join('siembras', 'recursos_siembras.id_siembra', 'siembras.id')   
        ->get();
    
        return $recursosNecesarios;
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
        $recursoNecesario = new RecursoNecesario();
        $recursoNecesario->id_recurso = $request['id_recurso'];
        $recursoNecesario->id_alimento =  $request['id_alimento'];
        $recursoNecesario->tipo_actividad = $request['tipo_actividad'];
        $recursoNecesario->fecha_ra = $request['fecha_ra'];
        $recursoNecesario->horas_hombre = $request['horas_hombre'];
        $recursoNecesario->cant_manana = $request['cant_manana'];
        $recursoNecesario->cant_tarde = $request['cant_tarde'];
        $recursoNecesario->detalles = $request['detalles'];
        $recursoNecesario->save();
        
       foreach ($request->id_siembra as $siembra){
            $recursoSiembra = new RecursoSiembra();
            $recursoSiembra->id_registro = $recursoNecesario->id;
            $recursoSiembra->id_siembra = $siembra;
            $recursoSiembra->save();
        }
       
            
       return ($request);
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
