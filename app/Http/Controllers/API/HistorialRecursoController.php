<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Recursos;
use App\HistorialRecurso;

class HistorialRecursoController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		//
		$historial_recursos = HistorialRecurso::select('historial_costos_recursos.id as id', 'historial_costos_recursos.id_recurso', 'historial_costos_recursos.costo', 'historial_costos_recursos.fecha_registro', 'recursos.recurso', 'recursos.unidad')
			->join('recursos', 'recursos.id', 'historial_costos_recursos.id_recurso');

		if ($request['idRecurso'] != '') {
			$historial_recursos = $historial_recursos->where('id_recurso', $request['idRecurso']);
		}

		$historial_recursos = $historial_recursos->get();

		return $historial_recursos;
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
		HistorialRecurso::destroy($id);
	}
}
