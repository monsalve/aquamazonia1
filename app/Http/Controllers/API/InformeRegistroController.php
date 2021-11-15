<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\EspeciesSiembraController;
use Illuminate\Http\Request;
use App\Registro;
use App\EspecieSiembra;
use App\RecursoNecesario;
use App\Siembra;
use App\Recursos;


class InformeRegistroController extends Controller
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
			'registros.id_siembra as id_siembra',
			'registros.id_especie as id_especie',
			'fecha_registro',
			'tipo_registro',
			'peso_ganado',
			'registros.cantidad',
			'biomasa',
			'mortalidad',
			'especies.especie as especie',
			'nombre_siembra',
			'lote',
			'especies_siembra.cantidad as cantidad_inicial',
			'especies_siembra.cant_actual as cantidad_actual',
			'especies_siembra.peso_actual as peso_actual',
			'siembras.id_contenedor'
		)
			->join(
				'especies',
				'registros.id_especie',
				'especies.id'
			)
			->join('siembras', 'registros.id_siembra', 'siembras.id')
			->leftJoin('especies_siembra', function ($join) {
				$join->on('registros.id_especie', '=', 'especies_siembra.id_especie')->on('registros.id_siembra', '=', 'especies_siembra.id_siembra');
			})
			->orderBy('fecha_registro', 'desc')
			->get();

		$especies_siembra = new EspeciesSiembraController;
		if (count($registros) > 0) {
			foreach ($registros as $registro) {

				$registro->mortalidad_general = $especies_siembra->cantidadEspecieSiembra($registro->id_siembra, $registro->id_especie)->mortalidad;
				$registro->biomasa_general = $especies_siembra->cantidadEspecieSiembra($registro->id_siembra, $registro->id_especie)->biomasa;
				$registro->salida_animales_general = $especies_siembra->cantidadEspecieSiembra($registro->id_siembra, $registro->id_especie)->cantidad + $registro->mortalidad_general;
				$registro->cantidad_actual = $registro->cantidad_inicial - $registro->salida_animales_general;
				$registro->biomasa_disponible = ((($registro->peso_actual) * ($registro->cantidad_actual)) / 1000);
				$registro->biomasa_inicial =  ((($registro->peso_inicial) * ($registro->cantidad_inicial)) / 1000);

				$registro->bio_dispo_alimen = $this->BiomasaAlimento($registro->id_siembra)['bio_dispo_alimen'];
				$registro->salida_animales = $registro->cantidad + $registro->mortalidad;
				if ($registro->tipo_registro == 0) $registro->nombre_registro = 'Muestreo';
				if ($registro->tipo_registro == 1) $registro->nombre_registro = 'Pesca';
				if ($registro->tipo_registro == 2) $registro->nombre_registro = 'Mortalidad Inicial';
				if ($registro->tipo_registro == 3) $registro->nombre_registro = 'Peso Inicial';

				$registro->biomasa_disponible = number_format($registro->biomasa_disponible, 2, ',', '');
				$registro->bio_dispo_alimen = number_format($registro->bio_dispo_alimen, 2, ',', '');
				$registro->cantidad_actual = number_format($registro->cantidad_actual, 0, '', '');
				$registro->salida_animales = number_format($registro->salida_animales, 0, '', '');
				$registro->biomasa = number_format($registro->biomasa, 2, ',', '');
			}
		}
		return $registros;
	}


	public function BiomasaAlimento($id_siembra)
	{
		$siembras = Siembra::select(
			'siembras.id as id',
			'nombre_siembra'
		)
			->where('siembras.id', $id_siembra)
			->get();

		$existencias = EspecieSiembra::select(
			'cant_actual',
			'especies_siembra.cantidad as cantidad_inicial',
			'especies_siembra.id_especie as id_especie',
			'especies_siembra.id_siembra as id_siembra',
			'peso_inicial',
			'peso_actual',
		)
			->orderBy('especies_siembra.id_siembra')
			->orderBy('especies_siembra.id_especie')
			->join('siembras', 'especies_siembra.id_siembra', 'siembras.id')
			->get();

		$registros = Registro::select()
			->join('siembras', 'registros.id_siembra', 'siembras.id')->where('siembras.estado', '=', '1')
			->get();

		$recursos_necesarios = RecursoNecesario::select(
			'recursos_necesarios.id as id',
			'recursos_siembras.id_registro as id_registro',
			'id_siembra',
			'id_alimento',
			'cant_manana',
			'cant_tarde',
			'conv_alimenticia',
		)
			->join('recursos_siembras', 'recursos_necesarios.id', 'recursos_siembras.id_registro')
			->leftJoin('alimentos', 'recursos_necesarios.id_alimento', 'alimentos.id')
			->get();

		$data = array();
		if (count($siembras) > 0) {
			foreach ($siembras as $siembra) {
				if (count($existencias) > 0) {
					foreach ($existencias as $existencia) {
						$existencia->biomasa_inicial =  ((($existencia->peso_inicial) * ($existencia->cantidad_inicial)) / 1000);

						if ($siembra->id == $existencia->id_siembra) {
							$siembra->biomasa_inicial += $existencia->biomasa_inicial;

							foreach ($registros as $registro) {
								if ($existencia->id_siembra == $registro->id_siembra) {
									$existencia->salida_biomasa += $registro->biomasa;
									if ($existencia->id_especie == $registro->id_especie) {
										$registro->mortalidad_kg = (($registro->mortalidad * $registro->peso_ganado) / 1000);
										$existencia->mortalidad += $registro->mortalidad;
										$existencia->mortalidad_kg += $registro->mortalidad_kg;
										$existencia->salida_biomasa_especie += $registro->biomasa;
									}
								}
							}
							$siembra->mortalidad += $existencia->mortalidad;
							$siembra->mortalidad_kg += $existencia->mortalidad_kg;
							$siembra->salida_biomasa = $existencia->salida_biomasa;
						}
					}
				}

				foreach ($recursos_necesarios as $recurso_necesario) {
					if ($siembra->id == $recurso_necesario->id_siembra) {

						$recurso_necesario->cantidad_total_alimento = $recurso_necesario->cant_tarde + $recurso_necesario->cant_manana;
						$siembra->cantidad_total_alimento +=  $recurso_necesario->cantidad_total_alimento;

						if ($recurso_necesario->conv_alimenticia > 0) {
							$recurso_necesario->incr_bio_acum_conver = $recurso_necesario->cantidad_total_alimento / $recurso_necesario->conv_alimenticia;
							$siembra->incr_bio_acum_conver +=  $recurso_necesario->incr_bio_acum_conver;
						}
					}
				}
				$siembra->bio_dispo_alimen = (($siembra->incr_bio_acum_conver + $siembra->biomasa_inicial) - ($siembra->salida_biomasa + $siembra->mortalidad_kg));

				$data = [
					"id" => $siembra->id,
					'bio_dispo_alimen' => $siembra->bio_dispo_alimen //Los datos adicionaes se encuentran en el controlador de biomasaAlimento
				];
			}
		}


		return $data;
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
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
		$c3 = 'registros.tipo_registro';
		$op2 = '!=';
		$c4 = '-1';
		$c5 = 'registros.id';
		$op3 = '!=';
		$c6 = '-1';
		$c7 = 'registros.id';
		$op4 = '!=';
		$c8 = '-1';
		$c9 = 'registros.id';
		$op5 = '!=';
		$c10 = '-1';
		$c11 = "registros.peso_ganado";
		$op6 = '!=';
		$c12 = '0';
		$c13 = "registros.peso_ganado";
		$op7 = '!=';
		$c14 = '0';
		$c15 = 'lote';
		$op8 = '!=';
		$c16 = '-1';

		$estado_siembra = '-1';
		$filtro_estado_siembra = '!=';
		
		$filtro_contenedor = '!=';
		$id_contenedor = '-1';
		

		if ($request['f_siembra'] != '-1') {
			$c1 = "registros.id_siembra";
			$op1 = '=';
			$c2 = $request['f_siembra'];
		}
		if ($request['f_actividad'] != '-1') {
			$c3 = "registros.tipo_registro";
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
			$op4 = '<=';
			$c8 = $request['f_fecha_h'];
		}
		if ($request['f_especie'] != '-1') {
			$c9 = "especies.id";
			$op5 = '=';
			$c10 = $request['f_especie'];
		}
		if ($request['f_peso_d'] != '-1') {
			$c11 = "peso_ganado";
			$op6 = '>=';
			$c12 = $request['f_peso_d'];
		}
		if ($request['f_peso_h'] != '-1') {
			$c13 = "peso_ganado";
			$op7 = '<=';
			$c14 = $request['f_peso_h'];
		}
		if ($request['f_lote'] != '-1') {
			$c15 = "lote";
			$op8 = '=';
			$c16 = $request['f_lote'];
		}
		if ($request['f_estado'] != '-1') {
			$filtro_estado_siembra = '=';
			$estado_siembra = $request['f_estado'];
		}
		if ($request['id_contenedor'] != '-1') {
			$filtro_contenedor = '=';
			$id_contenedor = $request['id_contenedor'];
		}

		$registros = Registro::select(
			'registros.id as id',
			'registros.id_siembra as id_siembra',
			'registros.id_especie as id_especie',
			'fecha_registro',
			'tipo_registro',
			'peso_ganado',
			'registros.cantidad',
			'biomasa',
			'mortalidad',
			'especies.especie as especie',
			'nombre_siembra',
			'lote',
			'especies_siembra.cantidad as cantidad_inicial',
			'especies_siembra.cant_actual as cantidad_actual',
			'especies_siembra.peso_actual as peso_actual',
			'siembras.id_contenedor'
		)
			->join(
				'especies',
				'registros.id_especie',
				'especies.id'
			)
			->join('siembras', 'registros.id_siembra', 'siembras.id')
			->join('especies_siembra', function ($join) {
				$join->on('registros.id_especie', '=', 'especies_siembra.id_especie')->on('registros.id_siembra', '=', 'especies_siembra.id_siembra');
			})
			->where($c1, $op1, $c2)
			->where($c3, $op2, $c4)
			->where($c5, $op3, $c6)
			->where($c7, $op4, $c8)
			->where($c9, $op5, $c10)
			->where($c15, $op8, $c16)
			->where('siembras.estado', $filtro_estado_siembra, $estado_siembra)
			->where('siembras.id_contenedor', $filtro_contenedor, $id_contenedor)

			->orderBy('fecha_registro', 'desc')
			->get();

		$especies_siembra = new EspeciesSiembraController;
		if (count($registros) > 0) {
			foreach ($registros as $registro) {

				$registro->mortalidad_general = $especies_siembra->cantidadEspecieSiembra($registro->id_siembra, $registro->id_especie)->mortalidad;
				$registro->biomasa_general = $especies_siembra->cantidadEspecieSiembra($registro->id_siembra, $registro->id_especie)->biomasa;
				$registro->salida_animales_general = $especies_siembra->cantidadEspecieSiembra($registro->id_siembra, $registro->id_especie)->cantidad + $registro->mortalidad_general;
				$registro->cantidad_actual = $registro->cantidad_inicial - $registro->salida_animales_general;
				$registro->biomasa_disponible = ((($registro->peso_actual) * ($registro->cantidad_actual)) / 1000);
				$registro->biomasa_inicial =  ((($registro->peso_inicial) * ($registro->cantidad_inicial)) / 1000);

				$registro->bio_dispo_alimen = $this->BiomasaAlimento($registro->id_siembra)['bio_dispo_alimen'];
				$registro->salida_animales = $registro->cantidad + $registro->mortalidad;
				if ($registro->tipo_registro == 0) $registro->nombre_registro = 'Muestreo';
				if ($registro->tipo_registro == 1) $registro->nombre_registro = 'Pesca';
				if ($registro->tipo_registro == 2) $registro->nombre_registro = 'Mortalidad Inicial';
				if ($registro->tipo_registro == 3) $registro->nombre_registro = 'Peso Inicial';

				$registro->biomasa_disponible = number_format($registro->biomasa_disponible, 2, ',', '');
				$registro->bio_dispo_alimen = number_format($registro->bio_dispo_alimen, 2, ',', '');
				$registro->cantidad_actual = number_format($registro->cantidad_actual, 0, '', '');
				$registro->salida_animales = number_format($registro->salida_animales, 0, '', '');
				$registro->biomasa = number_format($registro->biomasa, 2, ',', '');
			}
		}

		return $registros;
	}
}
