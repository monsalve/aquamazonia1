<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\RecursoNecesario;
use App\RecursoSiembra;

use App\Recursos;
use App\Siembra;
use App\Actividad;

use Illuminate\Support\Facades\Auth;

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
		$minutos_hombre = Recursos::select()->where('recurso', 'Minutos hombre')->orWhere('recurso', 'Minuto hombre')->orWhere('recurso', 'Minutos')->first();

		$recursosNecesarios = RecursoNecesario::orderBy('fecha_ra', 'desc')
			->join('recursos_siembras', 'recursos_necesarios.id', 'recursos_siembras.id_registro')
			->leftJoin('recursos', 'recursos_necesarios.id_recurso', 'recursos.id')
			->join('siembras', 'recursos_siembras.id_siembra', 'siembras.id')
			->join('actividades', 'recursos_necesarios.tipo_actividad', 'actividades.id')
			->where('tipo_actividad', '!=', '1')
			->where('estado', 1)
			->select('recursos_necesarios.id as id', 'cantidad_recurso', 'cant_manana', 'cant_tarde', 'id_recurso', 'id_alimento', 'recursos_siembras.id_siembra', 'actividad', 'conv_alimenticia', 'costo', 'recursos_necesarios.detalles', 'tipo_actividad', 'recurso', 'nombre_siembra', 'minutos_hombre', 'fecha_ra')
			->paginate(20);

		$promedioRecursos = array();
		$summh = 0;
		$sumtmh = 0;
		$sumcr = 0;
		$sumc = 0;
		$sumctr = 0;

		if (count($recursosNecesarios) > 0) {
			for ($i = 0; $i < count($recursosNecesarios); $i++) {
				$recursosNecesarios[$i]->costo_total_recurso = $recursosNecesarios[$i]->cantidad_recurso * $recursosNecesarios[$i]->costo;
				$recursosNecesarios[$i]->total_minutos_hombre = $recursosNecesarios[$i]->minutos_hombre * $minutos_hombre->costo;
				$summh += $recursosNecesarios[$i]->minutos_hombre;
				$sumtmh += $recursosNecesarios[$i]->total_minutos_hombre;
				$sumcr += $recursosNecesarios[$i]->cantidad_recurso;
				$sumc += $recursosNecesarios[$i]->costo;
				$sumctr += $recursosNecesarios[$i]->costo_total_recurso;

				$recursosNecesarios[$i]->costo_total_recurso = number_format($recursosNecesarios[$i]->costo_total_recurso, 2, ',', '');
			}
			$promedioRecursos['tmh'] = $summh;
			$promedioRecursos['ttmh'] = $sumtmh;
			$promedioRecursos['tcr'] = $sumcr;
			$promedioRecursos['tc'] = $sumc;
			$promedioRecursos['ctr'] = $sumctr;

			$promedioRecursos['tc'] = number_format($promedioRecursos['tc'], 2, ',', '');
			$promedioRecursos['ctr'] = number_format($promedioRecursos['ctr'], 2, ',', '');
		}

		return [
			'recursosNecesarios' => $recursosNecesarios,
			'promedioRecursos' => $promedioRecursos,
			'pagination' => [
				'total'        => $recursosNecesarios->total(),
				'current_page' => $recursosNecesarios->currentPage(),
				'per_page'     => $recursosNecesarios->perPage(),
				'last_page'    => $recursosNecesarios->lastPage(),
				'from'         => $recursosNecesarios->firstItem(),
				'to'           => $recursosNecesarios->lastItem(),
			]
		];
	}
	public function  alimentacion(Request $request)
	{
		//
		$minutos_hombre = Recursos::select()->where('recurso', 'Minutos hombre')->orWhere('recurso', 'Minuto hombre')->orWhere('recurso', 'Minutos')->first();
		$recursosNecesarios = RecursoNecesario::orderBy('fecha_ra', 'desc')
			->join('recursos_siembras', 'recursos_necesarios.id', 'recursos_siembras.id_registro')
			->join('alimentos', 'recursos_necesarios.id_alimento', 'alimentos.id')
			->join('siembras', 'recursos_siembras.id_siembra', 'siembras.id')
			->join('actividades', 'recursos_necesarios.tipo_actividad', 'actividades.id')
			->where('tipo_actividad', '=', '1')
			->where('estado', 1)
			->paginate(20);

		$promedioRecursos = array();
		$summh = 0;
		$cantm = 0;
		$cantt = 0;
		$alid = 0;
		$coskg = 0;
		$cta = 0;
		$icb = 0;

		if (count($recursosNecesarios) > 0) {
			for ($i = 0; $i < count($recursosNecesarios); $i++) {
				$recursosNecesarios[$i]->costo_total_alimento = ($recursosNecesarios[$i]->cant_tarde + $recursosNecesarios[$i]->cant_manana) * $recursosNecesarios[$i]->costo_kg;
				$recursosNecesarios[$i]->total_minutos_hombre = $recursosNecesarios[$i]->minutos_hombre * $minutos_hombre->costo;
				$recursosNecesarios[$i]->alimento_dia = $recursosNecesarios[$i]->cant_tarde + $recursosNecesarios[$i]->cant_manana;
				if ($recursosNecesarios[$i]->conv_alimenticia > 0) {
					$recursosNecesarios[$i]->incr_bio_acum_conver = $recursosNecesarios[$i]->alimento_dia / $recursosNecesarios[$i]->conv_alimenticia;
					$recursosNecesarios[$i]->conv_alimenticia = number_format($recursosNecesarios[$i]->conv_alimenticia, 2, ',', '');
				}
				$summh += $recursosNecesarios[$i]->minutos_hombre;
				$cantm += $recursosNecesarios[$i]->cant_manana;
				$cantt += $recursosNecesarios[$i]->cant_tarde;
				$alid += $recursosNecesarios[$i]->alimento_dia;
				$coskg += $recursosNecesarios[$i]->costo_kg;
				$cta += $recursosNecesarios[$i]->costo_total_alimento;
				$icb += $recursosNecesarios[$i]->incr_bio_acum_conver;
				$recursosNecesarios[$i]->incr_bio_acum_conver = number_format($recursosNecesarios[$i]->incr_bio_acum_conver, 2, ',', '');
			}
			$promedioRecursos['tmh'] = $summh;
			$promedioRecursos['cman'] = $cantm;
			$promedioRecursos['ctar'] = $cantt;
			$promedioRecursos['alid'] = $alid;
			$promedioRecursos['coskg'] = $coskg;
			$promedioRecursos['cta'] = $cta;
			$icb = number_format($icb, 2, ',', '');
			$promedioRecursos['icb'] = $icb;
		}
		// print_r($recursosNecesarios);

		return [
			'recursosNecesarios' => $recursosNecesarios,
			'promedioRecursos' => $promedioRecursos,
			'pagination' => [
				'total'        => $recursosNecesarios->total(),
				'current_page' => $recursosNecesarios->currentPage(),
				'per_page'     => $recursosNecesarios->perPage(),
				'last_page'    => $recursosNecesarios->lastPage(),
				'from'         => $recursosNecesarios->firstItem(),
				'to'           => $recursosNecesarios->lastItem(),
			],
		];
	}
	public function siembraxAlimentacion($id)
	{
		//
		$minutos_hombre = Recursos::select()->where('recurso', 'Minutos hombre')->orWhere('recurso', 'Minuto hombre')->orWhere('recurso', 'Minutos')->first();
		$recursosNecesarios = RecursoNecesario::orderBy('fecha_ra', 'desc')
			->join('recursos_siembras', 'recursos_necesarios.id', 'recursos_siembras.id_registro')
			->join('alimentos', 'recursos_necesarios.id_alimento', 'alimentos.id')
			->join('siembras', 'recursos_siembras.id_siembra', 'siembras.id')
			->join('actividades', 'recursos_necesarios.tipo_actividad', 'actividades.id')
			->where('id_siembra', '=', $id)
			->where('tipo_actividad', '=', '1')
			->get();
		if (count($recursosNecesarios) > 0) {
			for ($i = 0; $i < count($recursosNecesarios); $i++) {
				$recursosNecesarios[$i]->costo_total_alimento = ($recursosNecesarios[$i]->cant_tarde + $recursosNecesarios[$i]->cant_manana) * $recursosNecesarios[$i]->costo_kg;
				$recursosNecesarios[$i]->total_minutos_hombre = $recursosNecesarios[$i]->minutos_hombre * $minutos_hombre->costo;
				$recursosNecesarios[$i]->alimento_dia = $recursosNecesarios[$i]->cant_tarde + $recursosNecesarios[$i]->cant_manana;
			}
		}

		return ['recursosNecesarios' => $recursosNecesarios];
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
		$c_alim = RecursoNecesario::select()->orderBy('id', 'desc')->first();
		// print_r($c_alim->conv_alimenticia);

		$recursoNecesario = new RecursoNecesario();
		$recursoNecesario->id_recurso = $request['id_recurso'];
		$recursoNecesario->id_alimento =  $request['id_alimento'];
		$recursoNecesario->tipo_actividad = $request['tipo_actividad'];
		$recursoNecesario->fecha_ra = $request['fecha_ra'];
		$recursoNecesario->minutos_hombre = $request['minutos_hombre'];
		$recursoNecesario->horas_hombre = ($request['minutos_hombre'] / 60);
		$recursoNecesario->cantidad_recurso = $request['cantidad_recurso'];
		$recursoNecesario->cant_manana = $request['cant_manana'];
		$recursoNecesario->cant_tarde = $request['cant_tarde'];
		if ($request['conv_alimenticia'] == '') {
			$recursoNecesario->conv_alimenticia = $c_alim->conv_alimenticia;
		} else {
			$recursoNecesario->conv_alimenticia = $request['conv_alimenticia'];
		}
		$recursoNecesario->detalles = $request['detalles'];
		$recursoNecesario->save();

		if ($request['tipo_actividad'] == '1') {
			if (!is_array($request['id_siembra'])) {
				$siembras = Siembra::findOrFail($request['id_siembra']);
				$siembras->fecha_alimento = $request['fecha_ra'];
				$siembras->save();

				$recursoSiembra = new RecursoSiembra();
				$recursoSiembra->id_registro = $recursoNecesario->id;
				$recursoSiembra->id_siembra = $request['id_siembra'];
				$recursoSiembra->save();
			} else {
				foreach ($request->id_siembra as $siembra) {
					$siembras = Siembra::findOrFail($siembra);
					$siembras->fecha_alimento = $request['fecha_ra'];
					$siembras->save();

					$recursoSiembra = new RecursoSiembra();
					$recursoSiembra->id_registro = $recursoNecesario->id;
					$recursoSiembra->id_siembra = $siembra;
					$recursoSiembra->save();
				}
			}
		} else {
			foreach ($request->id_siembra as $siembra) {
				$recursoSiembra = new RecursoSiembra();
				$recursoSiembra->id_registro = $recursoNecesario->id;
				$recursoSiembra->id_siembra = $siembra;
				$recursoSiembra->save();
			}
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
		$minutos =  $request['minutos_hombre'] / 60;
		$recursoNecesario = RecursoNecesario::findOrFail($id);
		$recursoNecesario->update([
			'cant_manana' => $request['cant_manana'],
			'cant_tarde' => $request['cant_tarde'],
			'id_alimento' => $request['id_alimento'],
			'id_recurso' => $request['id_recurso'],
			'detalles' => $request['detalles'],
			'minutos_hombre' => $request['minutos_hombre'],
			'horas_hombre' => $minutos,
			'cantidad_recurso' => $request['cantidad_recurso'],
		]);

		return ['recursoNecesario' => $recursoNecesario];
		// return 'ok';
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
		RecursoNecesario::destroy($id);
		$rxs = RecursoSiembra::where('id_registro', $id)->delete();
		return 'eliminado';
	}
	public function searchResults(Request $request)
	{

		$minutos_hombre = Recursos::select()->where('recurso', 'Minutos hombre')->orWhere('recurso', 'Minuto hombre')->orWhere('recurso', 'Minutos')->first();

		$tipo_actividad = "recursos_necesarios.id";
		$op1 = "!=";
		$c2 = "-1";
		$c3 = "recursos_necesarios.id";
		$op2 = "!=";
		$c4 = "-3";
		$c5 = "recursos_necesarios.id";
		$op3 = "!=";
		$c6 = "-1";
		$c7 = "recursos_necesarios.id";
		$op4 = "!=";
		$c8 = "-1";
		$c9 = "recursos_necesarios.id";
		$op5 = "!=";
		$c10 = "-1";
		$c11 = 'recursos_necesarios.id';
		$op6 = '!=';
		$c12 = '-1';
		$c13 = 'recursos_necesarios.id';
		$op7 = '!=';
		$c14 = '-1';

		if ($request['tipo_actividad'] != '-1') {
			$tipo_actividad = "tipo_actividad";
			$op1 = '=';
			$id_tipo_actividad = $request['tipo_actividad'];
		} elseif ($request['tipo_actividad'] == '-1') {
			$tipo_actividad = "tipo_actividad";
			$op1 = '!=';
			$id_tipo_actividad = '1';
		}

		if ($request['fecha_ra1'] != '-3') {
			$c3 = "fecha_ra";
			$op2 = '>=';
			$c4 = $request['fecha_ra1'];
		}
		if ($request['fecha_ra2'] != '-1') {
			$c5 = "fecha_ra";
			$op3 = '<=';
			$c6 = $request['fecha_ra2'];
		}
		if ($request['f_siembra'] != '-1') {
			$c7 = "siembras.id";
			$op4 = '=';
			$c8 = $request['f_siembra'];
		}
		if (isset($request['alimento_s']) && $request['alimento_s'] != '-1') {
			$c9 = "id_alimento";
			$op5 = '=';
			$c10 = $request['alimento_s'];
		}
		if (isset($request['recurso_s']) &&  ($request['recurso_s'] != '-1')) {
			$c11 = "id_recurso";
			$op6 = '=';
			$c12 = $request['recurso_s'];
		}
		if ($request['f_siembra'] != '-1') {
			$c13 = "siembras.id";
			$op7 = '=';
			$c14 = $request['f_siembra'];
		}

		$recursosNecesarios = RecursoNecesario::orderBy('fecha_ra', 'desc')
			->join('recursos_siembras', 'recursos_necesarios.id', 'recursos_siembras.id_registro');

		if ($request['tipo_actividad'] == 1) {
			$recursosNecesarios = 	$recursosNecesarios->join('alimentos', 'recursos_necesarios.id_alimento', 'alimentos.id');
		} else {
			$recursosNecesarios = 	$recursosNecesarios->join('recursos', 'recursos_necesarios.id_recurso', 'recursos.id');
		}

		$recursosNecesarios = 	$recursosNecesarios->join('siembras', 'recursos_siembras.id_siembra', 'siembras.id')
			->join('actividades', 'recursos_necesarios.tipo_actividad', 'actividades.id')
			// ->select('recursos_necesarios.id as id', 'cantidad_recurso', 'cant_manana', 'cant_tarde', 'id_recurso', 'id_alimento', 'recursos_siembras.id_siembra', 'actividad', 'conv_alimenticia', 'costo', 'recursos_necesarios.detalles', 'tipo_actividad', 'recurso', 'nombre_siembra', 'minutos_hombre', 'fecha_ra', 'alimento')
			->where($tipo_actividad, $op1, $id_tipo_actividad)
			->where($c3, $op2, $c4)
			->where($c5, $op3, $c6)
			->where($c7, $op4, $c8)
			->where($c9, $op5, $c10)
			->where($c11, $op6, $c12)
			->where($c13, $op7, $c14);


		if ($request['see_all']) {
			$recursosNecesarios = $recursosNecesarios->get();
		} else {
			$recursosNecesarios = $recursosNecesarios->paginate(20);
		}

		$promedioRecursos = array();
		$summh = 0;
		$sumtmh = 0;
		$sumcr = 0;
		$sumc = 0;
		$sumctr = 0;
		$cantm = 0;
		$cantt = 0;
		$alid = 0;
		$coskg = 0;
		$cta = 0;
		$icb = 0;

		if (count($recursosNecesarios) > 0) {
			for ($i = 0; $i < count($recursosNecesarios); $i++) {

				$recursosNecesarios[$i]->costo_total_recurso = $recursosNecesarios[$i]->cantidad_recurso * $recursosNecesarios[$i]->costo;
				$recursosNecesarios[$i]->total_minutos_hombre = $recursosNecesarios[$i]->minutos_hombre * $minutos_hombre->costo;

				$recursosNecesarios[$i]->costo_total_alimento = ($recursosNecesarios[$i]->cant_tarde + $recursosNecesarios[$i]->cant_manana) * $recursosNecesarios[$i]->costo_kg;
				$recursosNecesarios[$i]->alimento_dia = $recursosNecesarios[$i]->cant_tarde + $recursosNecesarios[$i]->cant_manana;
				if ($recursosNecesarios[$i]->conv_alimenticia > 0) {
					$recursosNecesarios[$i]->incr_bio_acum_conver = $recursosNecesarios[$i]->alimento_dia / $recursosNecesarios[$i]->conv_alimenticia;
					$recursosNecesarios[$i]->conv_alimenticia = number_format($recursosNecesarios[$i]->conv_alimenticia, 2, ',', '');
				}

				$summh += $recursosNecesarios[$i]->minutos_hombre;
				$sumtmh += $recursosNecesarios[$i]->total_minutos_hombre;
				$sumcr += $recursosNecesarios[$i]->cantidad_recurso;
				$sumc += $recursosNecesarios[$i]->costo;
				$sumctr += $recursosNecesarios[$i]->costo_total_recurso;
				$cantm += $recursosNecesarios[$i]->cant_manana;
				$cantt += $recursosNecesarios[$i]->cant_tarde;
				$alid += $recursosNecesarios[$i]->alimento_dia;
				$coskg += $recursosNecesarios[$i]->costo_kg;
				$cta += $recursosNecesarios[$i]->costo_total_alimento;
				$icb += $recursosNecesarios[$i]->incr_bio_acum_conver;
				$recursosNecesarios[$i]->incr_bio_acum_conver = number_format($recursosNecesarios[$i]->incr_bio_acum_conver, 2, ',', '');
				$recursosNecesarios[$i]->costo_total_recurso = number_format($recursosNecesarios[$i]->costo_total_recurso, 2, ',', '');
			}
			$promedioRecursos['tmh'] = $summh;
			$promedioRecursos['ttmh'] = $sumtmh;
			$promedioRecursos['tcr'] = $sumcr;
			$promedioRecursos['tc'] = $sumc;
			$promedioRecursos['ctr'] = $sumctr;
			$promedioRecursos['cman'] = $cantm;
			$promedioRecursos['ctar'] = $cantt;
			$promedioRecursos['alid'] = $alid;
			$promedioRecursos['coskg'] = $coskg;
			$promedioRecursos['cta'] = $cta;
			$icb = number_format($icb, 2, ',', '');
			$promedioRecursos['icb'] = $icb;
			$promedioRecursos['tc'] = number_format($promedioRecursos['tc'], 2, ',', '');
			$promedioRecursos['ctr'] = number_format($promedioRecursos['ctr'], 2, ',', '');
		}


		if ($request['see_all']) {
			return [
				'recursosNecesarios' => $recursosNecesarios,
				'promedioRecursos' => $promedioRecursos

			];
		} else {
			return [
				'recursosNecesarios' => $recursosNecesarios,
				'promedioRecursos' => $promedioRecursos,
				'pagination' => [
					'total'        => $recursosNecesarios->total(),
					'current_page' => $recursosNecesarios->currentPage(),
					'per_page'     => $recursosNecesarios->perPage(),
					'last_page'    => $recursosNecesarios->lastPage(),
					'from'         => $recursosNecesarios->firstItem(),
					'to'           => $recursosNecesarios->lastItem(),
				],
			];
		}
	}
}
