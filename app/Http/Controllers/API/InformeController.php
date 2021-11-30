<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\EspeciesSiembraController;

use App\EspecieSiembra;
use App\Siembra;
use App\RecursoSiembra;
use App\RecursoNecesario;
use App\Recursos;
use App\Registro;
use App\Actividad;
use Illuminate\Support\Facades\DB;

class InformeController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{

		$minutos_hombre = Recursos::select()->where('recurso', 'Minutos hombre')->orWhere('recurso', 'Minuto hombre')->orWhere('recurso', 'Minutos')->first();

		$recursosNecesarios = RecursoNecesario::orderBy('fecha_ra', 'desc')
			->leftJoin('recursos', 'recursos_necesarios.id_recurso', 'recursos.id')
			->leftJoin('alimentos', 'recursos_necesarios.id_alimento', 'alimentos.id')
			->join('recursos_siembras', 'recursos_necesarios.id', 'recursos_siembras.id_registro')
			->join('siembras', 'recursos_siembras.id_siembra', 'siembras.id')
			->join('actividades', 'recursos_necesarios.tipo_actividad', 'actividades.id')
			->select('recursos.id as idr', 'alimentos.id as ida', 'recursos_necesarios.id as id', 'actividad', 'id_recurso', 'id_alimento', 'fecha_ra', 'minutos_hombre', 'cant_manana', 'cant_tarde', 'detalles', 'tipo_actividad', 'recurso', 'alimento', 'recursos.costo as costo_r', 'alimentos.costo_kg as costo_a', 'nombre_siembra', 'estado', 'cantidad_recurso')
			->get();

		$acumula = 0;
		$acumula2 = 0;
		$acumula3 = 0;


		$promedioRecursos = array();
		$sumac = 0;

		if (count($recursosNecesarios) > 0) {
			for ($i = 0; $i < count($recursosNecesarios); $i++) {

				$recursosNecesarios[$i]->costo_total_recurso = ($recursosNecesarios[$i]->cantidad_recurso * $recursosNecesarios[$i]->costo_r);
				$acumula += ($recursosNecesarios[$i]->cantidad_recurso * $recursosNecesarios[$i]->costo_r);

				$recursosNecesarios[$i]->costo_total_alimento = ($recursosNecesarios[$i]->cant_tarde + $recursosNecesarios[$i]->cant_manana) * $recursosNecesarios[$i]->costo_a;
				$acumula2 += (($recursosNecesarios[$i]->cant_tarde + $recursosNecesarios[$i]->cant_manana) * $recursosNecesarios[$i]->costo_a);

				$recursosNecesarios[$i]->costo_minutosh = $recursosNecesarios[$i]->minutos_hombre * $minutos_hombre->costo;
				$acumula3 += $recursosNecesarios[$i]->costo_minutosh;
				$recursosNecesarios[$i]->costo_total_actividad = ($acumula + 	$acumula2 + 	$acumula3);

				$sumac = $recursosNecesarios[$i]->costo_total_actividad;

				$recursosNecesarios[$i]->costo_r_acum = number_format($acumula, 2, ',', '');
				$recursosNecesarios[$i]->costo_a_acum = number_format($acumula2, 2, ',', '');
				$recursosNecesarios[$i]->costo_h_acum = number_format($acumula3, 2, ',', '');
			}
		}

