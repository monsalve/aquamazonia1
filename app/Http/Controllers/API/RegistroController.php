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
		$registros = Registro::select(
			'registros.id as id',
			'id_siembra',
			'fecha_registro',
			'tipo_registro',
			'peso_ganado',
			'mortalidad',
			'cantidad',
			'estado',
			'biomasa',
			'cantidad',
			'especies.especie as especie',
			'especies.id as id_especie'
		)
			->join(
				'especies',
				'registros.id_especie',
				'especies.id'
			)
			->orderBy('registros.id', 'desc')
			->get();
		return $registros;
	}
	public function registrosxSiembra($id)
	{
		//
		$registros = Registro::select('registros.id as id', 'id_siembra', 'fecha_registro', 'tipo_registro', 'peso_ganado', 'mortalidad', 'cantidad', 'estado', 'biomasa', 'cantidad', 'especies.especie as especie', 'especies.id as id_especie')
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

		foreach ($request->campos as $campo) {
			$exs = EspecieSiembra::where('id_siembra', $campo['id_siembra'])->where('id_especie', $campo['id_especie'])->first();
			$biomasa = 0;

			if ($request->tipo_registro == 0) {

				$exs->cant_actual = $exs->cant_actual - $campo['mortalidad'];

				if ($campo['peso_ganado'] > $exs->peso_actual) {
					$exs->peso_actual = $campo['peso_ganado'];
				}
				$exs->save();


				if ($campo['peso_ganado'] == '' && $campo['mortalidad'] != '') {

					$biomasa = ($campo['mortalidad'] * $exs->peso_actual) / 1000;
					$registro = Registro::create([
						'id_especie' => $campo['id_especie'],
						'id_siembra' => $campo['id_siembra'],
						'fecha_registro' => $request['fecha_registro'],
						'tipo_registro' => $request['tipo_registro'],
						'peso_ganado' => $exs['peso_actual'],
						'mortalidad' => $campo['mortalidad'],
						// 'cantidad' => $exs['cant_actual'],
						'biomasa' => $biomasa
					]);
				} elseif ($campo['peso_ganado'] != '' || $campo['mortalidad'] != '') {
					$biomasa = ($campo['mortalidad'] * $campo['peso_ganado']) / 1000;
					$registro = Registro::create([
						'id_especie' => $campo['id_especie'],
						'id_siembra' => $campo['id_siembra'],
						'fecha_registro' => $request['fecha_registro'],
						'tipo_registro' => $request['tipo_registro'],
						'peso_ganado' => $campo['peso_ganado'],
						'mortalidad' => $campo['mortalidad'],
						// 'cantidad' => $exs['cant_actual'],
						'biomasa' => $biomasa
					]);
				} elseif ($campo['peso_ganado'] != '' || $campo['mortalidad'] == '') {
					// $biomasa = ($campo['mortalidad'] * $campo['peso_ganado']) / 1000;
					$registro = Registro::create([
						'id_especie' => $campo['id_especie'],
						'id_siembra' => $campo['id_siembra'],
						'fecha_registro' => $request['fecha_registro'],
						'tipo_registro' => $request['tipo_registro'],
						'peso_ganado' => $campo['peso_ganado'],
						'mortalidad' => $campo['mortalidad'],
						// 'cantidad' => $exs['cant_actual'],
						'biomasa' => $biomasa
					]);
				}
			}
			if ($campo['biomasa'] != '') {
				if ($request->tipo_registro == 1) {
					$registro = Registro::create([
						'id_especie' => $campo['id_especie'],
						'id_siembra' => $campo['id_siembra'],
						'fecha_registro' => $request['fecha_registro'],
						'tipo_registro' => $request['tipo_registro'],
						'biomasa' => $campo['biomasa'],
						'cantidad' => (($campo['biomasa'] * 1000) / $exs->peso_actual),
						'peso_ganado' => $campo['peso_actual'],
					]);

					$exs->cant_actual = $exs->cant_actual - $registro->cantidad;
					$exs->save();
				}
			}
			if ($request->tipo_registro == 2) {
				$biomasa = ($campo['mortalidad'] * $exs->peso_inicial) / 1000;

				if (($campo['mortalidad'] > 0) && ($campo['mortalidad'] < $exs->cant_actual)) {
					$exs->cant_actual = $exs->cant_actual - $campo['mortalidad'];
				}
				$exs->save();
				if ($campo['mortalidad']) {
					$registro = Registro::create([
						'id_especie' => $campo['id_especie'],
						'id_siembra' => $campo['id_siembra'],
						'fecha_registro' => $request['fecha_registro'],
						'tipo_registro' => $request['tipo_registro'],
						'mortalidad' => $campo['mortalidad'],
						'peso_ganado' => $exs['peso_inicial'],
						// 'cantidad' => $exs['cant_actual'],
						'biomasa' => $biomasa
					]);
				}
			}
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
		Registro::destroy($id);

		$oldData = $this->ultimoRegistroEspecieSiembra($request['campos']['id_siembra'], $request['campos']['id_especie']);

		$exs = EspecieSiembra::where('id_siembra', $request['campos']['id_siembra'])->where('id_especie', $request['campos']['id_especie'])->first();
		if ($request['campos']['mortalidad'] > 0) {
			$exs->cant_actual = $exs->cant_actual + $request['campos']['mortalidad'];
		}

		$exs->peso_actual = $oldData['peso_actual'];
		$exs->cant_actual = $oldData['cantidad_actual'];

		$exs->save();
	}

	public function ultimoRegistroEspecieSiembra($id_siembra, $id_especie)
	{

		$exs = EspecieSiembra::where('id_siembra', $id_siembra)->where('id_especie', $id_especie)->first();

		$peso = Registro::select()
			->where('registros.id_siembra', '=', $id_siembra)
			->where('registros.id_especie', '=', $id_especie)
			->where('registros.peso_ganado', '!=', '')
			->orderBy('registros.id', 'desc')
			->first();

		if ($peso != '' || $peso != NULL) {
			$peso_actual = $peso->peso_ganado;
		} else {
			$peso_actual = $exs->peso_inicial;
		}
	
		$cantidad = Registro::select()
			->where('registros.id_siembra', '=', $id_siembra)
			->where('registros.id_especie', '=', $id_especie)
			->where('registros.cantidad', '!=', '')
			->orderBy('registros.id', 'desc')
			->first();

		if ($cantidad != '' || $cantidad != NULL) {
			$cantidad_actual = $cantidad->cantidad;
		} else {
			$cantidad_actual = $exs->cantidad;
		}

		return [
			'peso_actual' => $peso_actual,
			'cantidad_actual' => $cantidad_actual
		];
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id, Request $request)
	{
		//
		$registro = Registro::destroy($id);
	}

	public function filtroRegistros(Request $request)
	{
		$c1 = 'registros.id';
		$op1 = '!=';
		$c2 = '-1';
		$c3 = 'registros.id';
		$op2 = '!=';
		$c4 = '-1';
		$c5 = 'registros.id';
		$op3 = '!=';
		$c6 = '-1';
		$c7 = 'registros.id';
		$op4 = '!=';
		$c8 = '-1';

		if ($request['f_siembra'] != '-1') {
			$c1 = "id_siembra";
			$op1 = '=';
			$c2 = $request['f_siembra'];
		}
		if ($request['f_actividad'] != '-1') {
			$c3 = "tipo_registro";
			$op2 = '=';
			$c4 = $request['f_actividad'];
		}
		if ($request['f_fecha_d'] != '-1') {
			$c5 = "fecha_registro";
			$op3 = '>=';
			$c6 = $request['f_fecha_d'];
		}
		if ($request['f_fecha_h'] != '-1') {
			$c7 = "fecha_registro";
			$op4 = '>=';
			$c8 = $request['f_fecha_h'];
		}

		$registros = Registro::select(
			'registros.id as id',
			'id_siembra',
			'fecha_registro',
			'tipo_registro',
			'peso_ganado',
			'mortalidad',
			'cantidad',
			'estado',
			'biomasa',
			'cantidad',
			'especies.especie as especie',
			'especies.id as id_especie'
		)
			->join(
				'especies',
				'registros.id_especie',
				'especies.id'
			)
			->where($c1, $op1, $c2)
			->where($c3, $op2, $c4)
			->where($c5, $op3, $c6)
			->where($c7, $op4, $c8)
			->orderBy('registros.id', 'desc')
			->get();

		return $registros;
	}
}
