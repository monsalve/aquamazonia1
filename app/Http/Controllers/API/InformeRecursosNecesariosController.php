<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\RecursoNecesario;
use App\RecursoSiembra;
use App\Alimento;
use App\Recursos;
use App\Siembra;
use App\Actividad;
use Illuminate\Support\Facades\DB;
use SebastianBergmann\Environment\Console;

class InformeRecursosNecesariosController extends Controller
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

    $recursosNecesarios = RecursoNecesario::select(
        'actividad',
        'id_siembra',
        'id_contenedor',
        'tipo_actividad',
        'nombre_siembra',
        'siembras.estado as estado',
        DB::raw('SUM(cantidad_recurso) as cantidad_recurso'),
        DB::raw('SUM(cant_manana) as c_manana'),
        DB::raw('SUM(cant_tarde) as c_tarde'),
        DB::raw('SUM(minutos_hombre) as minutos_hombre'),
        DB::raw('SUM(horas_hombre) as horas_hombre'),
      )
      ->join('recursos_siembras', 'recursos_necesarios.id', 'recursos_siembras.id_registro')
      ->join('siembras', 'recursos_siembras.id_siembra', 'siembras.id')
      ->join('actividades', 'recursos_necesarios.tipo_actividad', 'actividades.id')
      ->groupBy('siembras.nombre_siembra')
      ->groupBy('siembras.estado')
      ->groupBy('recursos_siembras.id_siembra')
      ->groupBy('siembras.id_contenedor')
      ->groupBy('recursos_necesarios.tipo_actividad')
      ->groupBy('actividades.actividad')
      ->orderBy('nombre_siembra', 'desc')
      ->paginate(30);


    foreach ($recursosNecesarios as $recursoNecesario) {

      $costo_recursos = RecursoNecesario::select()
        ->join('actividades', 'recursos_necesarios.tipo_actividad', 'actividades.id')
        ->join('recursos_siembras', 'recursos_necesarios.id', 'recursos_siembras.id_registro')
        ->leftJoin('alimentos', 'recursos_necesarios.id_alimento', 'alimentos.id')
        ->leftJoin('recursos', 'recursos_necesarios.id_recurso', 'recursos.id')
        ->join('siembras', 'recursos_siembras.id_siembra', 'siembras.id')
        ->get();

      foreach ($costo_recursos as $costo_recurso) {

        if ($recursoNecesario->id_siembra == $costo_recurso->id_siembra) {


          $recursoNecesario->minutosHombre += $costo_recurso->minutos_hombre;

          $recursoNecesario->costoRecurso +=  ($costo_recurso->cantidad_recurso * $costo_recurso->costo);
          $recursoNecesario->cantidadAlimento += ($costo_recurso->cant_tarde + $costo_recurso->cant_manana);
          $recursoNecesario->costoAlimento +=  (($costo_recurso->cant_tarde + $costo_recurso->cant_manana) * $costo_recurso->costo_kg);
          $recursoNecesario->costoMinutos = ($recursoNecesario->minutosHombre) * $minutos_hombre->costo;
          $recursoNecesario->costoTotalSiembra = $recursoNecesario->costoRecurso + $recursoNecesario->costoMinutos + $recursoNecesario->costoAlimento;

          if ($recursoNecesario->tipo_actividad == $costo_recurso->tipo_actividad) {
            $recursoNecesario->costo_recurso +=  ($costo_recurso->cantidad_recurso * $costo_recurso->costo);
            $recursoNecesario->cantidad_alimento += ($costo_recurso->cant_tarde + $costo_recurso->cant_manana);
            $recursoNecesario->costo_alimento +=  (($costo_recurso->cant_tarde + $costo_recurso->cant_manana) * $costo_recurso->costo_kg);
            $recursoNecesario->costo_minutos = ($recursoNecesario->minutos_hombre) * $minutos_hombre->costo;
            $recursoNecesario->costo_total_actividad = $recursoNecesario->costo_recurso + $recursoNecesario->costo_minutos + $recursoNecesario->costo_alimento;
          }

          $recursoNecesario->porcentaje_total_produccion = ($recursoNecesario->costo_total_actividad * 100) / $recursoNecesario->costoTotalSiembra;
        }
      }

      $recursoNecesario->horas_hombre = number_format($recursoNecesario->minutos_hombre / 60, 2, ',', '');
      $recursoNecesario->costo_recurso = number_format($recursoNecesario->costo_recurso, 2, ',', '');
      $recursoNecesario->costo_total_actividad = number_format($recursoNecesario->costo_total_actividad, 2, ',', '');
      $recursoNecesario->porcentaje_total_produccion = number_format($recursoNecesario->porcentaje_total_produccion, 2, ',', '');
    }

    return ['recursosNecesarios' => $recursosNecesarios];
  }

  public function filtroRecursos(Request $request)
  {
    //
    $minutos_hombre = Recursos::select()->where('recurso', 'Minutos hombre')->orWhere('recurso', 'Minuto hombre')->orWhere('recurso', 'Minutos')->first();

    $id_siembra = 'tipo_actividad';
    $operador_siembra = '!=';
    $filtro_siembra = '-1';
    $c3 = 'tipo_actividad';
    $op2 = '!=';
    $c4 = '-1';
    $c5 = 'estado';
    $op3 = '!=';
    $c6 = '-1';
    $signCont = '!=';
    $idContenedor = '-1';

    if ($request['f_siembra'] != '-1') {
      $id_siembra = "id_siembra";
      $operador_siembra = '=';
      $filtro_siembra = $request['f_siembra'];
    }
    if ($request['f_actividad'] != '-1') {
      $c3 = "tipo_actividad";
      $op2 = '=';
      $c4 = $request['f_actividad'];
    }
    if ($request['f_estado'] != '-1') {
      $c5 = "estado";
      $op3 = '=';
      $c6 = $request['f_estado'];
    }
    if ($request['f_contenedor'] != '-1') {
      $signCont = '=';
      $idContenedor = $request['f_contenedor'];
    }

    $recursosNecesarios = RecursoNecesario::select(
        'actividad',
        'id_siembra',
        'tipo_actividad',
        'nombre_siembra',
        'id_contenedor',
        'siembras.estado as estado',
        DB::raw('SUM(cantidad_recurso) as cantidad_recurso'),
        DB::raw('SUM(cant_manana) as c_manana'),
        DB::raw('SUM(cant_tarde) as c_tarde'),
        DB::raw('SUM(minutos_hombre) as minutos_hombre'),
        DB::raw('SUM(horas_hombre) as horas_hombre'),
      )
      ->join('recursos_siembras', 'recursos_necesarios.id', 'recursos_siembras.id_registro')
      ->join('siembras', 'recursos_siembras.id_siembra', 'siembras.id')
      ->join('actividades', 'recursos_necesarios.tipo_actividad', 'actividades.id')
      ->groupBy('siembras.nombre_siembra')
      ->groupBy('siembras.estado')
      ->groupBy('siembras.id_contenedor')
      ->groupBy('recursos_siembras.id_siembra')
      ->groupBy('recursos_necesarios.tipo_actividad')
      ->groupBy('actividades.actividad')
      ->where($id_siembra, $operador_siembra, $filtro_siembra)
      ->where($c3, $op2, $c4)
      ->where($c5, $op3, $c6)
      ->where('siembras.id_contenedor', $signCont, $idContenedor)
      ->orderBy('nombre_siembra', 'desc')
      ->paginate(30);

    foreach ($recursosNecesarios as $recursoNecesario) {

      $costo_recursos = RecursoNecesario::select()
        ->join('actividades', 'recursos_necesarios.tipo_actividad', 'actividades.id')
        ->join('recursos_siembras', 'recursos_necesarios.id', 'recursos_siembras.id_registro')
        ->leftJoin('alimentos', 'recursos_necesarios.id_alimento', 'alimentos.id')
        ->leftJoin('recursos', 'recursos_necesarios.id_recurso', 'recursos.id')
        ->join('siembras', 'recursos_siembras.id_siembra', 'siembras.id')
        ->get();

      foreach ($costo_recursos as $costo_recurso) {

        if ($recursoNecesario->id_siembra == $costo_recurso->id_siembra) {


          $recursoNecesario->minutosHombre += $costo_recurso->minutos_hombre;

          $recursoNecesario->costoRecurso +=  ($costo_recurso->cantidad_recurso * $costo_recurso->costo);
          $recursoNecesario->cantidadAlimento += ($costo_recurso->cant_tarde + $costo_recurso->cant_manana);
          $recursoNecesario->costoAlimento +=  (($costo_recurso->cant_tarde + $costo_recurso->cant_manana) * $costo_recurso->costo_kg);
          $recursoNecesario->costoMinutos = ($recursoNecesario->minutosHombre) * $minutos_hombre->costo;
          $recursoNecesario->costoTotalSiembra = $recursoNecesario->costoRecurso + $recursoNecesario->costoMinutos + $recursoNecesario->costoAlimento;

          if ($recursoNecesario->tipo_actividad == $costo_recurso->tipo_actividad) {
            $recursoNecesario->costo_recurso +=  ($costo_recurso->cantidad_recurso * $costo_recurso->costo);
            $recursoNecesario->cantidad_alimento += ($costo_recurso->cant_tarde + $costo_recurso->cant_manana);
            $recursoNecesario->costo_alimento +=  (($costo_recurso->cant_tarde + $costo_recurso->cant_manana) * $costo_recurso->costo_kg);
            $recursoNecesario->costo_minutos = (floatval($recursoNecesario->minutos_hombre)) * $minutos_hombre->costo;
            $recursoNecesario->costo_total_actividad = $recursoNecesario->costo_recurso + $recursoNecesario->costo_minutos + $recursoNecesario->costo_alimento;
          }
          $recursoNecesario->porcentaje_total_produccion = ($recursoNecesario->costo_total_actividad * 100) / $recursoNecesario->costoTotalSiembra;
        }
      }

      $recursoNecesario->horas_hombre = number_format($recursoNecesario->minutos_hombre / 60, 2, ',', '');
      $recursoNecesario->costo_recurso = number_format($recursoNecesario->costo_recurso, 2, ',', '');
      $recursoNecesario->costo_total_actividad = number_format($recursoNecesario->costo_total_actividad, 2, ',', '');
      $recursoNecesario->porcentaje_total_produccion = number_format($recursoNecesario->porcentaje_total_produccion, 2, ',', '');
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
