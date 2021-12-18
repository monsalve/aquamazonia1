<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\EspecieSiembra;
use App\Siembra;
use App\Contenedor;
use App\Registro;
use Illuminate\Support\Facades\DB;


class InformeSiembraController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    //
    $siembras = Siembra::select('siembras.id as id', 'nombre_siembra', 'id_contenedor', 'contenedor', 'fecha_inicio', 'ini_descanso', 'fin_descanso', 'siembras.estado as estado', 'fecha_alimento', 'lote', 'cant_actual', 'especie', 'peso_actual', 'especies.id as id_esp')
      ->join('especies_siembra', 'siembras.id', 'especies_siembra.id_siembra')
      ->join('especies', 'especies_siembra.id_especie', 'especies.id')
      ->join('contenedores', 'siembras.id_contenedor', 'contenedores.id')
      ->where('siembras.estado', '=', 1)
      ->orderBy('nombre_siembra', 'desc')
      ->get();
    $mortalidad_siembra = array();
    $cantidad_pesca = array();
    foreach ($siembras as $s) {
      $aux_mortalidad = Registro::select(
        'id_especie',
        DB::raw('SUM(cantidad) as cantidad_pesca'),
        DB::raw('SUM(biomasa) as salida_biomasa'),
        DB::raw('SUM(mortalidad) as mortalidad')
      )
        ->where('id_siembra', '=', $s->id)
        ->where('estado', '=', '1')
        // ->where('tipo_registro','!=','1')
        ->groupBy('id_especie')
        ->get();

      foreach ($aux_mortalidad as $a_mort) {
        $mortalidad_siembra[$s->id][$a_mort->id_especie] =  $a_mort->mortalidad;
        $cantidad_pesca[$s->id][$a_mort->id_especie] =  $a_mort->cantidad_pesca;
      }
    }

    $fecha_actual = date('Y-m-d');
    $lotes = EspecieSiembra::select('lote')->distinct()->get();

    return [
      "siembras" => $siembras,
      'lotes' => $lotes,
      'fecha_actual' => $fecha_actual,
      'mortalidad_siembra' => $mortalidad_siembra,
      'cantidad_pesca' => $cantidad_pesca
    ];
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
  }
  public function filtroSiembras(Request $request)
  {
    $id_siembra = "siembras.id";
    $operador_id_siembra = '!=';
    $filtro_id_siembra = '-1';
    $especie_id = "siembras.id";
    $operador_especie_id = '!=';
    $filtro_id_especie = '-1';
    $lote = "siembras.id";
    $op3 = '!=';
    $filtro_lote = '-1';
    $c7 = "siembras.id";
    $op4 = '!=';
    $filtro_fecha_desde = '-1';
    $fecha_inicio = "siembras.id";
    $operador_fecha_inicio = '!=';
    $filtro_fecha_inicio = '-1';
    $estado_siembra = "siembras.id";
    $operador_estado_siembra = '!=';
    $filtro_estado_siembra = '-1';

    if ($request['f_siembra'] != '-1') {
      $id_siembra = "siembras.id";
      $operador_id_siembra = '=';
      $filtro_id_siembra = $request['f_siembra'];
    }
    if ($request['f_especie'] != '-1') {
      $especie_id = "especies.id";
      $operador_especie_id = '=';
      $filtro_id_especie = $request['f_especie'];
    }
    if ($request['f_lote'] != '-1') {
      $lote = "lote";
      $op3 = '=';
      $filtro_lote = $request['f_lote'];
    }
    if ($request['f_inicio_d'] != '-1') {
      $c7 = "fecha_inicio";
      $op4 = '>=';
      $filtro_fecha_desde = $request['f_inicio_d'];
    }
    if ($request['f_inicio_h'] != '-1') {
      $fecha_inicio = "fecha_inicio";
      $operador_fecha_inicio = '<=';
      $filtro_fecha_inicio = $request['f_inicio_h'];
    }
    if ($request['f_estado_s'] != '-1') {
      $estado_siembra = "siembras.estado";
      $operador_estado_siembra = '=';
      $filtro_estado_siembra = $request['f_estado_s'];
    }

    $siembras = Siembra::select('siembras.id as id', 'nombre_siembra', 'id_contenedor', 'contenedor', 'fecha_inicio', 'ini_descanso', 'fin_descanso', 'siembras.estado as estado', 'lote', 'especies.id', 'especie', 'cantidad', 'peso_inicial', 'cant_actual', 'peso_actual', 'fecha_alimento')
      ->join('contenedores', 'siembras.id_contenedor', 'contenedores.id')
      ->join('especies_siembra', 'siembras.id', 'especies_siembra.id_siembra')
      ->join('especies', 'especies_siembra.id_especie', 'especies.id')
      ->where($id_siembra, $operador_id_siembra, $filtro_id_siembra)
      ->where($especie_id, $operador_especie_id, $filtro_id_especie)
      ->where($lote, $op3, $filtro_lote)
      ->where($c7, $op4, $filtro_fecha_desde)
      ->where($fecha_inicio, $operador_fecha_inicio, $filtro_fecha_inicio)
      ->where($estado_siembra, $operador_estado_siembra, $filtro_estado_siembra)
      ->orderBy('nombre_siembra', 'desc')
      ->get();

    return ['siembras' => $siembras];
  }
}
