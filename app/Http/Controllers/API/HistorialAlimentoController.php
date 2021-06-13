<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\HistorialAlimento;
use App\Alimento;

class HistorialAlimentoController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		//
		$historial_alimentos =  HistorialAlimento::select('historial_costos_alimentos.id as id', 'historial_costos_alimentos.id_alimento', 'historial_costos_alimentos.costo', 'historial_costos_alimentos.fecha_registro', 'alimentos.alimento')
			->join('alimentos', 'alimentos.id', 'historial_costos_alimentos.id_alimento');

		if ($request['idAlimento'] != '') {
			$historial_alimentos = $historial_alimentos->where('id_alimento', $request['idAlimento']);
		}

		$historial_alimentos = $historial_alimentos->get();

		return $historial_alimentos;
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
		HistorialAlimento::destroy($id);
	}
}
