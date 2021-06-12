<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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

			$minutos_hombre = Recursos::select()->where('recurso','Minutos hombre')->orWhere('recurso','Minuto hombre')->orWhere('recurso','Minutos')->first();

			$recursosNecesarios = RecursoNecesario::orderBy('fecha_ra', 'desc')
			->leftJoin('recursos', 'recursos_necesarios.id_recurso', 'recursos.id')
			->leftJoin('alimentos', 'recursos_necesarios.id_alimento','alimentos.id')
			->join('recursos_siembras', 'recursos_necesarios.id', 'recursos_siembras.id_registro')
			->join('siembras', 'recursos_siembras.id_siembra', 'siembras.id')
			->join('actividades','recursos_necesarios.tipo_actividad','actividades.id')
			->select('recursos.id as idr', 'alimentos.id as ida', 'recursos_necesarios.id as id','actividad', 'id_recurso', 'id_alimento', 'fecha_ra', 'minutos_hombre', 'cant_manana', 'cant_tarde', 'detalles', 'tipo_actividad', 'recurso', 'alimento', 'recursos.costo as costo_r', 'alimentos.costo_kg as costo_a', 'nombre_siembra', 'estado', 'cantidad_recurso')
			->get();

			$acumula=0;
			$acumula2=0;
			$acumula3=0;


			$promedioRecursos = array();
			$sumac = 0;

			if(count($recursosNecesarios)>0) {
				for($i=0;$i<count($recursosNecesarios); $i++ ){

					$recursosNecesarios[$i]->costo_total_recurso = ($recursosNecesarios[$i]->cantidad_recurso * $recursosNecesarios[$i]->costo_r);
					$acumula+=($recursosNecesarios[$i]->cantidad_recurso * $recursosNecesarios[$i]->costo_r);

					$recursosNecesarios[$i]->costo_total_alimento = ($recursosNecesarios[$i]->cant_tarde + $recursosNecesarios[$i]->cant_manana) * $recursosNecesarios[$i]->costo_a;
					$acumula2+=(($recursosNecesarios[$i]->cant_tarde + $recursosNecesarios[$i]->cant_manana) * $recursosNecesarios[$i]->costo_a);

					$recursosNecesarios[$i]->costo_minutosh = $recursosNecesarios[$i]->minutos_hombre*$minutos_hombre->costo;
					$acumula3+=$recursosNecesarios[$i]->costo_minutosh;
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
		$minutos_hombre = Recursos::select()->where('recurso','Minutos hombre')->orWhere('recurso','Minuto hombre')->orWhere('recurso','Minutos')->first();

		$c1 = 'tipo_actividad'; $op1 = '!='; $c2 = '-1';
		$c3 = 'tipo_actividad'; $op2 = '!='; $c4 = '-1';
		$c5 = 'tipo_actividad'; $op3 = '!='; $c6 = '-1';
		$c7 = 'tipo_actividad'; $op4 = '!='; $c8 = '-1';
		$c9 = 'tipo_actividad'; $op5 = '!='; $c10 = '-1';
		$c11 = 'tipo_actividad'; $op6 = '!='; $c12 = '-1';
		$c13 = 'tipo_actividad'; $op7 = '!='; $c14 = '-1';
		$signCont = '!='; $idContenedor = '-1';

		if($request['estado_s']!='-1'){$c1="estado"; $op1='='; $c2= $request['estado_s'];}
		if($request['actividad_s']!='-1'){$c3="tipo_actividad"; $op2='='; $c4= $request['actividad_s'];}
		if($request['alimento_s']!='-1'){$c5="id_alimento"; $op3='='; $c6= $request['alimento_s'];}
		if($request['recurso_s']!='-1'){$c7="id_recurso"; $op4='='; $c8= $request['recurso_s'];}
		if($request['fecha_ra1']!='-1'){$c9="fecha_ra"; $op5='>='; $c10=$request['fecha_ra1'];}
		if($request['fecha_ra2']!='-1'){$c11="fecha_ra"; $op6='<='; $c12=$request['fecha_ra2'];}
		if($request['f_siembra']!='-1'){$c13="siembras.id"; $op7='='; $c14= $request['f_siembra'];}
		if($request['f_contenedor']!='-1'){$signCont='='; $idContenedor= $request['f_contenedor'];}

		$recursosNecesarios = RecursoNecesario::orderBy('fecha_ra', 'desc')
		->leftJoin('recursos', 'recursos_necesarios.id_recurso', 'recursos.id')
		->leftJoin('alimentos', 'recursos_necesarios.id_alimento','alimentos.id')
		->join('recursos_siembras', 'recursos_necesarios.id', 'recursos_siembras.id_registro')
		->join('siembras', 'recursos_siembras.id_siembra', 'siembras.id')
		->join('actividades','recursos_necesarios.tipo_actividad','actividades.id')
		->select('recursos.id as idr', 'alimentos.id as ida', 'recursos_necesarios.id as id', 'actividad','minutos_hombre', 'id_recurso', 'id_alimento', 'fecha_ra', 'minutos_hombre', 'cant_manana', 'cant_tarde', 'detalles', 'tipo_actividad', 'recurso', 'alimento', 'recursos.costo as costo_r', 'alimentos.costo_kg as costo_a', 'nombre_siembra', 'estado', 'cantidad_recurso', 'siembras.id_contenedor')
		->where($c1, $op1, $c2)
		->where($c3, $op2, $c4)
		->where($c5, $op3, $c6)
		->where($c7, $op4, $c8)
		->where($c9, $op5, $c10)
		->where($c11, $op6, $c12)
		->where($c13, $op7, $c14)
		->where('siembras.id_contenedor', $signCont, $idContenedor )
		->get();

		$acumula=0;
		$acumula2=0;
		$acumula3=0;

		if(count($recursosNecesarios)>0){
			for($i=0;$i<count($recursosNecesarios); $i++){

				$recursosNecesarios[$i]->costo_total_recurso = ($recursosNecesarios[$i]->cantidad_recurso * $recursosNecesarios[$i]->costo_r);
				$acumula+=($recursosNecesarios[$i]->cantidad_recurso * $recursosNecesarios[$i]->costo_r);

				$recursosNecesarios[$i]->costo_total_alimento = ($recursosNecesarios[$i]->cant_tarde + $recursosNecesarios[$i]->cant_manana) * $recursosNecesarios[$i]->costo_a;
				$acumula2+=(($recursosNecesarios[$i]->cant_tarde + $recursosNecesarios[$i]->cant_manana) * $recursosNecesarios[$i]->costo_a);

				$recursosNecesarios[$i]->costo_minutosh = $recursosNecesarios[$i]->minutos_hombre*$minutos_hombre->costo;
				$acumula3+=$recursosNecesarios[$i]->costo_minutosh;


				$recursosNecesarios[$i]->costo_total_actividad = ($acumula + 	$acumula2 + 	$acumula3);

				$recursosNecesarios[$i]->costo_r_acum = number_format($acumula, 2, ',', '');
				$recursosNecesarios[$i]->costo_a_acum = number_format($acumula2, 2, ',', '');
				$recursosNecesarios[$i]->costo_h_acum = number_format($acumula3, 2, ',', '');

			}
		}

		return ['recursosNecesarios' => $recursosNecesarios];
	}

	public function traerInformes(){
		$minutos_hombre = Recursos::select()->where('recurso','Minutos hombre')->orWhere('recurso','Minuto hombre')->orWhere('recurso','Minutos')->first();

		$recursosNecesarios = RecursoNecesario::orderBy('fecha_ra', 'desc')
		->leftJoin('recursos', 'recursos_necesarios.id_recurso', 'recursos.id')
		->leftJoin('alimentos', 'recursos_necesarios.id_alimento','alimentos.id')
		->join('recursos_siembras', 'recursos_necesarios.id', 'recursos_siembras.id_registro')
		->join('siembras', 'recursos_siembras.id_siembra', 'siembras.id')
		->join('actividades','recursos_necesarios.tipo_actividad','actividades.id')
		->select('recursos.id as idr', 'alimentos.id as ida', 'recursos_necesarios.id as id','actividad', 'id_recurso', 'id_alimento', 'fecha_ra', 'minutos_hombre', 'cant_manana', 'cant_tarde', 'detalles', 'tipo_actividad', 'recurso', 'alimento', 'recursos.costo as costo_r', 'alimentos.costo_kg as costo_a', 'nombre_siembra', 'estado', 'cantidad_recurso')
		->get();

		$acumula=0;
		$acumula2=0;
		$acumula3=0;

		if(count($recursosNecesarios)>0){
			for($i=0;$i<count($recursosNecesarios); $i++ ){

				$recursosNecesarios[$i]->costo_total_recurso = ($recursosNecesarios[$i]->cantidad_recurso * $recursosNecesarios[$i]->costo_r);
				$acumula+=($recursosNecesarios[$i]->cantidad_recurso * $recursosNecesarios[$i]->costo_r);

				$recursosNecesarios[$i]->costo_total_alimento = ($recursosNecesarios[$i]->cant_tarde + $recursosNecesarios[$i]->cant_manana) * $recursosNecesarios[$i]->costo_a;
				$acumula2+=(($recursosNecesarios[$i]->cant_tarde + $recursosNecesarios[$i]->cant_manana) * $recursosNecesarios[$i]->costo_a);

				$recursosNecesarios[$i]->costo_minutosh = $recursosNecesarios[$i]->minutos_hombre*$minutos_hombre->costo;
				$acumula3+=$recursosNecesarios[$i]->costo_minutosh;
				$recursosNecesarios[$i]->costo_total_actividad = ($acumula + 	$acumula2 + 	$acumula3);

				$sumac = $recursosNecesarios[$i]->costo_total_actividad;

				$recursosNecesarios[$i]->costo_r_acum = number_format($acumula, 2, ',', '');
				$recursosNecesarios[$i]->costo_a_acum = number_format($acumula2, 2, ',', '');
				$recursosNecesarios[$i]->costo_h_acum = number_format($acumula3, 2, ',', '');
				$recursosNecesarios[$i]->costo_total_recurso = number_format(	$recursosNecesarios[$i]->costo_total_recurso, 2, ',', '');
				$recursosNecesarios[$i]->costo_total_alimento = number_format(	$recursosNecesarios[$i]->costo_total_alimento, 2, ',', '');
				$recursosNecesarios[$i]->costo_total_actividad = number_format(	$recursosNecesarios[$i]->costo_total_actividad, 2, ',', '');
			}
		}
		return ['recursosNecesarios' => $recursosNecesarios];
	}
	// Filtro de la funcion anterior

	public function informeRecursos(Request $request)
	{
		$minutos_hombre = Recursos::select()->where('recurso','Minutos hombre')->orWhere('recurso','Minuto hombre')->orWhere('recurso','Minutos')->first();

		$c1 = 'tipo_actividad'; $op1 = '!='; $c2 = '-1';
		$c3 = 'tipo_actividad'; $op2 = '!='; $c4 = '-1';
		$c5 = 'tipo_actividad'; $op3 = '!='; $c6 = '-1';
		$c7 = 'tipo_actividad'; $op4 = '!='; $c8 = '-1';
		$c9 = 'tipo_actividad'; $op5 = '!='; $c10 = '-1';
		$c11 = 'tipo_actividad'; $op6 = '!='; $c12 = '-1';
		$c13 = 'tipo_actividad'; $op7 = '!='; $c14 = '-1';

		if($request['estado_s']!='-1'){$c1="estado"; $op1='='; $c2= $request['estado_s'];}
		if($request['actividad_s']!='-1'){$c3="tipo_actividad"; $op2='='; $c4= $request['actividad_s'];}
		if($request['alimento_s']!='-1'){$c5="id_alimento"; $op3='='; $c6= $request['alimento_s'];}
		if($request['recurso_s']!='-1'){$c7="id_recurso"; $op4='='; $c8= $request['recurso_s'];}
		if($request['fecha_ra1']!='-1'){$c9="fecha_ra"; $op5='>='; $c10=$request['fecha_ra1'];}
		if($request['fecha_ra2']!='-1'){$c11="fecha_ra"; $op6='<='; $c12=$request['fecha_ra2'];}
		if($request['f_siembra']!='-1'){$c13="siembras.id"; $op7='='; $c14= $request['f_siembra'];}

		$recursosNecesarios = RecursoNecesario::orderBy('fecha_ra', 'desc')
		->leftJoin('recursos', 'recursos_necesarios.id_recurso', 'recursos.id')
		->leftJoin('alimentos', 'recursos_necesarios.id_alimento','alimentos.id')
		->join('recursos_siembras', 'recursos_necesarios.id', 'recursos_siembras.id_registro')
		->join('siembras', 'recursos_siembras.id_siembra', 'siembras.id')
		->join('actividades','recursos_necesarios.tipo_actividad','actividades.id')
		->select('recursos.id as idr', 'alimentos.id as ida', 'recursos_necesarios.id as id','actividad', 'minutos_hombre', 'id_recurso', 'id_alimento', 'fecha_ra', 'minutos_hombre', 'cant_manana', 'cant_tarde', 'detalles', 'tipo_actividad', 'recurso', 'alimento', 'recursos.costo as costo_r', 'alimentos.costo_kg as costo_a', 'nombre_siembra', 'siembras.estado as estado','cantidad_recurso')
		->where($c1, $op1, $c2)
		->where($c3, $op2, $c4)
		->where($c5, $op3, $c6)
		->where($c7, $op4, $c8)
		->where($c9, $op5, $c10)
		->where($c11, $op6, $c12)
		->where($c13, $op7, $c14)
		->get();
		//  return 'Hola';
		$acumula=0;
		$acumula2=0;
		$acumula3=0;

		if(count($recursosNecesarios)>0){
			for($i=0;$i<count($recursosNecesarios); $i++){

				$recursosNecesarios[$i]->costo_total_recurso = ($recursosNecesarios[$i]->cantidad_recurso * $recursosNecesarios[$i]->costo_r);
				$acumula+=($recursosNecesarios[$i]->cantidad_recurso * $recursosNecesarios[$i]->costo_r);

				$recursosNecesarios[$i]->costo_total_alimento = ($recursosNecesarios[$i]->cant_tarde + $recursosNecesarios[$i]->cant_manana) * $recursosNecesarios[$i]->costo_a;
				$acumula2+=(($recursosNecesarios[$i]->cant_tarde + $recursosNecesarios[$i]->cant_manana) * $recursosNecesarios[$i]->costo_a);

				$recursosNecesarios[$i]->costo_minutosh = $recursosNecesarios[$i]->minutos_hombre*$minutos_hombre->costo;
				$acumula3+=$recursosNecesarios[$i]->costo_minutosh;


				$recursosNecesarios[$i]->costo_total_actividad = ($acumula + 	$acumula2 + 	$acumula3);

				$recursosNecesarios[$i]->costo_r_acum = number_format($acumula, 2, ',', '');
				$recursosNecesarios[$i]->costo_a_acum = number_format($acumula2, 2, ',', '');
				$recursosNecesarios[$i]->costo_h_acum = number_format($acumula3, 2, ',', '');
				$recursosNecesarios[$i]->costo_total_recurso = number_format(	$recursosNecesarios[$i]->costo_total_recurso, 2, ',', '');
				$recursosNecesarios[$i]->costo_total_alimento = number_format(	$recursosNecesarios[$i]->costo_total_alimento, 2, ',', '');
				$recursosNecesarios[$i]->costo_total_actividad = number_format(	$recursosNecesarios[$i]->costo_total_actividad, 2, ',', '');
			}
		}

		return ['recursosNecesarios' => $recursosNecesarios ];
	}



	public function traerExistencias(){

		$existencias = EspecieSiembra::select(
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
		->join('siembras', 'especies_siembra.id_siembra', 'siembras.id' )
		->join('contenedores', 'siembras.id_contenedor', 'contenedores.id' )
		->join('especies', 'especies_siembra.id_especie', 'especies.id')
		->where('siembras.estado', '=', 1)
		->get();

		$var1 = 0;
		$var2 = 0;
		$bio_acum  = 0;
		$diff = 0 ;

		$registros = Registro::select()
			// ->join('siembras', 'registros.id_siembra', 'siembras.id' )->where('siembras.estado','=','1')
			->get();

		if(count($existencias)>0){

			for($i=0;$i<count($existencias); $i++){
				$existencias[$i]->biomasa_inicial = ((($existencias[$i]->peso_inicial)*($existencias[$i]->cantidad_inicial)) / 1000);
				$existencias[$i]->biomasa_disponible = ((($existencias[$i]->peso_actual)*($existencias[$i]->cant_actual)) / 1000);

				$bio_dispo = ((($existencias[$i]->peso_actual)*($existencias[$i]->cant_actual)) / 1000);

				for($j=0;$j<count($registros); $j++){

					if(count($registros)>0){
						if($existencias[$i]->id_siembra == $registros[$j]->id_siembra && $existencias[$i]->id_especie == $registros[$j]->id_especie ){

							$int_tiempo=Registro::select('fecha_registro')
								->orderBy('fecha_registro', 'desc')
								->where('id_siembra', $existencias[$i]->id_siembra)
								->where('id_especie', $existencias[$i]->id_especie)
								->first();
							$date1 = new \DateTime($existencias[$i]->fecha_inicio);
							$date2 = new \DateTime($int_tiempo->fecha_registro);
							$diff = $date1->diff($date2);
							$existencias[$i]->fecha_registro = $int_tiempo->fecha_registro;
							$existencias[$i]->intervalo_tiempo  = $diff->days;
							$existencias[$i]->salida_biomasa += $registros[$j]->biomasa;
							$bio_acum += $registros[$j]->biomasa;
							$existencias[$i]->biomasa_acumulada = number_format($bio_acum, 2, ',','');
							$existencias[$i]->mortalidad += $registros[$j]->mortalidad;
							$existencias[$i]->mortalidad_kg += (($registros[$j]->mortalidad *  $registros[$j]->peso_ganado)/1000) ;
							$existencias[$i]->mortalidad_porcentaje =  (number_format((($existencias[$i]->mortalidad * 100)/$existencias[$i]->cantidad_inicial),2, ',',''));
							$var2 = ($var1 * $existencias[$i]->peso_actual )/1000;
							$existencias[$i]->mortalidad_kg_au = (number_format(($var2),2,',',''));
							$existencias[$i]->salida_animales = (number_format((($existencias[$i]->salida_biomasa * 1000)/$existencias[$i]->peso_actual),2, ',',''));
							$sal_ani = (($existencias[$i]->salida_biomasa * 1000)/$existencias[$i]->peso_actual);
							$existencias[$i]->densidad_final = (number_format(($existencias[$i]->cant_actual/$existencias[$i]->capacidad),2, ',',''));
							$existencias[$i]->carga_final = (number_format(($bio_dispo/$existencias[$i]->capacidad), 2, ',',''));
							$existencias[$i]->peso_incremento = $existencias[$i]->peso_actual -  $existencias[$i]->peso_inicial;
							$existencias[$i]->incremento_biomasa = (($existencias[$i]->peso_incremento * $existencias[$i]->cant_actual)/1000);
							$existencias[$i]->ganancia_peso_dia = $existencias[$i]->peso_incremento/$existencias[$i]->intervalo_tiempo;
						}
					}
				}
				$existencias[$i]->mortalidad_kg =(number_format(($existencias[$i]->mortalidad_kg),2, ',',''));
				$existencias[$i]->biomasa_disponible = number_format($existencias[$i]->biomasa_disponible,1,',','');
				$existencias[$i]->incremento_biomasa = number_format($existencias[$i]->incremento_biomasa, 2,',','');
				$existencias[$i]->ganancia_peso_dia = number_format($existencias[$i]->ganancia_peso_dia,1,',','');
			}
		}
		return ['existencias'=> $existencias];
	}

	public function filtroExistencias(Request $request){
		$c1 = "siembras.id"; $op1 = '!='; $c2 = '-1';
		$c3 = "siembras.id"; $op2 = '!='; $c4 = '-1';
		$c5 = "siembras.id"; $op3 = '!='; $c6 = '-1';
		$c7 = "siembras.id"; $op4 = '!='; $c8 = '-1';
			 $op5 = '>='; $c10 = '0';
		$op6 = '>='; $c12 = '0';
				$op7 = '!='; $c14 = '-1';

		if($request['f_siembra']!='-1'){$c1="siembras.id"; $op1='='; $c2= $request['f_siembra'];}
		if($request['f_especie']!='-1'){$c3="especies.id"; $op2='='; $c4= $request['f_especie'];}
		if($request['f_inicio_d']!='-1'){$c5="fecha_inicio"; $op3='>='; $c6= $request['f_inicio_d'];}
		if($request['f_inicio_h']!='-1'){$c7="fecha_inicio"; $op4='<='; $c8= $request['f_inicio_h'];}
		if($request['f_peso_d']!='-1'){$op5='>='; $c10= $request['f_peso_d'];}
		if($request['f_peso_h']!='-1'){$op6='<='; $c12= $request['f_peso_h'];}
				if($request['f_lote']!='-1'){$op7='='; $c14= $request['f_lote'];}

		$existencias = EspecieSiembra::select(
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
		->join('siembras', 'especies_siembra.id_siembra', 'siembras.id' )
		->join('especies', 'especies_siembra.id_especie', 'especies.id')
		->join('contenedores', 'siembras.id_contenedor', 'contenedores.id' )
		// ->where('siembras.estado', '=', 1)
		->where($c1, $op1, $c2)
		->where($c3, $op2, $c4)
		->where($c5, $op3, $c6)
		->where($c7, $op4, $c8)
		->where('peso_actual', $op5, $c10)
		->where('peso_actual', $op6, $c12)
				->where('lote', $op7, $c14)
		->get();

		$var1 = 0;
		$var2 = 0;
		$bio_acum  = 0;
		$int_tiempo = 0;
		$registros = Registro::select()
			->join('siembras', 'registros.id_siembra', 'siembras.id' )
			// ->where('siembras.estado','=','1')
			->get();

		if(count($existencias)>0){

			for($i=0;$i<count($existencias); $i++){
				$existencias[$i]->biomasa_inicial = ((($existencias[$i]->peso_inicial)*($existencias[$i]->cantidad_inicial)) / 1000);
				$existencias[$i]->biomasa_disponible = ((($existencias[$i]->peso_actual)*($existencias[$i]->cant_actual)) / 1000);


				$bio_dispo = ((($existencias[$i]->peso_actual)*($existencias[$i]->cant_actual)) / 1000);

				for($j=0;$j<count($registros); $j++){

					if(count($registros)>0){
						if($existencias[$i]->id_siembra == $registros[$j]->id_siembra && $existencias[$i]->id_especie == $registros[$j]->id_especie ){

							$int_tiempo =Registro::select('fecha_registro')
								->orderBy('fecha_registro', 'desc')
								->where('id_siembra', $existencias[$i]->id_siembra)
								->where('id_especie', $existencias[$i]->id_especie)
								->first();
							$date1 = new \DateTime($existencias[$i]->fecha_inicio);
							$date2 = new \DateTime($int_tiempo->fecha_registro);
							$diff = $date1->diff($date2);
							$existencias[$i]->fecha_registro = $int_tiempo->fecha_registro;
							$existencias[$i]->intervalo_tiempo  = $diff->days;
							$existencias[$i]->salida_biomasa += $registros[$j]->biomasa;

							$bio_acum += $registros[$j]->biomasa;
							$existencias[$i]->biomasa_acumulada = number_format($bio_acum, 2, ',','');
							$existencias[$i]->mortalidad += $registros[$j]->mortalidad;
							$existencias[$i]->mortalidad_kg += (($registros[$j]->mortalidad *  $registros[$j]->peso_ganado)/1000) ;

							// $existencias[$i]->mortalidad_kg =  (number_format((($existencias[$i]->mortalidad * $existencias[$i]->peso_ganado)/1000),2, ',',''));
							$existencias[$i]->mortalidad_porcentaje =  (number_format((($existencias[$i]->mortalidad * 100)/$existencias[$i]->cantidad_inicial),2, ',',''));
							$var2 = ($var1 * $existencias[$i]->peso_actual )/1000;
							$existencias[$i]->mortalidad_kg_au = (number_format(($var2),2,',',''));
							$existencias[$i]->salida_animales = (number_format((($existencias[$i]->salida_biomasa * 1000)/$existencias[$i]->peso_actual),2, ',',''));
							$sal_ani = (($existencias[$i]->salida_biomasa * 1000)/$existencias[$i]->peso_actual);
							$existencias[$i]->densidad_final = (number_format(($existencias[$i]->cant_actual/$existencias[$i]->capacidad),2, ',',''));
							$existencias[$i]->carga_final = (number_format(($bio_dispo/$existencias[$i]->capacidad), 2, ',',''));
							$existencias[$i]->peso_incremento = $existencias[$i]->peso_actual -  $existencias[$i]->peso_inicial;
							$existencias[$i]->incremento_biomasa = (($existencias[$i]->peso_incremento * $existencias[$i]->cant_actual)/1000);
							$existencias[$i]->ganancia_peso_dia = $existencias[$i]->peso_incremento/$existencias[$i]->intervalo_tiempo;
						}
					}
				}
				$existencias[$i]->mortalidad_kg =(number_format(($existencias[$i]->mortalidad_kg),2, ',',''));
				$existencias[$i]->biomasa_disponible = number_format($existencias[$i]->biomasa_disponible,1,',','');
				$existencias[$i]->incremento_biomasa = number_format($existencias[$i]->incremento_biomasa, 2,',','');
				$existencias[$i]->ganancia_peso_dia = number_format($existencias[$i]->ganancia_peso_dia,1,',','');
			}
		}

		return ['existencias'=> $existencias];
	}
	public function traerExistenciasDetalle(){
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

		$existencias = EspecieSiembra::select(
					'cant_actual',
					'contenedor',
					'capacidad',
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
		->join('siembras', 'especies_siembra.id_siembra', 'siembras.id' )
		->join('contenedores', 'siembras.id_contenedor', 'contenedores.id' )
		->join('especies', 'especies_siembra.id_especie', 'especies.id')
		->where('siembras.estado', '=', 1)
		->get();
		$registros = Registro::select()
			->join('siembras', 'registros.id_siembra', 'siembras.id' )
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
			->leftJoin('alimentos', 'recursos_necesarios.id_alimento','alimentos.id')
			->leftJoin('recursos', 'recursos_necesarios.id_recurso', 'recursos.id')
			->get();

		$mh = Recursos::select()->where('recurso','Minutos hombre')->orWhere('recurso','Minuto hombre')->orWhere('recurso','Minutos')->first();
		$aux_regs = array();
		$diff = 0 ;

		if(count($siembras)>0){
			for($i=0;$i<count($siembras); $i++){
				// Especies en la siembra
				if(count($existencias)>0){
					$contador_esp=0;
					for($j=0;$j<count($existencias); $j++){
						$existencias[$j]->biomasa_disponible = ((($existencias[$j]->peso_actual)*($existencias[$j]->cant_actual)) / 1000);
						$existencias[$j]->biomasa_inicial =  ((($existencias[$j]->peso_inicial)*($existencias[$j]->cantidad_inicial)) / 1000);
						$bio_dispo = ((($existencias[$j]->peso_actual)*($existencias[$j]->cant_actual)) / 1000);

						if($siembras[$i]->id == $existencias[$j]->id_siembra ){
							$contador_esp++;
							$existencias[$j]->cant_actual_especie += $existencias[$j]->cant_actual;
							$siembras[$i]->cantidad_inicial += $existencias[$j]->cantidad_inicial;
							$siembras[$i]->peso_ini += $existencias[$j]->peso_inicial;
							$siembras[$i]->cant_actual += $existencias[$j]->cant_actual;
							$siembras[$i]->peso_actual += $existencias[$j]->peso_actual;
							$siembras[$i]->biomasa_inicial += $existencias[$j]->biomasa_inicial;
							$siembras[$i]->biomasa_disponible += $existencias[$j]->biomasa_disponible;

							for($k=0;$k<count($registros); $k++){

								if($existencias[$j]->id_siembra == $registros[$k]->id_siembra ){

									$int_tiempo =Registro::select('fecha_registro')
										->orderBy('fecha_registro', 'desc')
										->where('id_siembra', $existencias[$j]->id_siembra)
										->where('id_especie', $existencias[$j]->id_especie)
										->first();

									if(isset($int_tiempo['fecha_registro'])){
										$date1 = new \DateTime($existencias[$j]->fecha_inicio);
										$date2 = new \DateTime($int_tiempo['fecha_registro']);
										$diff = $date1->diff($date2);

										$existencias[$j]->intervalo_tiempo  = $diff->days;
									}else{
										$existencias[$j]->intervalo_tiempo  = 1;
									}

									$existencias[$j]->salida_biomasa += $registros[$k]->biomasa;

									if($existencias[$j]->id_especie == $registros[$k]->id_especie){
										$registros[$k]->mortalidad_kg = (($registros[$k]->mortalidad * $registros[$k]->peso_ganado)/1000);
										$existencias[$j]->mortalidad += $registros[$k]->mortalidad;
										$existencias[$j]->mortalidad_kg += $registros[$k]->mortalidad_kg;
										$existencias[$j]->salida_biomasa_especie += $registros[$k]->biomasa;
										$existencias[$j]->salida_animales = (($existencias[$j]->salida_biomasa_especie * 1000)/$existencias[$j]->peso_actual);
										$existencias[$j]->peso_incremento = $existencias[$j]->peso_actual -  $existencias[$j]->peso_inicial;
										$existencias[$j]->incremento_biomasa = (($existencias[$j]->peso_incremento * $existencias[$j]->cant_actual)/1000);
										$existencias[$j]->ganancia_peso_dia = $existencias[$j]->peso_incremento/$existencias[$j]->intervalo_tiempo;
										$existencias[$j]->mortalidad_porcentaje =  (($existencias[$j]->mortalidad*100)/$existencias[$j]->cantidad_inicial);

									}
								}
							}
							$siembras[$i]->mortalidad += $existencias[$j]->mortalidad;
							$siembras[$i]->mortalidad_kg += $existencias[$j]->mortalidad_kg;
							$siembras[$i]->mortalidad_porcentaje = (($siembras[$i]->mortalidad*100)/$siembras[$i]->cantidad_inicial);
							$siembras[$i]->salida_biomasa = $existencias[$j]->salida_biomasa;
							$siembras[$i]->salida_animales += $existencias[$j]->salida_animales;
							$siembras[$i]->incremento_biomasa += $existencias[$j]->incremento_biomasa;
							$siembras[$i]->intervalo_tiempo = $existencias[$j]->intervalo_tiempo;
							$siembras[$i]->porc_supervivencia_final = (( $siembras[$i]->salida_animales * 100) / $siembras[$i]->cantidad_inicial);
														$siembras[$i]->densidad_inicial =($siembras[$i]->cantidad_inicial/$siembras[$i]->capacidad);
							$siembras[$i]->densidad_final =($siembras[$i]->salida_animales/$siembras[$i]->capacidad);
														$siembras[$i]->carga_inicial = ($siembras[$i]->biomasa_inicial/$existencias[$j]->capacidad);
							$siembras[$i]->carga_final = ($siembras[$i]->salida_biomasa/$existencias[$j]->capacidad);
														$siembras[$i]->ganancia_peso_dia += $existencias[$j]->ganancia_peso_dia;

						}
					}
				}
				for($l=0;$l<count($recursos_necesarios); $l++){
					if($siembras[$i]->id == $recursos_necesarios[$l]->id_siembra){
						$siembras[$i]->minutos_hombre += $recursos_necesarios[$l]->minutos_hombre;
						$siembras[$i]->horas_hombre = $siembras[$i]->minutos_hombre/60;
						$recursos_necesarios[$l]->costo_total_recurso =  $recursos_necesarios[$l]->cantidad_recurso *  $recursos_necesarios[$l]->costo_recurso;
						$siembras[$i]->costo_total_recurso += $recursos_necesarios[$l]->costo_total_recurso;
						$recursos_necesarios[$l]->cantidad_total_alimento = $recursos_necesarios[$l]->cant_tarde + $recursos_necesarios[$l]->cant_manana;
						$siembras[$i]->cantidad_total_alimento +=  $recursos_necesarios[$l]->cantidad_total_alimento;
						$recursos_necesarios[$l]->costo_total_alimento = ($recursos_necesarios[$l]->cant_tarde + $recursos_necesarios[$l]->cant_manana)*$recursos_necesarios[$l]->costo_alimento;
						$siembras[$i]->costo_total_alimento += $recursos_necesarios[$l]->costo_total_alimento;

						if($recursos_necesarios[$l]->conv_alimenticia > 0){
							$recursos_necesarios[$l]->incr_bio_acum_conver = $recursos_necesarios[$l]->cantidad_total_alimento / $recursos_necesarios[$l]->conv_alimenticia;
							$recursos_necesarios[$l]->conv_alimenticia = number_format($recursos_necesarios[$l]->conv_alimenticia,2,',','');
							$siembras[$i]->incr_bio_acum_conver +=  $recursos_necesarios[$l]->incr_bio_acum_conver;
						}
					}
				}
				$siembras[$i]->costo_minutos_hombre += ($siembras[$i]->minutos_hombre * $mh->costo );
				$siembras[$i]->costo_total_siembra = ($siembras[$i]->costo_minutos_hombre + $siembras[$i]->costo_total_alimento + $siembras[$i]->costo_total_recurso);

				if(($siembras[$i]->salida_biomasa)>0){
					$siembras[$i]->costo_produccion_final = $siembras[$i]->costo_total_siembra / $siembras[$i]->salida_biomasa;
				}else{
					$siembras[$i]->costo_produccion_final = 0;
				}

				if( $siembras[$i]->incremento_biomasa>0){
					$siembras[$i]->conversion_alimenticia_siembra = $siembras[$i]->cantidad_total_alimento /  $siembras[$i]->incremento_biomasa;
				}
				if(($siembras[$i]->biomasa_disponible - $siembras[$i]->biomasa_inicial)>0){
					$siembras[$i]->conversion_alimenticia_parcial = $siembras[$i]->cantidad_total_alimento / ($siembras[$i]->biomasa_disponible - $siembras[$i]->biomasa_inicial);
				}
				else{
					$siembras[$i]->conversion_alimenticia_parcial = 0;
				}
				if(($siembras[$i]->salida_biomasa + $siembras[$i]->mortalidad_kg - $siembras[$i]->biomasa_inicial)>0){
					$siembras[$i]->conversion_final = (($siembras[$i]->cantidad_total_alimento)/($siembras[$i]->salida_biomasa + $siembras[$i]->mortalidad_kg - $siembras[$i]->biomasa_inicial));
				}else{
					$siembras[$i]->conversion_final = 0;
				}
				$siembras[$i]->bio_dispo_conver = ($siembras[$i]->biomasa_inicial + $siembras[$i]->incr_bio_acum_conver) - ($siembras[$i]->biomasa_disponible + $siembras[$i]->mortalidad_kg);
				// if($siembras[$i]->salida_animales>0 && $siembras[$i]->intervalo_tiempo>0){
				//     $siembras[$i]->ganancia_peso_dia = ((($siembras[$i]->salida_biomasa*1000)/$siembras[$i]->salida_animales)/$siembras[$i]->intervalo_tiempo);
				// }
				$siembras[$i]->contador_esp = $contador_esp;
				if(($siembras[$i]->contador_esp)>0){
					$siembras[$i]->peso_inicial = $siembras[$i]->peso_ini/$siembras[$i]->contador_esp;
				}else{
					$siembras[$i]->peso_inicial = 0;
				}
				if (($siembras[$i]->contador_esp)>0) {
					$siembras[$i]->peso_actual_esp = $siembras[$i]->peso_actual/$siembras[$i]->contador_esp;
				}else{
					$siembras[$i]->peso_actual_esp = 0;
				}

				$siembras[$i]->conversion_alimenticia_siembra = number_format($siembras[$i]->conversion_alimenticia_siembra,2,',','');
				$siembras[$i]->biomasa_disponible = number_format($siembras[$i]->biomasa_disponible,2,',','');
				$siembras[$i]->mortalidad_kg = number_format($siembras[$i]->mortalidad_kg,2,',','');
				$siembras[$i]->salida_animales = number_format($siembras[$i]->salida_animales,2,',','');
								$siembras[$i]->densidad_inicial = (number_format(( $siembras[$i]->densidad_inicial),2, ',',''));
				$siembras[$i]->densidad_final = (number_format(( $siembras[$i]->densidad_final),2, ',',''));
				$siembras[$i]->incremento_biomasa = number_format($siembras[$i]->incremento_biomasa,2,',','');
				$siembras[$i]->bio_dispo_conver = number_format($siembras[$i]->bio_dispo_conver,2,',','');
				$siembras[$i]->incr_bio_acum_conver = number_format($siembras[$i]->incr_bio_acum_conver,2,',','');
				$siembras[$i]->ganancia_peso_dia = number_format($siembras[$i]->ganancia_peso_dia,2,',','');
				$siembras[$i]->peso_inicial = number_format($siembras[$i]->peso_inicial,2,',','');
				$siembras[$i]->mortalidad_porcentaje = number_format($siembras[$i]->mortalidad_porcentaje,2,',','');
				$siembras[$i]->peso_actual_esp = number_format($siembras[$i]->peso_actual_esp,2,',','');
				$siembras[$i]->horas_hombre = number_format($siembras[$i]->horas_hombre,2,',','');
				$siembras[$i]->conversion_alimenticia_parcial = number_format($siembras[$i]->conversion_alimenticia_parcial,2,',','');
				$siembras[$i]->conversion_final = number_format($siembras[$i]->conversion_final,2,',','');
				$siembras[$i]->porc_supervivencia_final = number_format($siembras[$i]->porc_supervivencia_final,2,',','');
								$siembras[$i]->carga_inicial = number_format($siembras[$i]->carga_inicial,2,',','');
				$siembras[$i]->carga_final = number_format($siembras[$i]->carga_final,2,',','');
				$siembras[$i]->costo_total_recurso = number_format($siembras[$i]->costo_total_recurso,2,',','');
				$siembras[$i]->costo_total_siembra = number_format($siembras[$i]->costo_total_siembra,2,',','');
				$siembras[$i]->costo_produccion_final = number_format($siembras[$i]->costo_produccion_final,2,',','');
				// recursos_necesarios
				$aux_regs[]=[
					"biomasa_inicial" => $siembras[$i]->biomasa_inicial,
					"biomasa_disponible" => $siembras[$i]->biomasa_disponible,
					'bio_dispo_conver' =>$siembras[$i]->bio_dispo_conver,
										"carga_inicial" => $siembras[$i]->carga_inicial,
					"carga_final" => $siembras[$i]->carga_final,
					"cantidad_inicial" => $siembras[$i]->cantidad_inicial,
					"cant_actual" => $siembras[$i]->cant_actual,
					'capacidad' => $siembras[$i]->capacidad,
					'conversion_final' => $siembras[$i]->conversion_final,
					"costo_minutosh" => $siembras[$i]->costo_minutos_hombre ,
					"costo_total_recurso" => $siembras[$i]->costo_total_recurso ,
					'cantidad_total_alimento' => $siembras[$i]->cantidad_total_alimento,
					"costo_total_alimento" => $siembras[$i]->costo_total_alimento,
					"costo_tot" => $siembras[$i]->costo_total_siembra,
					"costo_produccion_final" => $siembras[$i]->costo_produccion_final,
					'conversion_alimenticia_siembra' => $siembras[$i]->conversion_alimenticia_siembra,
					'conversion_alimenticia_parcial' => $siembras[$i]->conversion_alimenticia_parcial,
										"densidad_inicial" => $siembras[$i]->densidad_inicial,
					"densidad_final" => $siembras[$i]->densidad_final,
					'ganancia_peso_dia'=>$siembras[$i]->ganancia_peso_dia,
					"fecha_inicio" => $siembras[$i]->fecha_inicio,
					"horas_hombre" => $siembras[$i]->horas_hombre,
					"minutos_hombre" => $siembras[$i]->minutos_hombre,
					'incr_bio_acum_conver' =>$siembras[$i]->incr_bio_acum_conver,
					'incremento_biomasa' => $siembras[$i]->incremento_biomasa,
					'intervalo_tiempo' => $siembras[$i]->intervalo_tiempo,
					"mortalidad" => $siembras[$i]->mortalidad,
					"mortalidad_kg" => $siembras[$i]->mortalidad_kg,
					"mortalidad_porcentaje" => $siembras[$i]->mortalidad_porcentaje ,
					"nombre_siembra"=>$siembras[$i]->nombre_siembra,
					"peso_inicial" => $siembras[$i]->peso_inicial,
					"peso_actual"=>$siembras[$i]->peso_actual_esp,
					"salida_animales"=>$siembras[$i]->salida_animales,
					"salida_biomasa" => $siembras[$i]->salida_biomasa,
					"porc_supervivencia_final" => $siembras[$i]->porc_supervivencia_final
				];


			}
		}


		return ['existencias'=> $aux_regs];
	}

	public function filtroExistenciasDetalle(Request $request){

		$c1 = "siembras.id"; $op1 = '!='; $c2 = '-1';
		$op2 = '!='; $c4 = '-1';
		$signCont = '!='; $idContenedor = '-1';

		if($request['f_siembra']!='-1'){$c1="siembras.id"; $op1='='; $c2= $request['f_siembra'];}
		if($request['f_contenedor']!='-1'){$signCont='='; $idContenedor= $request['f_contenedor'];}
		if($request['f_estado']!='-1'){ $op2='='; $c4= $request['f_estado'];}


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
		->where('id_contenedor', $signCont, $idContenedor )
		->where('siembras.estado', $op2, $c4)
		->join('contenedores', 'siembras.id_contenedor', 'contenedores.id')
		->get();

		$existencias = EspecieSiembra::select(
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
		->join('siembras', 'especies_siembra.id_siembra', 'siembras.id' )
		->join('contenedores', 'siembras.id_contenedor', 'contenedores.id' )
		->join('especies', 'especies_siembra.id_especie', 'especies.id')
		// ->where('siembras.estado', '=', 1)
		->get();
		$registros = Registro::select()
			->join('siembras', 'registros.id_siembra', 'siembras.id' )
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
			->leftJoin('alimentos', 'recursos_necesarios.id_alimento','alimentos.id')
			->leftJoin('recursos', 'recursos_necesarios.id_recurso', 'recursos.id')
			->get();

		$mh = Recursos::select()->where('recurso','Minutos hombre')->orWhere('recurso','Minuto hombre')->orWhere('recurso','Minutos')->first();

		$aux_regs = array();
		$diff = 0 ;

		if(count($siembras)>0){
			for($i=0;$i<count($siembras); $i++){
				// Especies en la siembra
				if(count($existencias)>0){
					$contador_esp=0;
					for($j=0;$j<count($existencias); $j++){
						$existencias[$j]->biomasa_disponible = ((($existencias[$j]->peso_actual)*($existencias[$j]->cant_actual)) / 1000);
						$existencias[$j]->biomasa_inicial =  ((($existencias[$j]->peso_inicial)*($existencias[$j]->cantidad_inicial)) / 1000);
						$bio_dispo = ((($existencias[$j]->peso_actual)*($existencias[$j]->cant_actual)) / 1000);

						if($siembras[$i]->id == $existencias[$j]->id_siembra ){
							$contador_esp++;
							$existencias[$j]->cant_actual_especie += $existencias[$j]->cant_actual;
							$siembras[$i]->cantidad_inicial += $existencias[$j]->cantidad_inicial;
							$siembras[$i]->peso_ini += $existencias[$j]->peso_inicial;
							$siembras[$i]->cant_actual += $existencias[$j]->cant_actual;
							$siembras[$i]->peso_actual += $existencias[$j]->peso_actual;
							$siembras[$i]->biomasa_inicial += $existencias[$j]->biomasa_inicial;
							$siembras[$i]->biomasa_disponible += $existencias[$j]->biomasa_disponible;

							for($k=0;$k<count($registros); $k++){

								if($existencias[$j]->id_siembra == $registros[$k]->id_siembra ){

									$int_tiempo =Registro::select('fecha_registro')
										->orderBy('fecha_registro', 'desc')
										->where('id_siembra', $existencias[$j]->id_siembra)
										->where('id_especie', $existencias[$j]->id_especie)
										->first();
									$date1 = new \DateTime($existencias[$j]->fecha_inicio);
									$date2 = new \DateTime($int_tiempo['fecha_registro']);
									$diff = $date1->diff($date2);

									$existencias[$j]->intervalo_tiempo  = $diff->days;
									$existencias[$j]->salida_biomasa += $registros[$k]->biomasa;

									if($existencias[$j]->id_especie == $registros[$k]->id_especie){
										$registros[$k]->mortalidad_kg = (($registros[$k]->mortalidad * $registros[$k]->peso_ganado)/1000);
										$existencias[$j]->mortalidad += $registros[$k]->mortalidad;
										$existencias[$j]->mortalidad_kg += $registros[$k]->mortalidad_kg;
										$existencias[$j]->salida_biomasa_especie += $registros[$k]->biomasa;
										$existencias[$j]->salida_animales = (($existencias[$j]->salida_biomasa_especie * 1000)/$existencias[$j]->peso_actual);
										$existencias[$j]->peso_incremento = $existencias[$j]->peso_actual -  $existencias[$j]->peso_inicial;
										$existencias[$j]->incremento_biomasa = (($existencias[$j]->peso_incremento * $existencias[$j]->cant_actual)/1000);
										$existencias[$j]->ganancia_peso_dia = $existencias[$j]->peso_incremento/$existencias[$j]->intervalo_tiempo;
										$existencias[$j]->mortalidad_porcentaje =  (($existencias[$j]->mortalidad*100)/$existencias[$j]->cantidad_inicial);
									}
								}
							}
							$siembras[$i]->mortalidad += $existencias[$j]->mortalidad;
							$siembras[$i]->mortalidad_kg += $existencias[$j]->mortalidad_kg;
							$siembras[$i]->mortalidad_porcentaje = (($siembras[$i]->mortalidad*100)/$siembras[$i]->cantidad_inicial);
							$siembras[$i]->salida_biomasa = $existencias[$j]->salida_biomasa;
							$siembras[$i]->salida_animales += $existencias[$j]->salida_animales;
							$siembras[$i]->incremento_biomasa += $existencias[$j]->incremento_biomasa;
							$siembras[$i]->intervalo_tiempo = $existencias[$j]->intervalo_tiempo;
							$siembras[$i]->porc_supervivencia_final = (( $siembras[$i]->salida_animales * 100) / $siembras[$i]->cantidad_inicial);
														$siembras[$i]->densidad_inicial =($siembras[$i]->cantidad_inicial/$siembras[$i]->capacidad);
							$siembras[$i]->densidad_final =($siembras[$i]->salida_animales/$siembras[$i]->capacidad);
														$siembras[$i]->carga_inicial = ($siembras[$i]->biomasa_inicial/$existencias[$j]->capacidad);
							$siembras[$i]->carga_final = ($siembras[$i]->salida_biomasa/$existencias[$j]->capacidad);
														$siembras[$i]->ganancia_peso_dia += $existencias[$j]->ganancia_peso_dia;
						}
					}
				}
				 for($l=0;$l<count($recursos_necesarios); $l++){
					if($siembras[$i]->id == $recursos_necesarios[$l]->id_siembra){
						$siembras[$i]->minutos_hombre += $recursos_necesarios[$l]->minutos_hombre;
						$siembras[$i]->horas_hombre = $siembras[$i]->minutos_hombre/60;
						$recursos_necesarios[$l]->costo_total_recurso =  $recursos_necesarios[$l]->cantidad_recurso *  $recursos_necesarios[$l]->costo_recurso;
						$siembras[$i]->costo_total_recurso += $recursos_necesarios[$l]->costo_total_recurso;
						$recursos_necesarios[$l]->cantidad_total_alimento = $recursos_necesarios[$l]->cant_tarde + $recursos_necesarios[$l]->cant_manana;
						$siembras[$i]->cantidad_total_alimento +=  $recursos_necesarios[$l]->cantidad_total_alimento;
						$recursos_necesarios[$l]->costo_total_alimento = ($recursos_necesarios[$l]->cant_tarde + $recursos_necesarios[$l]->cant_manana)*$recursos_necesarios[$l]->costo_alimento;
						$siembras[$i]->costo_total_alimento += $recursos_necesarios[$l]->costo_total_alimento;

						if($recursos_necesarios[$l]->conv_alimenticia > 0){
							$recursos_necesarios[$l]->incr_bio_acum_conver = $recursos_necesarios[$l]->cantidad_total_alimento / $recursos_necesarios[$l]->conv_alimenticia;
							$recursos_necesarios[$l]->conv_alimenticia = number_format($recursos_necesarios[$l]->conv_alimenticia,2,',','');
							$siembras[$i]->incr_bio_acum_conver +=  $recursos_necesarios[$l]->incr_bio_acum_conver;
						}
					}
				 }
				 $siembras[$i]->costo_minutos_hombre += ($siembras[$i]->minutos_hombre * $mh->costo );
				 $siembras[$i]->costo_total_siembra = ($siembras[$i]->costo_minutos_hombre + $siembras[$i]->costo_total_alimento + $siembras[$i]->costo_total_recurso);

				 if(($siembras[$i]->salida_biomasa)>0){
					$siembras[$i]->costo_produccion_final = $siembras[$i]->costo_total_siembra / $siembras[$i]->salida_biomasa;
				}else{
					$siembras[$i]->costo_produccion_final = 0;
				}

				 if( $siembras[$i]->incremento_biomasa>0){
					$siembras[$i]->conversion_alimenticia_siembra = $siembras[$i]->cantidad_total_alimento /  $siembras[$i]->incremento_biomasa;
				}

				$siembras[$i]->bio_dispo_conver = ($siembras[$i]->biomasa_inicial + $siembras[$i]->incr_bio_acum_conver) - ($siembras[$i]->biomasa_disponible + $siembras[$i]->mortalidad_kg);
				if (($siembras[$i]->biomasa_disponible - $siembras[$i]->biomasa_inicial)>0) {
					$siembras[$i]->conversion_alimenticia_parcial = $siembras[$i]->cantidad_total_alimento / ($siembras[$i]->biomasa_disponible - $siembras[$i]->biomasa_inicial);
				}else{
					$siembras[$i]->conversion_alimenticia_parcial = 0;
				}
				if (($siembras[$i]->salida_biomasa + $siembras[$i]->mortalidad_kg - $siembras[$i]->biomasa_inicial)>0) {
					$siembras[$i]->conversion_final = (($siembras[$i]->cantidad_total_alimento)/($siembras[$i]->salida_biomasa + $siembras[$i]->mortalidad_kg - $siembras[$i]->biomasa_inicial));
				} else {
					$siembras[$i]->conversion_final = 0;
				}

				$siembras[$i]->contador_esp = $contador_esp;
				if (($siembras[$i]->contador_esp)>0) {
					$siembras[$i]->peso_inicial = $siembras[$i]->peso_ini/$siembras[$i]->contador_esp;
				} else {
					$siembras[$i]->peso_inicial = 0;
				}

				if (($siembras[$i]->contador_esp)>0) {
					$siembras[$i]->peso_actual_esp = $siembras[$i]->peso_actual/$siembras[$i]->contador_esp;
				} else {
					$siembras[$i]->peso_actual_esp = 0;
				}

				$siembras[$i]->conversion_alimenticia_siembra = number_format($siembras[$i]->conversion_alimenticia_siembra,2,',','');
				$siembras[$i]->biomasa_disponible = number_format($siembras[$i]->biomasa_disponible,2,',','');
				$siembras[$i]->mortalidad_kg = number_format($siembras[$i]->mortalidad_kg,2,',','');
				$siembras[$i]->salida_animales = number_format($siembras[$i]->salida_animales,2,',','');
				$siembras[$i]->incremento_biomasa = number_format($siembras[$i]->incremento_biomasa,2,',','');
				$siembras[$i]->bio_dispo_conver = number_format($siembras[$i]->bio_dispo_conver,2,',','');
				$siembras[$i]->incr_bio_acum_conver = number_format($siembras[$i]->incr_bio_acum_conver,2,',','');
				$siembras[$i]->ganancia_peso_dia = number_format($siembras[$i]->ganancia_peso_dia,2,',','');
				$siembras[$i]->peso_inicial = number_format($siembras[$i]->peso_inicial,2,',','');
				$siembras[$i]->mortalidad_porcentaje = number_format($siembras[$i]->mortalidad_porcentaje,2,',','');
				$siembras[$i]->peso_actual_esp = number_format($siembras[$i]->peso_actual_esp,2,',','');
				$siembras[$i]->horas_hombre = number_format($siembras[$i]->horas_hombre,2,',','');
				$siembras[$i]->conversion_alimenticia_parcial = number_format($siembras[$i]->conversion_alimenticia_parcial,2,',','');
				$siembras[$i]->conversion_final = number_format($siembras[$i]->conversion_final,2,',','');
				$siembras[$i]->porc_supervivencia_final = number_format($siembras[$i]->porc_supervivencia_final,2,',','');
								$siembras[$i]->densidad_inicial = number_format($siembras[$i]->densidad_inicial,2,',','');
				$siembras[$i]->densidad_final = number_format($siembras[$i]->densidad_final,2,',','');
								$siembras[$i]->carga_inicial = number_format($siembras[$i]->carga_inicial,2,',','');
				$siembras[$i]->carga_final = number_format($siembras[$i]->carga_final,2,',','');
				$siembras[$i]->costo_total_recurso = number_format($siembras[$i]->costo_total_recurso,2,',','');
				$siembras[$i]->costo_produccion_final = number_format($siembras[$i]->costo_produccion_final,2,',','');
				$siembras[$i]->costo_total_siembra = number_format($siembras[$i]->costo_total_siembra,2,',','');

				// recursos_necesarios
				$aux_regs[]=[
					"biomasa_inicial" => $siembras[$i]->biomasa_inicial,
					"biomasa_disponible" => $siembras[$i]->biomasa_disponible,
					'bio_dispo_conver' =>$siembras[$i]->bio_dispo_conver,
					"carga_inicial" => $siembras[$i]->carga_inicial,
					"carga_final" => $siembras[$i]->carga_final,
					"cantidad_inicial" => $siembras[$i]->cantidad_inicial,
					"cant_actual" => $siembras[$i]->cant_actual,
					'capacidad' => $siembras[$i]->capacidad,
					'conversion_final' => $siembras[$i]->conversion_final,
					"costo_minutosh" => $siembras[$i]->costo_minutos_hombre ,
					"costo_total_recurso" => $siembras[$i]->costo_total_recurso,
					'cantidad_total_alimento' => $siembras[$i]->cantidad_total_alimento,
					"costo_total_alimento" => $siembras[$i]->costo_total_alimento,
					"costo_tot" => $siembras[$i]->costo_total_siembra,
					"costo_produccion_final" => $siembras[$i]->costo_produccion_final,
					'conversion_alimenticia_parcial' => $siembras[$i]->conversion_alimenticia_parcial,
					'conversion_alimenticia_siembra' => $siembras[$i]->conversion_alimenticia_siembra,
										"densidad_inicial" => $siembras[$i]->densidad_inicial,
					"densidad_final" => $siembras[$i]->densidad_final,
					'ganancia_peso_dia'=>$siembras[$i]->ganancia_peso_dia,
					"fecha_inicio" => $siembras[$i]->fecha_inicio,
					"horas_hombre" => $siembras[$i]->horas_hombre,
					"minutos_hombre" => $siembras[$i]->minutos_hombre,
					'incr_bio_acum_conver' =>$siembras[$i]->incr_bio_acum_conver,
					'incremento_biomasa' => $siembras[$i]->incremento_biomasa,
					'intervalo_tiempo' => $siembras[$i]->intervalo_tiempo,
					"mortalidad" => $siembras[$i]->mortalidad,
					"mortalidad_kg" => $siembras[$i]->mortalidad_kg,
					"mortalidad_porcentaje" => $siembras[$i]->mortalidad_porcentaje ,
					"nombre_siembra"=>$siembras[$i]->nombre_siembra,
					"peso_inicial" => $siembras[$i]->peso_inicial,
					"peso_actual"=>$siembras[$i]->peso_actual_esp,
					"salida_animales"=>$siembras[$i]->salida_animales,
					"salida_biomasa" => $siembras[$i]->salida_biomasa,
					"porc_supervivencia_final" => $siembras[$i]->porc_supervivencia_final
				];
			}
		}
		return ['existencias'=> $aux_regs];
	}
}

