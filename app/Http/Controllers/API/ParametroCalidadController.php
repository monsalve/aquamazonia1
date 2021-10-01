<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CalidadAgua;
use App\Siembra;
use App\Contenedor;


class ParametroCalidadController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		//
		$calidad_agua = CalidadAgua::select(
			'calidad_agua.id as id',
			'contenedores.id as id_contenedor',
			'4_am',
			'4_pm',
			'7_am',
			'8_pm',
			'12_am',
			'amonio',
			'contenedor',
			'fecha_parametro',
			'nitrato',
			'nitrito',
			'otros',
			'ph',
			'temperatura'
		)
			->join('contenedores', 'calidad_agua.id_contenedor', 'contenedores.id')
			->orderBy('fecha_parametro', 'desc')
			->get();

		// Formateo de variables
		$val4am = 0;
		$val4pm = 0;
		$val7am = 0;
		$val8pm = 0;
		$val12am = 0;
		$valamo = 0;
		$valnit = 0;
		$valnat = 0;
		$valot = 0;
		$valph = 0;
		$valtem = 0;
		$parametros_calidad = array();

		foreach ($calidad_agua as $cl) {
			if ($cl['4_am'] === NULL || $cl['4_am'] === '') {
				$val4am = '';
			} else {
				$val4am =  number_format($cl['4_am'], 2, ',', '');
			}
			if ($cl['4_pm'] === NULL || $cl['4_pm'] === '') {
				$val4pm = '';
			} else {
				$val4pm =  number_format($cl['4_pm'], 2, ',', '');
			}
			if ($cl['7_am'] === NULL || $cl['7_am'] === '') {
				$val7am = '';
			} else {
				$val7am =  number_format($cl['7_am'], 2, ',', '');
			}
			if ($cl['8_pm'] === NULL || $cl['8_pm'] === '') {
				$val8pm = '';
			} else {
				$val8pm =  number_format($cl['8_pm'], 2, ',', '');
			}
			if ($cl['12_am'] === NULL || $cl['12_am'] === '') {
				$val12am = '';
			} else {
				$val12am =  number_format($cl['12_am'], 2, ',', '');
			}
			if ($cl['amonio'] === NULL || $cl['amonio'] === '') {
				$valamo = '';
			} else {
				$valamo =  number_format($cl['amonio'], 2, ',', '');
			}
			if ($cl['nitrato'] === NULL || $cl['nitrato'] === '') {
				$valnat = '';
			} else {
				$valnat =  number_format($cl['nitrato'], 2, ',', '');
			}
			if ($cl['nitrito'] === NULL || $cl['nitrito'] === '') {
				$valnit = '';
			} else {
				$valnit =  number_format($cl['nitrito'], 2, ',', '');
			}
			if ($cl['otros'] === NULL || $cl['otros'] === '') {
				$valot = '';
			} else {
				$valot =  number_format($cl['otros'], 2, ',', '');
			}
			if ($cl['ph'] === NULL || $cl['ph'] === '') {
				$valph = '';
			} else {
				$valph =  number_format($cl['ph'], 2, ',', '');
			}
			if ($cl['temperatura'] === NULL || $cl['temperatura'] === '') {
				$valtem = '';
			} else {
				$valtem =  number_format($cl['temperatura'], 2, '.', '');
			}

			$parametros_calidad[] = array(
				'id' =>  $cl['id'],
				'id_contenedor' => $cl['id_contenedor'],
				'4_am' => $val4am,
				'4_pm' => $val4pm,
				'7_am' => $val7am,
				'8_pm' => $val8pm,
				'12_am' => $val12am,
				'amonio' => $valamo,
				'contenedor' => $cl['contenedor'],
				'fecha_parametro' => $cl['fecha_parametro'],
				'nitrato' => $valnat,
				'nitrito' => $valnit,
				'otros' => $valot,
				'ph' => $valph,
				'temperatura' => $valtem
			);
		}

		$promedios = array();
		$prom_12am = 0;
		$prom_4am = 0;
		$prom_7am = 0;
		$prom_4pm = 0;
		$prom_8pm = 0;
		$prom_temperatura = 0;
		$prom_ph = 0;
		$prom_amonio = 0;
		$prom_nitrito = 0;
		$prom_nitrato = 0;
		$prom_otros = 0;
		$count_12am = 0;
		$count_4am = 0;
		$count_7am = 0;
		$count_4pm = 0;
		$count_8pm = 0;
		$count_temperatura = 0;
		$count_ph = 0;
		$count_amonio = 0;
		$count_nitrito = 0;
		$count_nitrato = 0;
		$count_otros = 0;


		for ($i = 0; $i < count($calidad_agua); $i++) {
			if ($calidad_agua[$i]['12_am'] !== NULL && $calidad_agua[$i]['12_am'] !== '') {
				$count_12am += 1;
				$prom_12am += $calidad_agua[$i]['12_am'];
				$promedios['promedio_12_am'] = (round($prom_12am / $count_12am, 2));
				$promedios['promedio_12_am']  = number_format($promedios['promedio_12_am'], 2, ',', '');
			}
			if ($calidad_agua[$i]['4_am'] !== NULL && $calidad_agua[$i]['4_am'] !== '') {
				$count_4am += 1;
				$prom_4am += $calidad_agua[$i]['4_am'];
				$promedios['promedio_4_am'] = (round($prom_4am / $count_4am, 2));
				$promedios['promedio_4_am']  = number_format($promedios['promedio_4_am'], 2, ',', '');
			}
			if ($calidad_agua[$i]['7_am'] !== NULL && $calidad_agua[$i]['7_am'] !== '') {
				$count_7am += 1;
				$prom_7am += $calidad_agua[$i]['7_am'];
				$promedios['promedio_7_am'] = (round($prom_7am / $count_7am, 2));
				$promedios['promedio_7_am']  = number_format($promedios['promedio_7_am'], 2, ',', '');
			}
			if ($calidad_agua[$i]['4_pm'] !== NULL && $calidad_agua[$i]['4_pm'] !== '') {
				$count_4pm += 1;
				$prom_4pm += $calidad_agua[$i]['4_pm'];
				$promedios['promedio_4_pm'] = (round($prom_4pm / $count_4pm, 2));
				$promedios['promedio_4_pm']  = number_format($promedios['promedio_4_pm'], 2, ',', '');
			}
			if ($calidad_agua[$i]['8_pm'] !== NULL && $calidad_agua[$i]['8_pm'] !== '') {
				$count_8pm += 1;
				$prom_8pm += $calidad_agua[$i]['8_pm'];
				$promedios['promedio_8_pm'] = (round($prom_8pm / $count_8pm, 2));
				$promedios['promedio_8_pm']  = number_format($promedios['promedio_8_pm'], 2, ',', '');
			}
			if ($calidad_agua[$i]['temperatura'] !== NULL && $calidad_agua[$i]['temperatura'] !== '') {
				$count_temperatura += 1;
				$prom_temperatura += $calidad_agua[$i]['temperatura'];
				$promedios['promedio_temperatura'] = (round($prom_temperatura / $count_temperatura, 2));
				$promedios['promedio_temperatura']  = number_format($promedios['promedio_temperatura'], 2, ',', '');
			}
			if ($calidad_agua[$i]['ph'] !== NULL && $calidad_agua[$i]['ph'] !== '') {
				$count_ph += 1;
				$prom_ph += $calidad_agua[$i]['ph'];
				$promedios['promedio_ph'] = (round($prom_ph / $count_ph, 2));
				$promedios['promedio_ph']  = number_format($promedios['promedio_ph'], 2, ',', '');
			}
			if ($calidad_agua[$i]['amonio'] !== NULL && $calidad_agua[$i]['amonio'] !== '') {
				$count_amonio += 1;
				$prom_amonio += $calidad_agua[$i]['amonio'];
				$promedios['promedio_amonio'] = (round($prom_amonio / $count_amonio, 2));
				$promedios['promedio_amonio']  = number_format($promedios['promedio_amonio'], 2, ',', '');
			}
			if ($calidad_agua[$i]['nitrito'] !== NULL && $calidad_agua[$i]['nitrito'] !== '') {
				$count_nitrito += 1;
				$prom_nitrito += $calidad_agua[$i]['nitrito'];
				$promedios['promedio_nitrito'] = (round($prom_nitrito / $count_nitrito, 2));
				$promedios['promedio_nitrito']  = number_format($promedios['promedio_nitrito'], 2, ',', '');
			}

			if ($calidad_agua[$i]['nitrato'] !== NULL && $calidad_agua[$i]['nitrato'] !== '') {
				$count_nitrato += 1;
				$prom_nitrato += $calidad_agua[$i]['nitrato'];
				$promedios['promedio_nitrato'] = (round($prom_nitrato / $count_nitrato, 2));
				$promedios['promedio_nitrato']  = number_format($promedios['promedio_nitrato'], 2, ',', '');
			}

			if ($calidad_agua[$i]['otros'] !== NULL && $calidad_agua[$i]['otros'] !== '') {
				$count_otros += 1;
				$prom_otros += $calidad_agua[$i]['otros'];
				$promedios['promedio_otros'] = (round($prom_otros / $count_otros, 2));
				$promedios['promedio_otros']  = number_format($promedios['promedio_otros'], 2, ',', '');
			}
		}

		return ['calidad_agua' => $parametros_calidad, 'promedios' => $promedios];
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
		foreach ($request->id_contenedor as $contenedor) {
			$calidad_agua = new CalidadAgua();
			$calidad_agua->fecha_parametro = $request['fecha_parametro'];
			// $calidad_agua->id_contenedor = $request['id_contenedor'];
			$calidad_agua->id_contenedor = $contenedor;
			$calidad_agua->{'12_am'} = $request['12_am'];
			$calidad_agua->{'4_am'} = $request['4_am'];
			$calidad_agua->{'7_am'} = $request['7_am'];
			$calidad_agua->{'4_pm'} = $request['4_pm'];
			$calidad_agua->{'8_pm'} = $request['8_pm'];
			$calidad_agua->temperatura = $request['temperatura'];
			$calidad_agua->ph = $request['ph'];
			$calidad_agua->amonio = $request['amonio'];
			$calidad_agua->nitrito = $request['nitrito'];
			$calidad_agua->nitrato = $request['nitrato'];
			$calidad_agua->otros = $request['otros'];
			$calidad_agua->save();
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
		$calidad_agua = CalidadAgua::findOrFail($id);
		$calidad_agua->update($request->all());
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
		CalidadAgua::destroy($id);
		// $cs = CalidadSiembra::where('id_calidad_parametros', $id)->delete();
		return 'eliminado';
	}

	public function filtroParametros(Request $request)
	{
		$c1 = "calidad_agua.id";
		$op1 = '!=';
		$c2 = '-1';
		$c3 = "calidad_agua.id";
		$op2 = '!=';
		$c4 = '-1';
		$c5 = "calidad_agua.id";
		$op3 = '!=';
		$c6 = '-1';
		
		if ($request['f_inicio_d'] != '-1') {
			$c1 = "fecha_parametro";
			$op1 = '>=';
			$c2 = $request['f_inicio_d'];
		}
		if ($request['f_inicio_h'] != '-1') {
			$c3 = "fecha_parametro";
			$op2 = '<=';
			$c4 = $request['f_inicio_h'];
		}
		if ($request['id_contenedor'] != '-1') {
			$c5 = "id_contenedor";
			$op3 = '=';
			$c6 = $request['id_contenedor'];
		}

		$calidad_agua = CalidadAgua::select('calidad_agua.id as id', 'contenedores.id as id_contenedor', '4_am', '4_pm', '7_am', '8_pm', '12_am', 'amonio', 'contenedor', 'fecha_parametro', 'nitrato', 'nitrito', 'otros', 'ph', 'temperatura')
			->join('contenedores', 'calidad_agua.id_contenedor', 'contenedores.id')
			->where($c1, $op1, $c2)
			->where($c3, $op2, $c4)
			->where($c5, $op3, $c6)
			->orderBy('fecha_parametro', 'desc')
			->get();

		// Formateo de variables
		$parametros_calidad = array();
		$val4am = 0;
		$val4pm = 0;
		$val7am = 0;
		$val8pm = 0;
		$val12am = 0;
		$valamo = 0;
		$valnit = 0;
		$valnat = 0;
		$valot = 0;
		$valph = 0;
		$valtem = 0;

		foreach ($calidad_agua as $cl) {
			if ($cl['4_am'] === NULL || $cl['4_am'] === '') {
				$val4am = '';
			} else {
				$val4am =  number_format($cl['4_am'], 2, ',', '');
			}
			if ($cl['4_pm'] === NULL || $cl['4_pm'] === '') {
				$val4pm = '';
			} else {
				$val4pm =  number_format($cl['4_pm'], 2, ',', '');
			}
			if ($cl['7_am'] === NULL || $cl['7_am'] === '') {
				$val7am = '';
			} else {
				$val7am =  number_format($cl['7_am'], 2, ',', '');
			}
			if ($cl['8_pm'] === NULL || $cl['8_pm'] === '') {
				$val8pm = '';
			} else {
				$val8pm =  number_format($cl['8_pm'], 2, ',', '');
			}
			if ($cl['12_am'] === NULL || $cl['12_am'] === '') {
				$val12am = '';
			} else {
				$val12am =  number_format($cl['12_am'], 2, ',', '');
			}
			if ($cl['amonio'] === NULL || $cl['amonio'] === '') {
				$valamo = '';
			} else {
				$valamo =  number_format($cl['amonio'], 2, ',', '');
			}
			if ($cl['nitrato'] === NULL || $cl['nitrato'] === '') {
				$valnat = '';
			} else {
				$valnat =  number_format($cl['nitrato'], 2, ',', '');
			}
			if ($cl['nitrito'] === NULL || $cl['nitrito'] === '') {
				$valnit = '';
			} else {
				$valnit =  number_format($cl['nitrito'], 2, ',', '');
			}
			if ($cl['otros'] === NULL || $cl['otros'] === '') {
				$valot = '';
			} else {
				$valot =  number_format($cl['otros'], 2, ',', '');
			}
			if ($cl['ph'] === NULL || $cl['ph'] === '') {
				$valph = '';
			} else {
				$valph =  number_format($cl['ph'], 2, ',', '');
			}
			if ($cl['temperatura'] === NULL || $cl['temperatura'] === '') {
				$valtem = '';
			} else {
				$valtem =  number_format($cl['temperatura'], 2, ',', '');
			}
			$parametros_calidad[] = array(
				'id' =>  $cl['id'],
				'id_contenedor' => $cl['id_contenedor'],
				'4_am' => $val4am,
				'4_pm' => $val4pm,
				'7_am' => $val7am,
				'8_pm' => $val8pm,
				'12_am' => $val12am,
				'amonio' => $valamo,
				'contenedor' => $cl['contenedor'],
				'fecha_parametro' => $cl['fecha_parametro'],
				'nitrato' => $valnat,
				'nitrito' => $valnit,
				'otros' => $valot,
				'ph' => $valph,
				'temperatura' => $valtem
			);
		}

		$promedios = array();
		$prom_12am = 0;
		$prom_4am = 0;
		$prom_7am = 0;
		$prom_4pm = 0;
		$prom_8pm = 0;
		$prom_temperatura = 0;
		$prom_ph = 0;
		$prom_amonio = 0;
		$prom_nitrito = 0;
		$prom_nitrato = 0;
		$prom_otros = 0;
		$count_12am = 0;
		$count_4am = 0;
		$count_7am = 0;
		$count_4pm = 0;
		$count_8pm = 0;
		$count_temperatura = 0;
		$count_ph = 0;
		$count_amonio = 0;
		$count_nitrito = 0;
		$count_nitrato = 0;
		$count_otros = 0;


		for ($i = 0; $i < count($calidad_agua); $i++) {
			if ($calidad_agua[$i]['12_am'] !== NULL && $calidad_agua[$i]['12_am'] !== '') {
				$count_12am += 1;
				$prom_12am += $calidad_agua[$i]['12_am'];
				$promedios['promedio_12_am'] = (round($prom_12am / $count_12am, 2));
				$promedios['promedio_12_am']  = number_format($promedios['promedio_12_am'], 2, ',', '');
			}
			if ($calidad_agua[$i]['4_am'] !== NULL && $calidad_agua[$i]['4_am'] !== '') {
				$count_4am += 1;
				$prom_4am += $calidad_agua[$i]['4_am'];
				$promedios['promedio_4_am'] = (round($prom_4am / $count_4am, 2));
				$promedios['promedio_4_am']  = number_format($promedios['promedio_4_am'], 2, ',', '');
			}
			if ($calidad_agua[$i]['7_am'] !== NULL && $calidad_agua[$i]['7_am'] !== '') {
				$count_7am += 1;
				$prom_7am += $calidad_agua[$i]['7_am'];
				$promedios['promedio_7_am'] = (round($prom_7am / $count_7am, 2));
				$promedios['promedio_7_am']  = number_format($promedios['promedio_7_am'], 2, ',', '');
			}
			if ($calidad_agua[$i]['4_pm'] !== NULL && $calidad_agua[$i]['4_pm'] !== '') {
				$count_4pm += 1;
				$prom_4pm += $calidad_agua[$i]['4_pm'];
				$promedios['promedio_4_pm'] = (round($prom_4pm / $count_4pm, 2));
				$promedios['promedio_4_pm']  = number_format($promedios['promedio_4_pm'], 2, ',', '');
			}
			if ($calidad_agua[$i]['8_pm'] !== NULL && $calidad_agua[$i]['8_pm'] !== '') {
				$count_8pm += 1;
				$prom_8pm += $calidad_agua[$i]['8_pm'];
				$promedios['promedio_8_pm'] = (round($prom_8pm / $count_8pm, 2));
				$promedios['promedio_8_pm']  = number_format($promedios['promedio_8_pm'], 2, ',', '');
			}
			if ($calidad_agua[$i]['temperatura'] !== NULL && $calidad_agua[$i]['temperatura'] !== '') {
				$count_temperatura += 1;
				$prom_temperatura += $calidad_agua[$i]['temperatura'];
				$promedios['promedio_temperatura'] = (round($prom_temperatura / $count_temperatura, 2));
				$promedios['promedio_temperatura']  = number_format($promedios['promedio_temperatura'], 2, ',', '');
			}
			if ($calidad_agua[$i]['ph'] !== NULL && $calidad_agua[$i]['ph'] !== '') {
				$count_ph += 1;
				$prom_ph += $calidad_agua[$i]['ph'];
				$promedios['promedio_ph'] = (round($prom_ph / $count_ph, 2));
				$promedios['promedio_ph']  = number_format($promedios['promedio_ph'], 2, ',', '');
			}
			if ($calidad_agua[$i]['amonio'] !== NULL && $calidad_agua[$i]['amonio'] !== '') {
				$count_amonio += 1;
				$prom_amonio += $calidad_agua[$i]['amonio'];
				$promedios['promedio_amonio'] = (round($prom_amonio / $count_amonio, 2));
				$promedios['promedio_amonio']  = number_format($promedios['promedio_amonio'], 2, ',', '');
			}
			if ($calidad_agua[$i]['nitrito'] !== NULL && $calidad_agua[$i]['nitrito'] !== '') {
				$count_nitrito += 1;
				$prom_nitrito += $calidad_agua[$i]['nitrito'];
				$promedios['promedio_nitrito'] = (round($prom_nitrito / $count_nitrito, 2));
				$promedios['promedio_nitrito']  = number_format($promedios['promedio_nitrito'], 2, ',', '');
			}

			if ($calidad_agua[$i]['nitrato'] !== NULL && $calidad_agua[$i]['nitrato'] !== '') {
				$count_nitrato += 1;
				$prom_nitrato += $calidad_agua[$i]['nitrato'];
				$promedios['promedio_nitrato'] = (round($prom_nitrato / $count_nitrato, 2));
				$promedios['promedio_nitrato']  = number_format($promedios['promedio_nitrato'], 2, ',', '');
			}

			if ($calidad_agua[$i]['otros'] !== NULL && $calidad_agua[$i]['otros'] !== '') {
				$count_otros += 1;
				$prom_otros += $calidad_agua[$i]['otros'];
				$promedios['promedio_otros'] = (round($prom_otros / $count_otros, 2));
				$promedios['promedio_otros']  = number_format($promedios['promedio_otros'], 2, ',', '');
			}
		}



		return ['calidad_agua' => $parametros_calidad, 'promedios' => $promedios];
		// return $calidad_agua;
	}
	public function filtroParametrosExcel(Request $request)
	{
		$c1 = "calidad_agua.id";
		$op1 = '!=';
		$c2 = '-1';
		$c3 = "calidad_agua.id";
		$op2 = '!=';
		$c4 = '-1';
		$c5 = "calidad_agua.id";
		$op3 = '!=';
		$c6 = '-1';
		// $c5 = "siembras.id"; $op3 = '!='; $c6 = '-1';

		// if($request['f_siembra']!='-1'){$c1="siembras.id"; $op1='='; $c2= $request['f_siembra'];}
		if ($request['f_inicio_d'] != '-1') {
			$c1 = "fecha_parametro";
			$op1 = '>=';
			$c2 = $request['f_inicio_d'];
		}
		if ($request['f_inicio_h'] != '-1') {
			$c3 = "fecha_parametro";
			$op2 = '<=';
			$c4 = $request['f_inicio_h'];
		}
		if ($request['id_contenedor'] != '-1') {
			$c5 = "id_contenedor";
			$op3 = '=';
			$c6 = $request['id_contenedor'];
		}

		$calidad_agua = CalidadAgua::select('calidad_agua.id as id', 'contenedores.id as id_contenedor', '4_am', '4_pm', '7_am', '8_pm', '12_am', 'amonio', 'contenedor', 'fecha_parametro', 'nitrato', 'nitrito', 'otros', 'ph', 'temperatura')
			->join('contenedores', 'calidad_agua.id_contenedor', 'contenedores.id')
			->where($c1, $op1, $c2)
			->where($c3, $op2, $c4)
			->where($c5, $op3, $c6)
			->orderBy('fecha_parametro', 'desc')
			->get();

		// Formateo de variables
		$parametros_calidad = array();
		$val4am = 0;
		$val4pm = 0;
		$val7am = 0;
		$val8pm = 0;
		$val12am = 0;
		$valamo = 0;
		$valnit = 0;
		$valnat = 0;
		$valot = 0;
		$valph = 0;
		$valtem = 0;

		foreach ($calidad_agua as $cl) {
			if ($cl['4_am'] === NULL || $cl['4_am'] === '') {
				$val4am = '';
			} else {
				$val4am =  number_format($cl['4_am'], 2, ',', '');
			}
			if ($cl['4_pm'] === NULL || $cl['4_pm'] === '') {
				$val4pm = '';
			} else {
				$val4pm =  number_format($cl['4_pm'], 2, ',', '');
			}
			if ($cl['7_am'] === NULL || $cl['7_am'] === '') {
				$val7am = '';
			} else {
				$val7am =  number_format($cl['7_am'], 2, ',', '');
			}
			if ($cl['8_pm'] === NULL || $cl['8_pm'] === '') {
				$val8pm = '';
			} else {
				$val8pm =  number_format($cl['8_pm'], 2, ',', '');
			}
			if ($cl['12_am'] === NULL || $cl['12_am'] === '') {
				$val12am = '';
			} else {
				$val12am =  number_format($cl['12_am'], 2, ',', '');
			}
			if ($cl['amonio'] === NULL || $cl['amonio'] === '') {
				$valamo = '';
			} else {
				$valamo =  number_format($cl['amonio'], 2, ',', '');
			}
			if ($cl['nitrato'] === NULL || $cl['nitrato'] === '') {
				$valnat = '';
			} else {
				$valnat =  number_format($cl['nitrato'], 2, ',', '');
			}
			if ($cl['nitrito'] === NULL || $cl['nitrito'] === '') {
				$valnit = '';
			} else {
				$valnit =  number_format($cl['nitrito'], 2, ',', '');
			}
			if ($cl['otros'] === NULL || $cl['otros'] === '') {
				$valot = '';
			} else {
				$valot =  number_format($cl['otros'], 2, ',', '');
			}
			if ($cl['ph'] === NULL || $cl['ph'] === '') {
				$valph = '';
			} else {
				$valph =  number_format($cl['ph'], 2, ',', '');
			}
			if ($cl['temperatura'] === NULL || $cl['temperatura'] === '') {
				$valtem = '';
			} else {
				$valtem =  number_format($cl['temperatura'], 2, ',', '');
			}
			$parametros_calidad[] = array(
				'id' =>  $cl['id'],
				'id_contenedor' => $cl['id_contenedor'],
				'4_am' => $val4am,
				'4_pm' => $val4pm,
				'7_am' => $val7am,
				'8_pm' => $val8pm,
				'12_am' => $val12am,
				'amonio' => $valamo,
				'contenedor' => $cl['contenedor'],
				'fecha_parametro' => $cl['fecha_parametro'],
				'nitrato' => $valnat,
				'nitrito' => $valnit,
				'otros' => $valot,
				'ph' => $valph,
				'temperatura' => $valtem
			);
		}

		$promedios = array();
		$prom_12am = 0;
		$prom_4am = 0;
		$prom_7am = 0;
		$prom_4pm = 0;
		$prom_8pm = 0;
		$prom_temperatura = 0;
		$prom_ph = 0;
		$prom_amonio = 0;
		$prom_nitrito = 0;
		$prom_nitrato = 0;
		$prom_otros = 0;
		$count_12am = 0;
		$count_4am = 0;
		$count_7am = 0;
		$count_4pm = 0;
		$count_8pm = 0;
		$count_temperatura = 0;
		$count_ph = 0;
		$count_amonio = 0;
		$count_nitrito = 0;
		$count_nitrato = 0;
		$count_otros = 0;


		for ($i = 0; $i < count($calidad_agua); $i++) {
			if ($calidad_agua[$i]['12_am'] !== NULL && $calidad_agua[$i]['12_am'] !== '') {
				$count_12am += 1;
				$prom_12am += $calidad_agua[$i]['12_am'];
				$promedios['promedio_12_am'] = (round($prom_12am / $count_12am, 2));
				$promedios['promedio_12_am']  = number_format($promedios['promedio_12_am'], 2, ',', '');
			}
			if ($calidad_agua[$i]['4_am'] !== NULL && $calidad_agua[$i]['4_am'] !== '') {
				$count_4am += 1;
				$prom_4am += $calidad_agua[$i]['4_am'];
				$promedios['promedio_4_am'] = (round($prom_4am / $count_4am, 2));
				$promedios['promedio_4_am']  = number_format($promedios['promedio_4_am'], 2, ',', '');
			}
			if ($calidad_agua[$i]['7_am'] !== NULL && $calidad_agua[$i]['7_am'] !== '') {
				$count_7am += 1;
				$prom_7am += $calidad_agua[$i]['7_am'];
				$promedios['promedio_7_am'] = (round($prom_7am / $count_7am, 2));
				$promedios['promedio_7_am']  = number_format($promedios['promedio_7_am'], 2, ',', '');
			}
			if ($calidad_agua[$i]['4_pm'] !== NULL && $calidad_agua[$i]['4_pm'] !== '') {
				$count_4pm += 1;
				$prom_4pm += $calidad_agua[$i]['4_pm'];
				$promedios['promedio_4_pm'] = (round($prom_4pm / $count_4pm, 2));
				$promedios['promedio_4_pm']  = number_format($promedios['promedio_4_pm'], 2, ',', '');
			}
			if ($calidad_agua[$i]['8_pm'] !== NULL && $calidad_agua[$i]['8_pm'] !== '') {
				$count_8pm += 1;
				$prom_8pm += $calidad_agua[$i]['8_pm'];
				$promedios['promedio_8_pm'] = (round($prom_8pm / $count_8pm, 2));
				$promedios['promedio_8_pm']  = number_format($promedios['promedio_8_pm'], 2, ',', '');
			}
			if ($calidad_agua[$i]['temperatura'] !== NULL && $calidad_agua[$i]['temperatura'] !== '') {
				$count_temperatura += 1;
				$prom_temperatura += $calidad_agua[$i]['temperatura'];
				$promedios['promedio_temperatura'] = (round($prom_temperatura / $count_temperatura, 2));
				$promedios['promedio_temperatura']  = number_format($promedios['promedio_temperatura'], 2, ',', '');
			}
			if ($calidad_agua[$i]['ph'] !== NULL && $calidad_agua[$i]['ph'] !== '') {
				$count_ph += 1;
				$prom_ph += $calidad_agua[$i]['ph'];
				$promedios['promedio_ph'] = (round($prom_ph / $count_ph, 2));
				$promedios['promedio_ph']  = number_format($promedios['promedio_ph'], 2, ',', '');
			}
			if ($calidad_agua[$i]['amonio'] !== NULL && $calidad_agua[$i]['amonio'] !== '') {
				$count_amonio += 1;
				$prom_amonio += $calidad_agua[$i]['amonio'];
				$promedios['promedio_amonio'] = (round($prom_amonio / $count_amonio, 2));
				$promedios['promedio_amonio']  = number_format($promedios['promedio_amonio'], 2, ',', '');
			}
			if ($calidad_agua[$i]['nitrito'] !== NULL && $calidad_agua[$i]['nitrito'] !== '') {
				$count_nitrito += 1;
				$prom_nitrito += $calidad_agua[$i]['nitrito'];
				$promedios['promedio_nitrito'] = (round($prom_nitrito / $count_nitrito, 2));
				$promedios['promedio_nitrito']  = number_format($promedios['promedio_nitrito'], 2, ',', '');
			}

			if ($calidad_agua[$i]['nitrato'] !== NULL && $calidad_agua[$i]['nitrato'] !== '') {
				$count_nitrato += 1;
				$prom_nitrato += $calidad_agua[$i]['nitrato'];
				$promedios['promedio_nitrato'] = (round($prom_nitrato / $count_nitrato, 2));
				$promedios['promedio_nitrato']  = number_format($promedios['promedio_nitrato'], 2, ',', '');
			}

			if ($calidad_agua[$i]['otros'] !== NULL && $calidad_agua[$i]['otros'] !== '') {
				$count_otros += 1;
				$prom_otros += $calidad_agua[$i]['otros'];
				$promedios['promedio_otros'] = (round($prom_otros / $count_otros, 2));
				$promedios['promedio_otros']  = number_format($promedios['promedio_otros'], 2, ',', '');
			}
		}



		return ['calidad_agua' => $parametros_calidad, 'promedios' => $promedios];
		// return $calidad_agua;
	}

	public function listadoParametrosContenedores()
	{
		$contenedores = Contenedor::select(
			'capacidad',
			'contenedores.id as id',
			'contenedores.estado as estado',
			'contenedor',
			'nombre_siembra',
			'siembras.id as id_siembra'
		)
			->leftJoin('siembras', 'contenedores.id', 'siembras.id_contenedor')
			->get();

		return $contenedores;
	}

	public function mostrarParametrosxContenedores($id)
	{
		//
		$calidad_agua = CalidadAgua::select(
			'calidad_agua.id as id',
			'contenedores.id as id_contenedor',
			'4_am',
			'4_pm',
			'7_am',
			'8_pm',
			'12_am',
			'amonio',
			'contenedor',
			'fecha_parametro',
			'nitrato',
			'nitrito',
			'otros',
			'ph',
			'temperatura'
		)
			->where('contenedores.id', '=', $id)
			->rightJoin('contenedores', 'calidad_agua.id_contenedor', 'contenedores.id')
			->orderBy('fecha_parametro', 'desc')
			->get();

		$val4am = 0;
		$val4pm = 0;
		$val7am = 0;
		$val8pm = 0;
		$val12am = 0;
		$valamo = 0;
		$valnit = 0;
		$valnat = 0;
		$valot = 0;
		$valph = 0;
		$valtem = 0;
		$parametros_calidad = array();

		foreach ($calidad_agua as $cl) {
			if ($cl['4_am'] === NULL || $cl['4_am'] === '') {
				$val4am = '';
			} else {
				$val4am =  number_format($cl['4_am'], 2, '.', '');
			}
			if ($cl['4_pm'] === NULL || $cl['4_pm'] === '') {
				$val4pm = '';
			} else {
				$val4pm =  number_format($cl['4_pm'], 2, '.', '');
			}
			if ($cl['7_am'] === NULL || $cl['7_am'] === '') {
				$val7am = '';
			} else {
				$val7am =  number_format($cl['7_am'], 2, '.', '');
			}
			if ($cl['8_pm'] === NULL || $cl['8_pm'] === '') {
				$val8pm = '';
			} else {
				$val8pm =  number_format($cl['8_pm'], 2, '.', '');
			}
			if ($cl['12_am'] === NULL || $cl['12_am'] === '') {
				$val12am = '';
			} else {
				$val12am =  number_format($cl['12_am'], 2, '.', '');
			}
			if ($cl['amonio'] === NULL || $cl['amonio'] === '') {
				$valamo = '';
			} else {
				$valamo =  number_format($cl['amonio'], 2, '.', '');
			}
			if ($cl['nitrato'] === NULL || $cl['nitrato'] === '') {
				$valnat = '';
			} else {
				$valnat =  number_format($cl['nitrato'], 2, '.', '');
			}
			if ($cl['nitrito'] === NULL || $cl['nitrito'] === '') {
				$valnit = '';
			} else {
				$valnit =  number_format($cl['nitrito'], 2, '.', '');
			}
			if ($cl['otros'] === NULL || $cl['otros'] === '') {
				$valot = '';
			} else {
				$valot =  number_format($cl['otros'], 2, '.', '');
			}
			if ($cl['ph'] === NULL || $cl['ph'] === '') {
				$valph = '';
			} else {
				$valph =  number_format($cl['ph'], 2, '.', '');
			}
			if ($cl['temperatura'] === NULL || $cl['temperatura'] === '') {
				$valtem = '';
			} else {
				$valtem =  number_format($cl['temperatura'], 2, '.', '');
			}

			$parametros_calidad[] = array(
				'id' =>  $cl['id'],
				'id_contenedor' => $cl['id_contenedor'],
				'4_am' => $val4am,
				'4_pm' => $val4pm,
				'7_am' => $val7am,
				'8_pm' => $val8pm,
				'12_am' => $val12am,
				'amonio' => $valamo,
				'contenedor' => $cl['contenedor'],
				'fecha_parametro' => $cl['fecha_parametro'],
				'nitrato' => $valnat,
				'nitrito' => $valnit,
				'otros' => $valot,
				'ph' => $valph,
				'temperatura' => $valtem
			);
		}

		$promedios = array();
		$prom_12am = 0;
		$prom_4am = 0;
		$prom_7am = 0;
		$prom_4pm = 0;
		$prom_8pm = 0;
		$prom_temperatura = 0;
		$prom_ph = 0;
		$prom_amonio = 0;
		$prom_nitrito = 0;
		$prom_nitrato = 0;
		$prom_otros = 0;
		$count_12am = 0;
		$count_4am = 0;
		$count_7am = 0;
		$count_4pm = 0;
		$count_8pm = 0;
		$count_temperatura = 0;
		$count_ph = 0;
		$count_amonio = 0;
		$count_nitrito = 0;
		$count_nitrato = 0;
		$count_otros = 0;

		for ($i = 0; $i < count($calidad_agua); $i++) {
			if ($calidad_agua[$i]['12_am'] !== NULL && $calidad_agua[$i]['12_am'] !== '') {
				$count_12am += 1;
				$prom_12am += $calidad_agua[$i]['12_am'];
				$promedios['promedio_12_am'] = (round($prom_12am / $count_12am, 2));
				$promedios['promedio_12_am']  = number_format($promedios['promedio_12_am'], 2, ',', '');
			} else {
				$promedios['promedio_12_am'] = '';
			}
			if ($calidad_agua[$i]['4_am'] !== NULL && $calidad_agua[$i]['4_am'] !== '') {
				$count_4am += 1;
				$prom_4am += $calidad_agua[$i]['4_am'];
				$promedios['promedio_4_am'] = (round($prom_4am / $count_4am, 2));
				$promedios['promedio_4_am']  = number_format($promedios['promedio_4_am'], 2, ',', '');
			} else {
				$calidad_agua[$i]['4_am'] = '';
			}
			if ($calidad_agua[$i]['7_am'] !== NULL && $calidad_agua[$i]['7_am'] !== '') {
				$count_7am += 1;
				$prom_7am += $calidad_agua[$i]['7_am'];
				$promedios['promedio_7_am'] = (round($prom_7am / $count_7am, 2));
				$promedios['promedio_7_am']  = number_format($promedios['promedio_7_am'], 2, ',', '');
			}
			if ($calidad_agua[$i]['4_pm']  !== NULL && $calidad_agua[$i]['4_pm'] !== '') {
				$count_4pm += 1;
				$prom_4pm += $calidad_agua[$i]['4_pm'];
				$promedios['promedio_4_pm'] = (round($prom_4pm / $count_4pm, 2));
				$promedios['promedio_4_pm']  = number_format($promedios['promedio_4_pm'], 2, ',', '');
			}
			if ($calidad_agua[$i]['8_pm'] !== NULL && $calidad_agua[$i]['8_pm'] !== '') {
				$count_8pm += 1;
				$prom_8pm += $calidad_agua[$i]['8_pm'];
				$promedios['promedio_8_pm'] = (round($prom_8pm / $count_8pm, 2));
				$promedios['promedio_8_pm']  = number_format($promedios['promedio_8_pm'], 2, ',', '');
			}
			if ($calidad_agua[$i]['temperatura'] !== NULL && $calidad_agua[$i]['temperatura'] !== '') {
				$count_temperatura += 1;
				$prom_temperatura += $calidad_agua[$i]['temperatura'];
				$promedios['promedio_temperatura'] = (round($prom_temperatura / $count_temperatura, 2));
				$promedios['promedio_temperatura']  = number_format($promedios['promedio_temperatura'], 2, ',', '');
			}
			if ($calidad_agua[$i]['ph'] !== NULL && $calidad_agua[$i]['ph'] !== '') {
				$count_ph += 1;
				$prom_ph += $calidad_agua[$i]['ph'];
				$promedios['promedio_ph'] = (round($prom_ph / $count_ph, 2));
				$promedios['promedio_ph']  = number_format($promedios['promedio_ph'], 2, ',', '');
			}
			if ($calidad_agua[$i]['amonio'] !== NULL && $calidad_agua[$i]['amonio'] !== '') {
				$count_amonio += 1;
				$prom_amonio += $calidad_agua[$i]['amonio'];
				$promedios['promedio_amonio'] = (round($prom_amonio / $count_amonio, 2));
				$promedios['promedio_amonio']  = number_format($promedios['promedio_amonio'], 2, ',', '');
			}
			if ($calidad_agua[$i]['nitrito'] !== NULL && $calidad_agua[$i]['nitrito'] !== '') {
				$count_nitrito += 1;
				$prom_nitrito += $calidad_agua[$i]['nitrito'];
				$promedios['promedio_nitrito'] = (round($prom_nitrito / $count_nitrito, 2));
				$promedios['promedio_nitrito']  = number_format($promedios['promedio_nitrito'], 2, ',', '');
			}

			if ($calidad_agua[$i]['nitrato'] !== NULL && $calidad_agua[$i]['nitrato'] !== '') {
				$count_nitrato += 1;
				$prom_nitrato += $calidad_agua[$i]['nitrato'];
				$promedios['promedio_nitrato'] = (round($prom_nitrato / $count_nitrato, 2));
			}

			if ($calidad_agua[$i]['otros'] !== NULL && $calidad_agua[$i]['otros'] !== '') {
				$count_otros += 1;
				$prom_otros += $calidad_agua[$i]['otros'];
				$promedios['promedio_otros'] = (round($prom_otros / $count_otros, 2));
				$promedios['promedio_otros']  = number_format($promedios['promedio_otros'], 2, ',', '');
			}
		}

		return ['calidad_agua' => $parametros_calidad, 'promedios' => $promedios];
	}

	public function mostrarParametrosxContenedoresExcel($id)
	{
		//
		$calidad_agua = CalidadAgua::select(
			'calidad_agua.id as id',
			'contenedores.id as id_contenedor',
			'4_am',
			'4_pm',
			'7_am',
			'8_pm',
			'12_am',
			'amonio',
			'contenedor',
			'fecha_parametro',
			'nitrato',
			'nitrito',
			'otros',
			'ph',
			'temperatura'
		)
			->where('contenedores.id', '=', $id)
			->rightJoin('contenedores', 'calidad_agua.id_contenedor', 'contenedores.id')
			->orderBy('fecha_parametro', 'desc')
			->get();

		$val4am = 0;
		$val4pm = 0;
		$val7am = 0;
		$val8pm = 0;
		$val12am = 0;
		$valamo = 0;
		$valnit = 0;
		$valnat = 0;
		$valot = 0;
		$valph = 0;
		$valtem = 0;
		$parametros_calidad = array();

		foreach ($calidad_agua as $cl) {
			if ($cl['4_am'] === NULL || $cl['4_am'] === '') {
				$val4am = '';
			} else {
				$val4am =  number_format($cl['4_am'], 2, ',', '');
			}
			if ($cl['4_pm'] === NULL || $cl['4_pm'] === '') {
				$val4pm = '';
			} else {
				$val4pm =  number_format($cl['4_pm'], 2, ',', '');
			}
			if ($cl['7_am'] === NULL || $cl['7_am'] === '') {
				$val7am = '';
			} else {
				$val7am =  number_format($cl['7_am'], 2, ',', '');
			}
			if ($cl['8_pm'] === NULL || $cl['8_pm'] === '') {
				$val8pm = '';
			} else {
				$val8pm =  number_format($cl['8_pm'], 2, ',', '');
			}
			if ($cl['12_am'] === NULL || $cl['12_am'] === '') {
				$val12am = '';
			} else {
				$val12am =  number_format($cl['12_am'], 2, ',', '');
			}
			if ($cl['amonio'] === NULL || $cl['amonio'] === '') {
				$valamo = '';
			} else {
				$valamo =  number_format($cl['amonio'], 2, ',', '');
			}
			if ($cl['nitrato'] === NULL || $cl['nitrato'] === '') {
				$valnat = '';
			} else {
				$valnat =  number_format($cl['nitrato'], 2, ',', '');
			}
			if ($cl['nitrito'] === NULL || $cl['nitrito'] === '') {
				$valnit = '';
			} else {
				$valnit =  number_format($cl['nitrito'], 2, ',', '');
			}
			if ($cl['otros'] === NULL || $cl['otros'] === '') {
				$valot = '';
			} else {
				$valot =  number_format($cl['otros'], 2, ',', '');
			}
			if ($cl['ph'] === NULL || $cl['ph'] === '') {
				$valph = '';
			} else {
				$valph =  number_format($cl['ph'], 2, ',', '');
			}
			if ($cl['temperatura'] === NULL || $cl['temperatura'] == '') {
				$valtem = '';
			} else {
				$valtem =  number_format($cl['temperatura'], 2, ',', '');
			}

			$parametros_calidad[] = array(
				'id' =>  $cl['id'],
				'id_contenedor' => $cl['id_contenedor'],
				'4_am' => $val4am,
				'4_pm' => $val4pm,
				'7_am' => $val7am,
				'8_pm' => $val8pm,
				'12_am' => $val12am,
				'amonio' => $valamo,
				'contenedor' => $cl['contenedor'],
				'fecha_parametro' => $cl['fecha_parametro'],
				'nitrato' => $valnat,
				'nitrito' => $valnit,
				'otros' => $valot,
				'ph' => $valph,
				'temperatura' => $valtem
			);
		}

		$promedios = array();
		$prom_12am = 0;
		$prom_4am = 0;
		$prom_7am = 0;
		$prom_4pm = 0;
		$prom_8pm = 0;
		$prom_temperatura = 0;
		$prom_ph = 0;
		$prom_amonio = 0;
		$prom_nitrito = 0;
		$prom_nitrato = 0;
		$prom_otros = 0;
		$count_12am = 0;
		$count_4am = 0;
		$count_7am = 0;
		$count_4pm = 0;
		$count_8pm = 0;
		$count_temperatura = 0;
		$count_ph = 0;
		$count_amonio = 0;
		$count_nitrito = 0;
		$count_nitrato = 0;
		$count_otros = 0;

		for ($i = 0; $i < count($calidad_agua); $i++) {
			if ($calidad_agua[$i]['12_am'] !== NULL && $calidad_agua[$i]['12_am'] !== '') {
				$count_12am += 1;
				$prom_12am += $calidad_agua[$i]['12_am'];
				$promedios['promedio_12_am'] = (round($prom_12am / $count_12am, 2));
				$promedios['promedio_12_am']  = number_format($promedios['promedio_12_am'], 2, ',', '');
			} else {
				$promedios['promedio_12_am'] = '';
			}
			if ($calidad_agua[$i]['4_am'] !== NULL && $calidad_agua[$i]['4_am'] !== '') {
				$count_4am += 1;
				$prom_4am += $calidad_agua[$i]['4_am'];
				$promedios['promedio_4_am'] = (round($prom_4am / $count_4am, 2));
				$promedios['promedio_4_am']  = number_format($promedios['promedio_4_am'], 2, ',', '');
			} else {
				$calidad_agua[$i]['4_am'] = '';
			}
			if ($calidad_agua[$i]['7_am'] !== NULL && $calidad_agua[$i]['7_am'] !== '') {
				$count_7am += 1;
				$prom_7am += $calidad_agua[$i]['7_am'];
				$promedios['promedio_7_am'] = (round($prom_7am / $count_7am, 2));
				$promedios['promedio_7_am']  = number_format($promedios['promedio_7_am'], 2, ',', '');
			}
			if ($calidad_agua[$i]['4_pm']  !== NULL && $calidad_agua[$i]['4_pm'] !== '') {
				$count_4pm += 1;
				$prom_4pm += $calidad_agua[$i]['4_pm'];
				$promedios['promedio_4_pm'] = (round($prom_4pm / $count_4pm, 2));
				$promedios['promedio_4_pm']  = number_format($promedios['promedio_4_pm'], 2, ',', '');
			}
			if ($calidad_agua[$i]['8_pm'] !== NULL && $calidad_agua[$i]['8_pm'] !== '') {
				$count_8pm += 1;
				$prom_8pm += $calidad_agua[$i]['8_pm'];
				$promedios['promedio_8_pm'] = (round($prom_8pm / $count_8pm, 2));
				$promedios['promedio_8_pm']  = number_format($promedios['promedio_8_pm'], 2, ',', '');
			}
			if ($calidad_agua[$i]['temperatura'] !== NULL && $calidad_agua[$i]['temperatura'] !== '') {
				$count_temperatura += 1;
				$prom_temperatura += $calidad_agua[$i]['temperatura'];
				$promedios['promedio_temperatura'] = (round($prom_temperatura / $count_temperatura, 2));
				$promedios['promedio_temperatura']  = number_format($promedios['promedio_temperatura'], 2, ',', '');
			}
			if ($calidad_agua[$i]['ph'] !== NULL && $calidad_agua[$i]['ph'] !== '') {
				$count_ph += 1;
				$prom_ph += $calidad_agua[$i]['ph'];
				$promedios['promedio_ph'] = (round($prom_ph / $count_ph, 2));
				$promedios['promedio_ph']  = number_format($promedios['promedio_ph'], 2, ',', '');
			}
			if ($calidad_agua[$i]['amonio'] !== NULL && $calidad_agua[$i]['amonio'] !== '') {
				$count_amonio += 1;
				$prom_amonio += $calidad_agua[$i]['amonio'];
				$promedios['promedio_amonio'] = (round($prom_amonio / $count_amonio, 2));
				$promedios['promedio_amonio']  = number_format($promedios['promedio_amonio'], 2, ',', '');
			}
			if ($calidad_agua[$i]['nitrito'] !== NULL && $calidad_agua[$i]['nitrito'] !== '') {
				$count_nitrito += 1;
				$prom_nitrito += $calidad_agua[$i]['nitrito'];
				$promedios['promedio_nitrito'] = (round($prom_nitrito / $count_nitrito, 2));
				$promedios['promedio_nitrito']  = number_format($promedios['promedio_nitrito'], 2, ',', '');
			}

			if ($calidad_agua[$i]['nitrato'] !== NULL && $calidad_agua[$i]['nitrato'] !== '') {
				$count_nitrato += 1;
				$prom_nitrato += $calidad_agua[$i]['nitrato'];
				$promedios['promedio_nitrato'] = (round($prom_nitrato / $count_nitrato, 2));
			}

			if ($calidad_agua[$i]['otros'] !== NULL && $calidad_agua[$i]['otros'] !== '') {
				$count_otros += 1;
				$prom_otros += $calidad_agua[$i]['otros'];
				$promedios['promedio_otros'] = (round($prom_otros / $count_otros, 2));
				$promedios['promedio_otros']  = number_format($promedios['promedio_otros'], 2, ',', '');
			}
		}

		return ['calidad_agua' => $parametros_calidad, 'promedios' => $promedios];
	}
}