		return ['recursosNecesarios' => $recursosNecesarios];
	}

	public function filtroInformes(Request $request)
	{
		$minutos_hombre = Recursos::select()->where('recurso', 'Minutos hombre')->orWhere('recurso', 'Minuto hombre')->orWhere('recurso', 'Minutos')->first();

		$c1 = 'tipo_actividad';
		$op1 = '!=';
		$c2 = '-1';
		$c3 = 'tipo_actividad';
		$op2 = '!=';
		$c4 = '-1';
		$c5 = 'tipo_actividad';
		$op3 = '!=';
		$c6 = '-1';
		$c7 = 'tipo_actividad';
		$op4 = '!=';
		$c8 = '-1';
		$c9 = 'tipo_actividad';
		$op5 = '!=';
		$c10 = '-1';
		$c11 = 'tipo_actividad';
		$op6 = '!=';
		$c12 = '-1';
		$c13 = 'tipo_actividad';
		$op7 = '!=';
		$c14 = '-1';
		$signCont = '!=';
		$idContenedor = '-1';

		if ($request['estado_s'] != '-1') {
			$c1 = "estado";
			$op1 = '=';
			$c2 = $request['estado_s'];
		}
		if ($request['actividad_s'] != '-1') {
			$c3 = "tipo_actividad";
			$op2 = '=';
			$c4 = $request['actividad_s'];
		}
		if ($request['alimento_s'] != '-1') {
			$c5 = "id_alimento";
			$op3 = '=';
			$c6 = $request['alimento_s'];
		}
		if ($request['recurso_s'] != '-1') {
			$c7 = "id_recurso";
			$op4 = '=';
			$c8 = $request['recurso_s'];
		}
		if ($request['fecha_ra1'] != '-1') {
			$c9 = "fecha_ra";
			$op5 = '>=';
			$c10 = $request['fecha_ra1'];
		}
		if ($request['fecha_ra2'] != '-1') {
			$c11 = "fecha_ra";
			$op6 = '<=';
			$c12 = $request['fecha_ra2'];
		}
		if ($request['f_siembra'] != '-1') {
			$c13 = "siembras.id";
			$op7 = '=';
			$c14 = $request['f_siembra'];
		}
		if ($request['f_contenedor'] != '-1') {
			$signCont = '=';
			$idContenedor = $request['f_contenedor'];
		}

		$recursosNecesarios = RecursoNecesario::orderBy('fecha_ra', 'desc')
			->leftJoin('recursos', 'recursos_necesarios.id_recurso', 'recursos.id')
			->leftJoin('alimentos', 'recursos_necesarios.id_alimento', 'alimentos.id')
			->join('recursos_siembras', 'recursos_necesarios.id', 'recursos_siembras.id_registro')
			->join('siembras', 'recursos_siembras.id_siembra', 'siembras.id')
			->join('actividades', 'recursos_necesarios.tipo_actividad', 'actividades.id')
			->select('recursos.id as idr', 'alimentos.id as ida', 'recursos_necesarios.id as id', 'actividad', 'minutos_hombre', 'id_recurso', 'id_alimento', 'fecha_ra', 'minutos_hombre', 'cant_manana', 'cant_tarde', 'detalles', 'tipo_actividad', 'recurso', 'alimento', 'recursos.costo as costo_r', 'alimentos.costo_kg as costo_a', 'nombre_siembra', 'estado', 'cantidad_recurso', 'siembras.id_contenedor')
			->where($c1, $op1, $c2)
			->where($c3, $op2, $c4)
			->where($c5, $op3, $c6)
			->where($c7, $op4, $c8)
			->where($c9, $op5, $c10)
			->where($c11, $op6, $c12)
			->where($c13, $op7, $c14)
			->where('siembras.id_contenedor', $signCont, $idContenedor)
			->get();

		$acumula = 0;
		$acumula2 = 0;
		$acumula3 = 0;

		if (count($recursosNecesarios) > 0) {
			for ($i = 0; $i < count($recursosNecesarios); $i++) {

				$recursosNecesarios[$i]->costo_total_recurso = ($recursosNecesarios[$i]->cantidad_recurso * $recursosNecesarios[$i]->costo_r);
				$acumula += ($recursosNecesarios[$i]->cantidad_recurso * $recursosNecesarios[$i]->costo_r);

				$recursosNecesarios[$i]->costo_total_alimento = ($recursosNecesarios[$i]->cant_tarde + $recursosNecesarios[$i]->cant_manana) * $recursosNecesarios[$i]->costo_a;
				$acumula2 += (($recursosNecesarios[$i]->cant_tarde + $recursosNecesarios[$i]->cant_manana) * $recursosNecesarios[$i]->costo_a);

				$recursosNecesarios[$i]->costo_minutosh = $recursosNecesarios[$i]->minutos_hombre * $minutos_hombre->costo;
				$acumula3 += $recursosNecesarios[$i]->costo_minutosh;


				$recursosNecesarios[$i]->costo_total_actividad = ($acumula + 	$acumula2 + 	$acumula3);

				$recursosNecesarios[$i]->costo_r_acum = number_format($acumula, 2, ',', '');
				$recursosNecesarios[$i]->costo_a_acum = number_format($acumula2, 2, ',', '');
				$recursosNecesarios[$i]->costo_h_acum = number_format($acumula3, 2, ',', '');
			}
		}

		return ['recursosNecesarios' => $recursosNecesarios];
	}

	public function traerInformes()
	{
		$minutos_hombre = Recursos::select()->where('recurso', 'Minutos hombre')->orWhere('recurso', 'Minuto hombre')->orWhere('recurso', 'Minutos')->first();

		$recursosNecesarios = RecursoNecesario::orderBy('fecha_ra', 'desc')
			->leftJoin('recursos', 'recursos_necesarios.id_recurso', 'recursos.id')
			->leftJoin('alimentos', 'recursos_necesarios.id_alimento', 'alimentos.id')
			->join('recursos_siembras', 'recursos_necesarios.id', 'recursos_siembras.id_registro')
			->join('siembras', 'recursos_siembras.id_siembra', 'siembras.id')
			->join('actividades', 'recursos_necesarios.tipo_actividad', 'actividades.id')
			->select('recursos.id as idr', 'alimentos.id as ida', 'recursos_necesarios.id as id', 'actividad', 'id_recurso', 'id_alimento', 'fecha_ra', 'minutos_hombre', 'cant_manana', 'cant_tarde', 'detalles', 'tipo_actividad', 'recurso', 'alimento', 'recursos.costo as costo_r', 'alimentos.costo_kg as costo_a', 'nombre_siembra', 'estado', 'cantidad_recurso')
			->get();

		$acumula = 0;
		$acumula2 = 0;
		$acumula3 = 0;

		if (count($recursosNecesarios) > 0) {
			for ($i = 0; $i < count($recursosNecesarios); $i++) {

				$recursosNecesarios[$i]->costo_total_recurso = ($recursosNecesarios[$i]->cantidad_recurso * $recursosNecesarios[$i]->costo_r);
				$acumula += ($recursosNecesarios[$i]->cantidad_recurso * $recursosNecesarios[$i]->costo_r);

				$recursosNecesarios[$i]->costo_total_alimento = ($recursosNecesarios[$i]->cant_tarde + $recursosNecesarios[$i]->cant_manana) * $recursosNecesarios[$i]->costo_a;
				$acumula2 += (($recursosNecesarios[$i]->cant_tarde + $recursosNecesarios[$i]->cant_manana) * $recursosNecesarios[$i]->costo_a);

				$recursosNecesarios[$i]->costo_minutosh = $recursosNecesarios[$i]->minutos_hombre * $minutos_hombre->costo;
				$acumula3 += $recursosNecesarios[$i]->costo_minutosh;
				$recursosNecesarios[$i]->costo_total_actividad = ($acumula + 	$acumula2 + 	$acumula3);

				$sumac = $recursosNecesarios[$i]->costo_total_actividad;

				$recursosNecesarios[$i]->costo_r_acum = number_format($acumula, 2, ',', '');
				$recursosNecesarios[$i]->costo_a_acum = number_format($acumula2, 2, ',', '');
				$recursosNecesarios[$i]->costo_h_acum = number_format($acumula3, 2, ',', '');
				$recursosNecesarios[$i]->costo_total_recurso = number_format($recursosNecesarios[$i]->costo_total_recurso, 2, ',', '');
				$recursosNecesarios[$i]->costo_total_alimento = number_format($recursosNecesarios[$i]->costo_total_alimento, 2, ',', '');
				$recursosNecesarios[$i]->costo_total_actividad = number_format($recursosNecesarios[$i]->costo_total_actividad, 2, ',', '');
			}
		}
		return ['recursosNecesarios' => $recursosNecesarios];
	}
	// Filtro de la funcion anterior

	public function informeRecursos(Request $request)
	{
		$minutos_hombre = Recursos::select()->where('recurso', 'Minutos hombre')->orWhere('recurso', 'Minuto hombre')->orWhere('recurso', 'Minutos')->first();

		$c1 = 'tipo_actividad';
		$op1 = '!=';
		$c2 = '-1';
		$c3 = 'tipo_actividad';
		$op2 = '!=';
		$c4 = '-1';
		$c5 = 'tipo_actividad';
		$op3 = '!=';
		$c6 = '-1';
		$c7 = 'tipo_actividad';
		$op4 = '!=';
		$c8 = '-1';
		$c9 = 'tipo_actividad';
		$op5 = '!=';
		$c10 = '-1';
		$c11 = 'tipo_actividad';
		$op6 = '!=';
		$c12 = '-1';
		$c13 = 'tipo_actividad';
		$op7 = '!=';
		$c14 = '-1';

		if ($request['estado_s'] != '-1') {
			$c1 = "estado";
			$op1 = '=';
			$c2 = $request['estado_s'];
		}
		if ($request['actividad_s'] != '-1') {
			$c3 = "tipo_actividad";
			$op2 = '=';
			$c4 = $request['actividad_s'];
		}
		if ($request['alimento_s'] != '-1') {
			$c5 = "id_alimento";
			$op3 = '=';
			$c6 = $request['alimento_s'];
		}
		if ($request['recurso_s'] != '-1') {
			$c7 = "id_recurso";
			$op4 = '=';
			$c8 = $request['recurso_s'];
		}
		if ($request['fecha_ra1'] != '-1') {
			$c9 = "fecha_ra";
			$op5 = '>=';
			$c10 = $request['fecha_ra1'];
		}
		if ($request['fecha_ra2'] != '-1') {
			$c11 = "fecha_ra";
			$op6 = '<=';
			$c12 = $request['fecha_ra2'];
		}
		if ($request['f_siembra'] != '-1') {
			$c13 = "siembras.id";
			$op7 = '=';
			$c14 = $request['f_siembra'];
		}

		$recursosNecesarios = RecursoNecesario::orderBy('fecha_ra', 'desc')
			->leftJoin('recursos', 'recursos_necesarios.id_recurso', 'recursos.id')
			->leftJoin('alimentos', 'recursos_necesarios.id_alimento', 'alimentos.id')
			->join('recursos_siembras', 'recursos_necesarios.id', 'recursos_siembras.id_registro')
			->join('siembras', 'recursos_siembras.id_siembra', 'siembras.id')
			->join('actividades', 'recursos_necesarios.tipo_actividad', 'actividades.id')
			->select('recursos.id as idr', 'alimentos.id as ida', 'recursos_necesarios.id as id', 'actividad', 'minutos_hombre', 'id_recurso', 'id_alimento', 'fecha_ra', 'minutos_hombre', 'cant_manana', 'cant_tarde', 'detalles', 'tipo_actividad', 'recurso', 'alimento', 'recursos.costo as costo_r', 'alimentos.costo_kg as costo_a', 'nombre_siembra', 'siembras.estado as estado', 'cantidad_recurso')
			->where($c1, $op1, $c2)
			->where($c3, $op2, $c4)
			->where($c5, $op3, $c6)
			->where($c7, $op4, $c8)
			->where($c9, $op5, $c10)
			->where($c11, $op6, $c12)
			->where($c13, $op7, $c14)
			->get();
		//  return 'Hola';
		$acumula = 0;
		$acumula2 = 0;
		$acumula3 = 0;

		if (count($recursosNecesarios) > 0) {
			for ($i = 0; $i < count($recursosNecesarios); $i++) {

				$recursosNecesarios[$i]->costo_total_recurso = ($recursosNecesarios[$i]->cantidad_recurso * $recursosNecesarios[$i]->costo_r);
				$acumula += ($recursosNecesarios[$i]->cantidad_recurso * $recursosNecesarios[$i]->costo_r);

				$recursosNecesarios[$i]->costo_total_alimento = ($recursosNecesarios[$i]->cant_tarde + $recursosNecesarios[$i]->cant_manana) * $recursosNecesarios[$i]->costo_a;
				$acumula2 += (($recursosNecesarios[$i]->cant_tarde + $recursosNecesarios[$i]->cant_manana) * $recursosNecesarios[$i]->costo_a);

				$recursosNecesarios[$i]->costo_minutosh = $recursosNecesarios[$i]->minutos_hombre * $minutos_hombre->costo;
				$acumula3 += $recursosNecesarios[$i]->costo_minutosh;


				$recursosNecesarios[$i]->costo_total_actividad = ($acumula + 	$acumula2 + 	$acumula3);

				$recursosNecesarios[$i]->costo_r_acum = number_format($acumula, 2, ',', '');
				$recursosNecesarios[$i]->costo_a_acum = number_format($acumula2, 2, ',', '');
				$recursosNecesarios[$i]->costo_h_acum = number_format($acumula3, 2, ',', '');
				$recursosNecesarios[$i]->costo_total_recurso = number_format($recursosNecesarios[$i]->costo_total_recurso, 2, ',', '');
				$recursosNecesarios[$i]->costo_total_alimento = number_format($recursosNecesarios[$i]->costo_total_alimento, 2, ',', '');
				$recursosNecesarios[$i]->costo_total_actividad = number_format($recursosNecesarios[$i]->costo_total_actividad, 2, ',', '');
			}
		}

		return ['recursosNecesarios' => $recursosNecesarios];
	}

	public function traerExistencias()
	{

		$especies = EspecieSiembra::select(
			'cant_actual',
			'contenedor',
			'capacidad',
			'especies_siembra.cantidad as cantidad_inicial',
			'especie',
			'especies_siembra.id_especie as id_especie',
			'especies_siembra.id_siembra as id_siembra',
			'especies_siembra.lote as lote',
			'fecha_inicio',
			'nombre_siembra',
			'peso_inicial',
			'peso_actual',
		)
			->orderBy('especies_siembra.id_siembra')
			->orderBy('especies_siembra.id_especie')
			->join('siembras', 'especies_siembra.id_siembra', 'siembras.id')
			->join('contenedores', 'siembras.id_contenedor', 'contenedores.id')
			->join('especies', 'especies_siembra.id_especie', 'especies.id')
			->where('siembras.estado', '=', 1)
			->get();

		$var1 = 0;
		$var2 = 0;
		$bio_acum  = 0;
		$diff = 0;

		$registros = Registro::select()->get();
		$especies_siembra = new EspeciesSiembraController;

		if (count($especies) > 0) {

			foreach ($especies as $especie) {

				$especie->mortalidad = $especies_siembra->cantidadEspecieSiembra($especie->id_siembra, $especie->id_especie)->mortalidad;
				$especie->biomasa = $especies_siembra->cantidadEspecieSiembra($especie->id_siembra, $especie->id_especie)->biomasa;
				$especie->salida_animales = $especies_siembra->cantidadEspecieSiembra($especie->id_siembra, $especie->id_especie)->cantidad + $especie->mortalidad;
				$especie->salida_animales_sin_mortalidad = $especies_siembra->cantidadEspecieSiembra($especie->id_siembra, $especie->id_especie)->cantidad;
				$especie->cantidad_actual = $especie->cantidad_inicial - $especie->salida_animales;
				$especie->biomasa_disponible = ((($especie->peso_actual) * ($especie->cantidad_actual)) / 1000);
				$especie->biomasa_inicial =  ((($especie->peso_inicial) * ($especie->cantidad_inicial)) / 1000);
				$especie->salida_biomasa = $especies_siembra->cantidadEspecieSiembraSinMortalidad($especie->id_siembra, $especie->id_especie)->biomasa;


				$bio_dispo = ((($especie->peso_actual) * ($especie->cantidad_actual)) / 1000);

				for ($j = 0; $j < count($registros); $j++) {

					if (count($registros) > 0) {
						if ($especie->id_siembra == $registros[$j]->id_siembra && $especie->id_especie == $registros[$j]->id_especie) {

							$int_tiempo = Registro::select('fecha_registro')
								->orderBy('fecha_registro', 'desc')
								->where('id_siembra', $especie->id_siembra)
								->where('id_especie', $especie->id_especie)
								->first();
							$date1 = new \DateTime($especie->fecha_inicio);
							$date2 = new \DateTime($int_tiempo->fecha_registro);
							$diff = $date1->diff($date2);
							$especie->fecha_registro = $int_tiempo->fecha_registro;
							$especie->intervalo_tiempo  = $diff->days;
							$bio_acum += $registros[$j]->biomasa;
							$especie->biomasa_acumulada = number_format($bio_acum, 2, ',', '');
							$especie->mortalidad_kg += (($registros[$j]->mortalidad *  $registros[$j]->peso_ganado) / 1000);
							$especie->mortalidad_porcentaje =  (number_format((($especie->mortalidad * 100) / $especie->cantidad_inicial), 2, ',', ''));
							$var2 = ($var1 * $especie->peso_actual) / 1000;
							$especie->mortalidad_kg_au = (number_format(($var2), 2, ',', ''));
							$especie->densidad_final = (number_format(($especie->salida_animales_sin_mortalidad / $especie->capacidad), 2, ',', ''));
							$especie->carga_final = (number_format(($especie->biomasa / $especie->capacidad), 2, ',', ''));
							$especie->peso_incremento = $especie->peso_actual -  $especie->peso_inicial;
							$especie->incremento_biomasa = (($especie->peso_incremento * $especie->cant_actual) / 1000);
							if ($especie->intervalo_tiempo > 0) {
								$especie->ganancia_peso_dia = $especie->peso_incremento / $especie->intervalo_tiempo;
							}
						}
					}
				}
				$especie->mortalidad_kg = (number_format(($especie->mortalidad_kg), 2, ',', ''));
				$especie->biomasa_disponible = number_format($especie->biomasa_disponible, 2, ',', '');
				$especie->incremento_biomasa = number_format($especie->incremento_biomasa, 2, ',', '');
				$especie->ganancia_peso_dia = number_format($especie->ganancia_peso_dia, 2, ',', '');
				$especie->salida_biomasa = number_format($especie->salida_biomasa, 2, ',', '');
				$especie->cantidad_actual = number_format($especie->cantidad_actual, 0, '', '');
				$especie->salida_animales = number_format($especie->salida_animales, 0, '', '');
				$especie->salida_animales_sin_mortalidad = number_format($especie->salida_animales_sin_mortalidad, 0, '', '');
			}
		}
		return ['existencias' => $especies];
	}

	public function filtroExistencias(Request $request)
	{
		$c1 = "siembras.id";
		$op1 = '!=';
		$c2 = '-1';
		$c3 = "siembras.id";
		$op2 = '!=';
		$c4 = '-1';
		$c5 = "siembras.id";
		$op3 = '!=';
		$c6 = '-1';
		$c7 = "siembras.id";
		$op4 = '!=';
		$c8 = '-1';
		$op5 = '>=';
		$c10 = '0';
		$op6 = '>=';
		$c12 = '0';
		$op7 = '!=';
		$c14 = '-1';
		$signEstado = '!=';
		$idSiembra = '-1';

		if ($request['f_siembra'] != '-1') {
			$c1 = "siembras.id";
			$op1 = '=';
			$c2 = $request['f_siembra'];
		}
		if ($request['f_especie'] != '-1') {
			$c3 = "especies.id";
			$op2 = '=';
			$c4 = $request['f_especie'];
		}
		if ($request['f_inicio_d'] != '-1') {
			$c5 = "fecha_inicio";
			$op3 = '>=';
			$c6 = $request['f_inicio_d'];
		}
		if ($request['f_inicio_h'] != '-1') {
			$c7 = "fecha_inicio";
			$op4 = '<=';
			$c8 = $request['f_inicio_h'];
		}
		if ($request['f_peso_d'] != '-1') {
			$op5 = '>=';
			$c10 = $request['f_peso_d'];
		}
		if ($request['f_peso_h'] != '-1') {
			$op6 = '<=';
			$c12 = $request['f_peso_h'];
		}
		if ($request['f_lote'] != '-1') {
			$op7 = '=';
			$c14 = $request['f_lote'];
		}
		if ($request['f_estado'] != '-1') {
			$signEstado = '=';
			$idSiembra = $request['f_estado'];
		}

		$especies = EspecieSiembra::select(
			'cant_actual',
			'contenedor',
			'capacidad',
			'especies_siembra.cantidad as cantidad_inicial',
			'especie',
			'especies_siembra.id_especie as id_especie',
			'especies_siembra.id_siembra as id_siembra',
			'especies_siembra.lote as lote',
			'fecha_inicio',
			'nombre_siembra',
			'peso_inicial',
			'peso_actual',
		)
			->orderBy('especies_siembra.id_siembra')
			->orderBy('especies_siembra.id_especie')
			->join('siembras', 'especies_siembra.id_siembra', 'siembras.id')
			->join('especies', 'especies_siembra.id_especie', 'especies.id')
			->join('contenedores', 'siembras.id_contenedor', 'contenedores.id')
			->where($c1, $op1, $c2)
			->where($c3, $op2, $c4)
			->where($c5, $op3, $c6)
			->where($c7, $op4, $c8)
			->where('peso_actual', $op5, $c10)
			->where('peso_actual', $op6, $c12)
			->where('lote', $op7, $c14)
			->where('siembras.estado', $signEstado, $idSiembra)
			->get();

		$var1 = 0;
		$var2 = 0;
		$bio_acum  = 0;
		$int_tiempo = 0;
		$registros = Registro::select()
			->join('siembras', 'registros.id_siembra', 'siembras.id')
			->get();

		$registros = Registro::select()->get();
		$especies_siembra = new EspeciesSiembraController;

		if (count($especies) > 0) {

			foreach ($especies as $especie) {

				$especie->mortalidad = $especies_siembra->cantidadEspecieSiembra($especie->id_siembra, $especie->id_especie)->mortalidad;
				$especie->biomasa = $especies_siembra->cantidadEspecieSiembra($especie->id_siembra, $especie->id_especie)->biomasa;
				$especie->salida_animales = $especies_siembra->cantidadEspecieSiembra($especie->id_siembra, $especie->id_especie)->cantidad + $especie->mortalidad;
				$especie->salida_animales_sin_mortalidad = $especies_siembra->cantidadEspecieSiembra($especie->id_siembra, $especie->id_especie)->cantidad;
				$especie->cantidad_actual = $especie->cantidad_inicial - $especie->salida_animales;
				$especie->biomasa_disponible = ((($especie->peso_actual) * ($especie->cantidad_actual)) / 1000);
				$especie->biomasa_inicial =  ((($especie->peso_inicial) * ($especie->cantidad_inicial)) / 1000);
				$especie->salida_biomasa = $especies_siembra->cantidadEspecieSiembraSinMortalidad($especie->id_siembra, $especie->id_especie)->biomasa;


				$bio_dispo = ((($especie->peso_actual) * ($especie->cantidad_actual)) / 1000);

				for ($j = 0; $j < count($registros); $j++) {

					if (count($registros) > 0) {
						if ($especie->id_siembra == $registros[$j]->id_siembra && $especie->id_especie == $registros[$j]->id_especie) {

							$int_tiempo = Registro::select('fecha_registro')
								->orderBy('fecha_registro', 'desc')
								->where('id_siembra', $especie->id_siembra)
								->where('id_especie', $especie->id_especie)
								->first();
							$date1 = new \DateTime($especie->fecha_inicio);
							$date2 = new \DateTime($int_tiempo->fecha_registro);
							$diff = $date1->diff($date2);
							$especie->fecha_registro = $int_tiempo->fecha_registro;
							$especie->intervalo_tiempo  = $diff->days;
							$bio_acum += $registros[$j]->biomasa;
							$especie->biomasa_acumulada = number_format($bio_acum, 2, ',', '');
							$especie->mortalidad_kg += (($registros[$j]->mortalidad *  $registros[$j]->peso_ganado) / 1000);
							$especie->mortalidad_porcentaje =  (number_format((($especie->mortalidad * 100) / $especie->cantidad_inicial), 2, ',', ''));
							$var2 = ($var1 * $especie->peso_actual) / 1000;
							$especie->mortalidad_kg_au = (number_format(($var2), 2, ',', ''));
							$especie->densidad_final = (number_format(($especie->salida_animales_sin_mortalidad / $especie->capacidad), 2, ',', ''));
							$especie->carga_final = (number_format(($especie->biomasa / $especie->capacidad), 2, ',', ''));
							$especie->peso_incremento = $especie->peso_actual -  $especie->peso_inicial;
							$especie->incremento_biomasa = (($especie->peso_incremento * $especie->cant_actual) / 1000);
							if ($especie->intervalo_tiempo > 0) {
								$especie->ganancia_peso_dia = $especie->peso_incremento / $especie->intervalo_tiempo;
							}
						}
					}
				}
				$especie->mortalidad_kg = (number_format(($especie->mortalidad_kg), 2, ',', ''));
				$especie->biomasa_disponible = number_format($especie->biomasa_disponible, 2, ',', '');
				$especie->incremento_biomasa = number_format($especie->incremento_biomasa, 2, ',', '');
				$especie->ganancia_peso_dia = number_format($especie->ganancia_peso_dia, 2, ',', '');
				$especie->salida_biomasa = number_format($especie->salida_biomasa, 2, ',', '');
				$especie->cantidad_actual = number_format($especie->cantidad_actual, 0, '', '');
				$especie->salida_animales = number_format($especie->salida_animales, 0, '', '');
				$especie->salida_animales_sin_mortalidad = number_format($especie->salida_animales_sin_mortalidad, 0, '', '');
			}
		}

		return ['existencias' => $especies];
	}
	public function traerExistenciasDetalle()
	{
		$siembras = Siembra::select(
			'siembras.id as id',
			'capacidad',
			'nombre_siembra',
			'id_contenedor',
			'fecha_inicio',
			'ini_descanso',
			'siembras.estado',
			'fin_descanso'
		)
			->join('contenedores', 'siembras.id_contenedor', 'contenedores.id')
			->get();

		$especies = EspecieSiembra::select(
			'cant_actual',
			'contenedor',
			'especies_siembra.cantidad as cantidad_inicial',
			'especies_siembra.id_especie as id_especie',
			'especies_siembra.lote as lote',
			'especies_siembra.id_siembra as id_siembra',
			'fecha_inicio',
			'nombre_siembra',
			'peso_inicial',
			'peso_actual',
		)
			->orderBy('especies_siembra.id_siembra')
			->orderBy('especies_siembra.id_especie')
			->join('siembras', 'especies_siembra.id_siembra', 'siembras.id')
			->join('contenedores', 'siembras.id_contenedor', 'contenedores.id')
			->where('siembras.estado', '=', 1)
			->get();

		$registros = Registro::select()
			->join('siembras', 'registros.id_siembra', 'siembras.id')
			// ->where('siembras.estado','=','1')
			->get();

		$recursos_necesarios = RecursoNecesario::select(
			'recursos_necesarios.id as id',
			'recursos_siembras.id_registro as id_registro',
			'id_siembra',
			'id_alimento',
			'id_recurso',
			'cantidad_recurso',
			'cant_manana',
			'cant_tarde',
			'conv_alimenticia',
			'minutos_hombre',
			'horas_hombre',
			'costo_kg as costo_alimento',
			'costo as costo_recurso'
		)
			->join('recursos_siembras', 'recursos_necesarios.id', 'recursos_siembras.id_registro')
			->leftJoin('alimentos', 'recursos_necesarios.id_alimento', 'alimentos.id')
			->leftJoin('recursos', 'recursos_necesarios.id_recurso', 'recursos.id')
			->get();

		$mh = Recursos::select()->where('recurso', 'Minutos hombre')->orWhere('recurso', 'Minuto hombre')->orWhere('recurso', 'Minutos')->first();
		$aux_regs = array();
		$diff = 0;
		$especies_siembra = new EspeciesSiembraController;

		if (count($siembras) > 0) {
			foreach ($siembras as $siembra) {

				// Especies en la siembra
				if (count($especies) > 0) {
					$contador_esp = 0;
					foreach ($especies as $especie) {
						$especie->mortalidad = $especies_siembra->cantidadEspecieSiembra($especie->id_siembra, $especie->id_especie)->mortalidad;
						$especie->biomasa = $especies_siembra->cantidadEspecieSiembra($especie->id_siembra, $especie->id_especie)->biomasa;
						$especie->salida_animales = $especies_siembra->cantidadEspecieSiembra($especie->id_siembra, $especie->id_especie)->cantidad + $especies_siembra->cantidadEspecieSiembra($especie->id_siembra, $especie->id_especie)->mortalidad;
						$especie->salida_animales_sin_mortalidad = $especies_siembra->cantidadEspecieSiembra($especie->id_siembra, $especie->id_especie)->cantidad;
						$especie->cantidad_actual = $especie->cantidad_inicial - $especie->salida_animales;
						$especie->biomasa_disponible = ((($especie->peso_actual) * ($especie->cantidad_actual)) / 1000);
						$especie->biomasa_inicial =  ((($especie->peso_inicial) * ($especie->cantidad_inicial)) / 1000);

						if ($siembra->id == $especie->id_siembra) {
							$contador_esp++;
							$siembra->cantidad_inicial += $especie->cantidad_inicial;
							$siembra->peso_ini += $especie->peso_inicial;
							$siembra->cant_actual += $especie->cantidad_actual;
							$siembra->peso_actual += $especie->peso_actual;
							$siembra->biomasa_inicial += $especie->biomasa_inicial;
							$siembra->biomasa_disponible += $especie->biomasa_disponible;

							for ($k = 0; $k < count($registros); $k++) {

								if ($especie->id_siembra == $registros[$k]->id_siembra) {

									$int_tiempo = Registro::select('fecha_registro')
										->orderBy('fecha_registro', 'desc')
										->where('id_siembra', $especie->id_siembra)
										->where('id_especie', $especie->id_especie)
										->first();

									if (isset($int_tiempo['fecha_registro'])) {
										$date1 = new \DateTime($especie->fecha_inicio);
										$date2 = new \DateTime($int_tiempo['fecha_registro']);
										$diff = $date1->diff($date2);

										$especie->intervalo_tiempo  = $diff->days;
									} else {
										$especie->intervalo_tiempo  = 1;
									}

									$especie->salida_biomasa += $registros[$k]->biomasa;
									if ($especie->id_especie == $registros[$k]->id_especie) {
										$registros[$k]->mortalidad_kg = (($registros[$k]->mortalidad * $registros[$k]->peso_ganado) / 1000);
										$especie->mortalidad_kg += $registros[$k]->mortalidad_kg;
										$especie->peso_incremento = $especie->peso_actual -  $especie->peso_inicial;
										$especie->incremento_biomasa = (($especie->peso_incremento * $especie->cant_actual) / 1000);
										if ($especie->intervalo_tiempo > 0) {
											$especie->ganancia_peso_dia = $especie->peso_incremento / $especie->intervalo_tiempo;
										} else {
											$especie->ganancia_peso_dia = 0;
										}
										$especie->mortalidad_porcentaje =  (($especie->mortalidad * 100) / $especie->cantidad_inicial);
									}
								}
							}

							$siembra->mortalidad =  $especies_siembra->cantidadTotalEspeciesSiembra($siembra->id)->mortalidad;
							$siembra->mortalidad_kg += $especie->mortalidad_kg;
							$siembra->mortalidad_porcentaje = (($siembra->mortalidad * 100) / $siembra->cantidad_inicial);
							$siembra->salida_biomasa = $especies_siembra->cantidadTotalEspeciesSiembraSinMortalidad($siembra->id)->biomasa;
							$siembra->salida_animales += $especie->salida_animales;
							$siembra->salida_animales_sin_mortalidad += $especie->salida_animales_sin_mortalidad;
							$siembra->cantidad_actual += $especie->cantidad_actual;
							$siembra->incremento_biomasa += $especie->incremento_biomasa;
							$siembra->intervalo_tiempo = $especie->intervalo_tiempo;
							$siembra->porc_supervivencia_final = (($siembra->salida_animales_sin_mortalidad * 100) / $siembra->cantidad_inicial);
							$siembra->densidad_inicial = ($siembra->cantidad_inicial / $siembra->capacidad);
							$siembra->densidad_final = ($siembra->salida_animales_sin_mortalidad / $siembra->capacidad);
							$siembra->carga_inicial = ($siembra->biomasa_inicial / $siembra->capacidad);
							$siembra->carga_final = ($siembra->salida_biomasa / $siembra->capacidad);
							$siembra->ganancia_peso_dia += $especie->ganancia_peso_dia;
						}
					}
				}

				for ($l = 0; $l < count($recursos_necesarios); $l++) {
					if ($siembra->id == $recursos_necesarios[$l]->id_siembra) {

						$siembra->minutos_hombre += $recursos_necesarios[$l]->minutos_hombre;
						$siembra->horas_hombre = $siembra->minutos_hombre / 60;
						$recursos_necesarios[$l]->costo_total_recurso =  $recursos_necesarios[$l]->cantidad_recurso *  $recursos_necesarios[$l]->costo_recurso;
						$siembra->costo_total_recurso += $recursos_necesarios[$l]->costo_total_recurso;
						$recursos_necesarios[$l]->cantidad_total_alimento = $recursos_necesarios[$l]->cant_tarde + $recursos_necesarios[$l]->cant_manana;
						$siembra->cantidad_total_alimento +=  $recursos_necesarios[$l]->cantidad_total_alimento;
						$recursos_necesarios[$l]->costo_total_alimento = ($recursos_necesarios[$l]->cant_tarde + $recursos_necesarios[$l]->cant_manana) * $recursos_necesarios[$l]->costo_alimento;
						$siembra->costo_total_alimento += $recursos_necesarios[$l]->costo_total_alimento;

						if ($recursos_necesarios[$l]->conv_alimenticia > 0) {
							$recursos_necesarios[$l]->incr_bio_acum_conver = $recursos_necesarios[$l]->cantidad_total_alimento / $recursos_necesarios[$l]->conv_alimenticia;
							$recursos_necesarios[$l]->conv_alimenticia = number_format($recursos_necesarios[$l]->conv_alimenticia, 2, ',', '');
							$siembra->incr_bio_acum_conver +=  $recursos_necesarios[$l]->incr_bio_acum_conver;
						}
					}
				}
				$siembra->costo_minutos_hombre += ($siembra->minutos_hombre * $mh->costo);
				$siembra->costo_total_siembra = ($siembra->costo_minutos_hombre + $siembra->costo_total_alimento + $siembra->costo_total_recurso);

				if (($siembra->salida_biomasa) > 0) {
					$siembra->costo_produccion_final = $siembra->costo_total_siembra / $siembra->salida_biomasa;
				} else {
					$siembra->costo_produccion_final = 0;
				}

				if ($siembra->incremento_biomasa > 0) {
					$siembra->conversion_alimenticia_siembra = $siembra->cantidad_total_alimento /  $siembra->incremento_biomasa;
				}
				if (($siembra->biomasa_disponible - $siembra->biomasa_inicial) > 0) {
					$siembra->conversion_alimenticia_parcial = $siembra->cantidad_total_alimento / ($siembra->biomasa_disponible - $siembra->biomasa_inicial);
				} else {
					$siembra->conversion_alimenticia_parcial = 0;
				}
				if (($siembra->salida_biomasa + $siembra->mortalidad_kg - $siembra->biomasa_inicial) > 0) {
					$siembra->conversion_final = (($siembra->cantidad_total_alimento) / ($siembra->salida_biomasa + $siembra->mortalidad_kg - $siembra->biomasa_inicial));
				} else {
					$siembra->conversion_final = 0;
				}
				$siembra->bio_dispo_conver = ($siembra->biomasa_inicial + $siembra->incr_bio_acum_conver) - ($siembra->biomasa_disponible + $siembra->mortalidad_kg);

				$siembra->contador_esp = $contador_esp;
				if (($siembra->contador_esp) > 0) {
					$siembra->peso_inicial = $siembra->peso_ini / $siembra->contador_esp;
				} else {
					$siembra->peso_inicial = 0;
				}
				if (($siembra->contador_esp) > 0) {
					$siembra->peso_actual_esp = $siembra->peso_actual / $siembra->contador_esp;
				} else {
					$siembra->peso_actual_esp = 0;
				}
				$siembra->conversion_alimenticia_siembra = number_format($siembra->conversion_alimenticia_siembra, 2, ',', '');
				$siembra->biomasa_disponible = number_format($siembra->biomasa_disponible, 2, ',', '');
				$siembra->mortalidad_kg = number_format($siembra->mortalidad_kg, 2, ',', '');
				$siembra->salida_animales = number_format($siembra->salida_animales, 0, '', '');
				$siembra->densidad_inicial = (number_format(($siembra->densidad_inicial), 2, ',', ''));
				$siembra->densidad_final = (number_format(($siembra->densidad_final), 2, ',', ''));
				$siembra->incremento_biomasa = number_format($siembra->incremento_biomasa, 2, ',', '');
				$siembra->bio_dispo_conver = number_format($siembra->bio_dispo_conver, 2, ',', '');
				$siembra->incr_bio_acum_conver = number_format($siembra->incr_bio_acum_conver, 2, ',', '');
				$siembra->ganancia_peso_dia = number_format($siembra->ganancia_peso_dia, 2, ',', '');
				$siembra->peso_inicial = number_format($siembra->peso_inicial, 2, ',', '');
				$siembra->mortalidad_porcentaje = number_format($siembra->mortalidad_porcentaje, 2, ',', '');
				$siembra->peso_actual_esp = number_format($siembra->peso_actual_esp, 2, ',', '');
				$siembra->horas_hombre = number_format($siembra->horas_hombre, 2, ',', '');
				$siembra->conversion_alimenticia_parcial = number_format($siembra->conversion_alimenticia_parcial, 2, ',', '');
				$siembra->conversion_final = number_format($siembra->conversion_final, 2, ',', '');
				$siembra->porc_supervivencia_final = number_format($siembra->porc_supervivencia_final, 2, ',', '');
				$siembra->carga_inicial = number_format($siembra->carga_inicial, 2, ',', '');
				$siembra->carga_final = number_format($siembra->carga_final, 2, ',', '');
				$siembra->costo_total_recurso = number_format($siembra->costo_total_recurso, 2, ',', '');
				$siembra->costo_total_siembra = number_format($siembra->costo_total_siembra, 2, ',', '');
				$siembra->costo_produccion_final = number_format($siembra->costo_produccion_final, 2, ',', '');
				$siembra->salida_biomasa = number_format($siembra->salida_biomasa, 2, ',', '');
				$siembra->cantidad_actual = number_format($siembra->cantidad_actual, 0, '', '');
				$siembra->costo_total_alimento = number_format($siembra->costo_total_alimento, 0, '', '');
				// recursos_necesarios
				$aux_regs[] = [
					"biomasa_inicial" => $siembra->biomasa_inicial,
					"biomasa_disponible" => $siembra->biomasa_disponible,
					'bio_dispo_conver' => $siembra->bio_dispo_conver,
					"carga_inicial" => $siembra->carga_inicial,
					"carga_final" => $siembra->carga_final,
					"cantidad_inicial" => $siembra->cantidad_inicial,
					"cant_actual" => $siembra->cantidad_actual,
					'capacidad' => $siembra->capacidad,
					'conversion_final' => $siembra->conversion_final,
					"costo_minutosh" => $siembra->costo_minutos_hombre,
					"costo_total_recurso" => $siembra->costo_total_recurso,
					'cantidad_total_alimento' => $siembra->cantidad_total_alimento,
					"costo_total_alimento" => $siembra->costo_total_alimento,
					"costo_tot" => $siembra->costo_total_siembra,
					"costo_produccion_final" => $siembra->costo_produccion_final,
					'conversion_alimenticia_siembra' => $siembra->conversion_alimenticia_siembra,
					'conversion_alimenticia_parcial' => $siembra->conversion_alimenticia_parcial,
					"densidad_inicial" => $siembra->densidad_inicial,
					"densidad_final" => $siembra->densidad_final,
					'ganancia_peso_dia' => $siembra->ganancia_peso_dia,
					"fecha_inicio" => $siembra->fecha_inicio,
					"horas_hombre" => $siembra->horas_hombre,
					"minutos_hombre" => $siembra->minutos_hombre,
					'incr_bio_acum_conver' => $siembra->incr_bio_acum_conver,
					'incremento_biomasa' => $siembra->incremento_biomasa,
					'intervalo_tiempo' => $siembra->intervalo_tiempo,
					"mortalidad" => $siembra->mortalidad,
					"mortalidad_kg" => $siembra->mortalidad_kg,
					"mortalidad_porcentaje" => $siembra->mortalidad_porcentaje,
					"nombre_siembra" => $siembra->nombre_siembra,
					"peso_inicial" => $siembra->peso_inicial,
					"peso_actual" => $siembra->peso_actual_esp,
					"salida_animales" => $siembra->salida_animales,
					"salida_biomasa" => $siembra->salida_biomasa,
					"porc_supervivencia_final" => $siembra->porc_supervivencia_final,
					"salida_animales_sin_mortalidad" => number_format($siembra->salida_animales_sin_mortalidad, 0, ',', '')
				];
			}
		}

		return ['existencias' => $aux_regs];
	}

	public function filtroExistenciasDetalle(Request $request)
	{

		$c1 = "siembras.id";
		$op1 = '!=';
		$c2 = '-1';
		$op2 = '!=';
		$c4 = '-1';
		$signCont = '!=';
		$idContenedor = '-1';

		if ($request['f_siembra'] != '-1') {
			$c1 = "siembras.id";
			$op1 = '=';
			$c2 = $request['f_siembra'];
		}
		if ($request['f_contenedor'] != '-1') {
			$signCont = '=';
			$idContenedor = $request['f_contenedor'];
		}
		if ($request['f_estado'] != '-1') {
			$op2 = '=';
			$c4 = $request['f_estado'];
		}


		$siembras = Siembra::select(
			'siembras.id as id',
			'capacidad',
			'nombre_siembra',
			'id_contenedor',
			'fecha_inicio',
			'ini_descanso',
			'siembras.estado',
			'fin_descanso'
		)
			->where($c1, $op1, $c2)
			->where('id_contenedor', $signCont, $idContenedor)
			->where('siembras.estado', $op2, $c4)
			->join('contenedores', 'siembras.id_contenedor', 'contenedores.id')
			->get();

		$especies = EspecieSiembra::select(
			'cant_actual',
			'contenedor',
			'capacidad',
			'especies_siembra.cantidad as cantidad_inicial',
			// 'especie',
			'especies_siembra.id_especie as id_especie',
			'especies_siembra.id_siembra as id_siembra',
			'fecha_inicio',
			'nombre_siembra',
			'peso_inicial',
			'peso_actual',
		)
			->orderBy('especies_siembra.id_siembra')
			->orderBy('especies_siembra.id_especie')
			->join('siembras', 'especies_siembra.id_siembra', 'siembras.id')
			->join('contenedores', 'siembras.id_contenedor', 'contenedores.id')
			->join('especies', 'especies_siembra.id_especie', 'especies.id')
			->get();
		$registros = Registro::select()
			->join('siembras', 'registros.id_siembra', 'siembras.id')
			->get();

		$recursos_necesarios = RecursoNecesario::select(
			'recursos_necesarios.id as id',
			'recursos_siembras.id_registro as id_registro',
			'id_siembra',
			'id_alimento',
			'id_recurso',
			'cantidad_recurso',
			'cant_manana',
			'cant_tarde',
			'conv_alimenticia',
			'minutos_hombre',
			'horas_hombre',
			'costo_kg as costo_alimento',
			'costo as costo_recurso'
		)
			->join('recursos_siembras', 'recursos_necesarios.id', 'recursos_siembras.id_registro')
			->leftJoin('alimentos', 'recursos_necesarios.id_alimento', 'alimentos.id')
			->leftJoin('recursos', 'recursos_necesarios.id_recurso', 'recursos.id')
			->get();

		$mh = Recursos::select()->where('recurso', 'Minutos hombre')->orWhere('recurso', 'Minuto hombre')->orWhere('recurso', 'Minutos')->first();
		$especies_siembra = new EspeciesSiembraController;

		$aux_regs = array();
		$diff = 0;

		if (count($siembras) > 0) {
			foreach ($siembras as $siembra) {

				// Especies en la siembra
				if (count($especies) > 0) {
					$contador_esp = 0;
					foreach ($especies as $especie) {
						$especie->mortalidad = $especies_siembra->cantidadEspecieSiembra($especie->id_siembra, $especie->id_especie)->mortalidad;
						$especie->biomasa = $especies_siembra->cantidadEspecieSiembra($especie->id_siembra, $especie->id_especie)->biomasa;
						$especie->salida_animales = $especies_siembra->cantidadEspecieSiembra($especie->id_siembra, $especie->id_especie)->cantidad + $especies_siembra->cantidadEspecieSiembra($especie->id_siembra, $especie->id_especie)->mortalidad;
						$especie->salida_animales_sin_mortalidad = $especies_siembra->cantidadEspecieSiembra($especie->id_siembra, $especie->id_especie)->cantidad;
						$especie->cantidad_actual = $especie->cantidad_inicial - $especie->salida_animales;
						$especie->biomasa_disponible = ((($especie->peso_actual) * ($especie->cantidad_actual)) / 1000);
						$especie->biomasa_inicial =  ((($especie->peso_inicial) * ($especie->cantidad_inicial)) / 1000);

						if ($siembra->id == $especie->id_siembra) {
							$contador_esp++;
							$siembra->cantidad_inicial += $especie->cantidad_inicial;
							$siembra->peso_ini += $especie->peso_inicial;
							$siembra->cant_actual += $especie->cantidad_actual;
							$siembra->peso_actual += $especie->peso_actual;
							$siembra->biomasa_inicial += $especie->biomasa_inicial;
							$siembra->biomasa_disponible += $especie->biomasa_disponible;

							for ($k = 0; $k < count($registros); $k++) {

								if ($especie->id_siembra == $registros[$k]->id_siembra) {

									$int_tiempo = Registro::select('fecha_registro')
										->orderBy('fecha_registro', 'desc')
										->where('id_siembra', $especie->id_siembra)
										->where('id_especie', $especie->id_especie)
										->first();

									if (isset($int_tiempo['fecha_registro'])) {
										$date1 = new \DateTime($especie->fecha_inicio);
										$date2 = new \DateTime($int_tiempo['fecha_registro']);
										$diff = $date1->diff($date2);

										$especie->intervalo_tiempo  = $diff->days;
									} else {
										$especie->intervalo_tiempo  = 1;
									}

									$especie->salida_biomasa += $registros[$k]->biomasa;
									if ($especie->id_especie == $registros[$k]->id_especie) {
										$registros[$k]->mortalidad_kg = (($registros[$k]->mortalidad * $registros[$k]->peso_ganado) / 1000);
										$especie->mortalidad_kg += $registros[$k]->mortalidad_kg;
										$especie->peso_incremento = $especie->peso_actual -  $especie->peso_inicial;
										$especie->incremento_biomasa = (($especie->peso_incremento * $especie->cant_actual) / 1000);
										if ($especie->intervalo_tiempo > 0) {
											$especie->ganancia_peso_dia = $especie->peso_incremento / $especie->intervalo_tiempo;
										} else {
											$especie->ganancia_peso_dia = 0;
										}
										$especie->mortalidad_porcentaje =  (($especie->mortalidad * 100) / $especie->cantidad_inicial);
									}
								}
							}
							$siembra->mortalidad =  $especies_siembra->cantidadTotalEspeciesSiembra($siembra->id)->mortalidad;
							$siembra->mortalidad_kg += $especie->mortalidad_kg;
							$siembra->mortalidad_porcentaje = (($siembra->mortalidad * 100) / $siembra->cantidad_inicial);
							$siembra->salida_biomasa = $especies_siembra->cantidadTotalEspeciesSiembraSinMortalidad($siembra->id)->biomasa;
							$siembra->salida_animales += $especie->salida_animales;
							$siembra->salida_animales_sin_mortalidad += $especie->salida_animales_sin_mortalidad;
							$siembra->cantidad_actual += $especie->cantidad_actual;
							$siembra->incremento_biomasa += $especie->incremento_biomasa;
							$siembra->intervalo_tiempo = $especie->intervalo_tiempo;
							$siembra->porc_supervivencia_final = (($siembra->salida_animales_sin_mortalidad * 100) / $siembra->cantidad_inicial);
							$siembra->densidad_inicial = ($siembra->cantidad_inicial / $siembra->capacidad);
							$siembra->densidad_final = ($siembra->salida_animales_sin_mortalidad / $siembra->capacidad);
							$siembra->carga_inicial = ($siembra->biomasa_inicial / $siembra->capacidad);
							$siembra->carga_final = ($siembra->salida_biomasa / $siembra->capacidad);
							$siembra->ganancia_peso_dia += $especie->ganancia_peso_dia;
						}
					}
				}

				for ($l = 0; $l < count($recursos_necesarios); $l++) {
					if ($siembra->id == $recursos_necesarios[$l]->id_siembra) {

						$siembra->minutos_hombre += $recursos_necesarios[$l]->minutos_hombre;
						$siembra->horas_hombre = $siembra->minutos_hombre / 60;
						$recursos_necesarios[$l]->costo_total_recurso =  $recursos_necesarios[$l]->cantidad_recurso *  $recursos_necesarios[$l]->costo_recurso;
						$siembra->costo_total_recurso += $recursos_necesarios[$l]->costo_total_recurso;
						$recursos_necesarios[$l]->cantidad_total_alimento = $recursos_necesarios[$l]->cant_tarde + $recursos_necesarios[$l]->cant_manana;
						$siembra->cantidad_total_alimento +=  $recursos_necesarios[$l]->cantidad_total_alimento;
						$recursos_necesarios[$l]->costo_total_alimento = ($recursos_necesarios[$l]->cant_tarde + $recursos_necesarios[$l]->cant_manana) * $recursos_necesarios[$l]->costo_alimento;
						$siembra->costo_total_alimento += $recursos_necesarios[$l]->costo_total_alimento;

						if ($recursos_necesarios[$l]->conv_alimenticia > 0) {
							$recursos_necesarios[$l]->incr_bio_acum_conver = $recursos_necesarios[$l]->cantidad_total_alimento / $recursos_necesarios[$l]->conv_alimenticia;
							$recursos_necesarios[$l]->conv_alimenticia = number_format($recursos_necesarios[$l]->conv_alimenticia, 2, ',', '');
							$siembra->incr_bio_acum_conver +=  $recursos_necesarios[$l]->incr_bio_acum_conver;
						}
					}
				}
				$siembra->costo_minutos_hombre += ($siembra->minutos_hombre * $mh->costo);
				$siembra->costo_total_siembra = ($siembra->costo_minutos_hombre + $siembra->costo_total_alimento + $siembra->costo_total_recurso);

				if (($siembra->salida_biomasa) > 0) {
					$siembra->costo_produccion_final = $siembra->costo_total_siembra / $siembra->salida_biomasa;
				} else {
					$siembra->costo_produccion_final = 0;
				}

				if ($siembra->incremento_biomasa > 0) {
					$siembra->conversion_alimenticia_siembra = $siembra->cantidad_total_alimento /  $siembra->incremento_biomasa;
				}
				if (($siembra->biomasa_disponible - $siembra->biomasa_inicial) > 0) {
					$siembra->conversion_alimenticia_parcial = $siembra->cantidad_total_alimento / ($siembra->biomasa_disponible - $siembra->biomasa_inicial);
				} else {
					$siembra->conversion_alimenticia_parcial = 0;
				}
				if (($siembra->salida_biomasa + $siembra->mortalidad_kg - $siembra->biomasa_inicial) > 0) {
					$siembra->conversion_final = (($siembra->cantidad_total_alimento) / ($siembra->salida_biomasa + $siembra->mortalidad_kg - $siembra->biomasa_inicial));
				} else {
					$siembra->conversion_final = 0;
				}
				$siembra->bio_dispo_conver = ($siembra->biomasa_inicial + $siembra->incr_bio_acum_conver) - ($siembra->biomasa_disponible + $siembra->mortalidad_kg);

				$siembra->contador_esp = $contador_esp;
				if (($siembra->contador_esp) > 0) {
					$siembra->peso_inicial = $siembra->peso_ini / $siembra->contador_esp;
				} else {
					$siembra->peso_inicial = 0;
				}
				if (($siembra->contador_esp) > 0) {
					$siembra->peso_actual_esp = $siembra->peso_actual / $siembra->contador_esp;
				} else {
					$siembra->peso_actual_esp = 0;
				}
				$siembra->conversion_alimenticia_siembra = number_format($siembra->conversion_alimenticia_siembra, 2, ',', '');
				$siembra->biomasa_disponible = number_format($siembra->biomasa_disponible, 2, ',', '');
				$siembra->mortalidad_kg = number_format($siembra->mortalidad_kg, 2, ',', '');
				$siembra->salida_animales = number_format($siembra->salida_animales, 0, '', '');
				$siembra->densidad_inicial = (number_format(($siembra->densidad_inicial), 2, ',', ''));
				$siembra->densidad_final = (number_format(($siembra->densidad_final), 2, ',', ''));
				$siembra->incremento_biomasa = number_format($siembra->incremento_biomasa, 2, ',', '');
				$siembra->bio_dispo_conver = number_format($siembra->bio_dispo_conver, 2, ',', '');
				$siembra->incr_bio_acum_conver = number_format($siembra->incr_bio_acum_conver, 2, ',', '');
				$siembra->ganancia_peso_dia = number_format($siembra->ganancia_peso_dia, 2, ',', '');
				$siembra->peso_inicial = number_format($siembra->peso_inicial, 2, ',', '');
				$siembra->mortalidad_porcentaje = number_format($siembra->mortalidad_porcentaje, 2, ',', '');
				$siembra->peso_actual_esp = number_format($siembra->peso_actual_esp, 2, ',', '');
				$siembra->horas_hombre = number_format($siembra->horas_hombre, 2, ',', '');
				$siembra->conversion_alimenticia_parcial = number_format($siembra->conversion_alimenticia_parcial, 2, ',', '');
				$siembra->conversion_final = number_format($siembra->conversion_final, 2, ',', '');
				$siembra->porc_supervivencia_final = number_format($siembra->porc_supervivencia_final, 2, ',', '');
				$siembra->carga_inicial = number_format($siembra->carga_inicial, 2, ',', '');
				$siembra->carga_final = number_format($siembra->carga_final, 2, ',', '');
				$siembra->costo_total_recurso = number_format($siembra->costo_total_recurso, 2, ',', '');
				$siembra->costo_total_siembra = number_format($siembra->costo_total_siembra, 2, ',', '');
				$siembra->costo_produccion_final = number_format($siembra->costo_produccion_final, 2, ',', '');
				$siembra->salida_biomasa = number_format($siembra->salida_biomasa, 2, ',', '');
				$siembra->cantidad_actual = number_format($siembra->cantidad_actual, 0, '', '');
				$siembra->costo_total_alimento = number_format($siembra->costo_total_alimento, 0, '', '');
				// recursos_necesarios
				$aux_regs[] = [
					"biomasa_inicial" => $siembra->biomasa_inicial,
					"biomasa_disponible" => $siembra->biomasa_disponible,
					'bio_dispo_conver' => $siembra->bio_dispo_conver,
					"carga_inicial" => $siembra->carga_inicial,
					"carga_final" => $siembra->carga_final,
					"cantidad_inicial" => $siembra->cantidad_inicial,
					"cant_actual" => $siembra->cantidad_actual,
					'capacidad' => $siembra->capacidad,
					'conversion_final' => $siembra->conversion_final,
					"costo_minutosh" => $siembra->costo_minutos_hombre,
					"costo_total_recurso" => $siembra->costo_total_recurso,
					'cantidad_total_alimento' => $siembra->cantidad_total_alimento,
					"costo_total_alimento" => $siembra->costo_total_alimento,
					"costo_tot" => $siembra->costo_total_siembra,
					"costo_produccion_final" => $siembra->costo_produccion_final,
					'conversion_alimenticia_siembra' => $siembra->conversion_alimenticia_siembra,
					'conversion_alimenticia_parcial' => $siembra->conversion_alimenticia_parcial,
					"densidad_inicial" => $siembra->densidad_inicial,
					"densidad_final" => $siembra->densidad_final,
					'ganancia_peso_dia' => $siembra->ganancia_peso_dia,
					"fecha_inicio" => $siembra->fecha_inicio,
					"horas_hombre" => $siembra->horas_hombre,
					"minutos_hombre" => $siembra->minutos_hombre,
					'incr_bio_acum_conver' => $siembra->incr_bio_acum_conver,
					'incremento_biomasa' => $siembra->incremento_biomasa,
					'intervalo_tiempo' => $siembra->intervalo_tiempo,
					"mortalidad" => $siembra->mortalidad,
					"mortalidad_kg" => $siembra->mortalidad_kg,
					"mortalidad_porcentaje" => $siembra->mortalidad_porcentaje,
					"nombre_siembra" => $siembra->nombre_siembra,
					"peso_inicial" => $siembra->peso_inicial,
					"peso_actual" => $siembra->peso_actual_esp,
					"salida_animales" => $siembra->salida_animales,
					"salida_biomasa" => $siembra->salida_biomasa,
					"porc_supervivencia_final" => $siembra->porc_supervivencia_final,
					"salida_animales_sin_mortalidad" => number_format($siembra->salida_animales_sin_mortalidad, 0, ',', '')
				];
			}
		}
		return ['existencias' => $aux_regs];
	}
}
