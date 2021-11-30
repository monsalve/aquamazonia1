<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\EspecieSiembra;
use App\Registro;

class EspeciesSiembraController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    //
    if (isset($request['idSiembra'])) {
      $especies_siembra = EspecieSiembra::select('especies_siembra.id as id', 'id_siembra', 'id_especie', 'lote', 'cantidad', 'peso_inicial', 'cant_actual',  'peso_actual', 'especies.especie as especie',)
        ->join('especies', 'especies_siembra.id_especie', 'especies.id')
        ->where('id_siembra', $request['idSiembra'])
        ->orderBy('especie', 'asc')
        ->get()->toArray();
    }

    return $especies_siembra;
  }

  /**
   * Se devuelve la cantidad total de especies en una siembra
   * A partir del conteo de los registros
   */
  public function cantidadTotalEspeciesSiembra($id_siembra)
  {
    return Registro::select(
      'id_siembra',
      DB::raw('SUM(cantidad) as cantidad'),
      DB::raw('SUM(mortalidad) as mortalidad'),
      DB::raw('SUM(biomasa) as biomasa')
    )
      ->where('id_siembra', $id_siembra)
      ->groupBy('id_siembra')
      ->first();
  }
  public function cantidadTotalEspeciesSiembraSinMortalidad($id_siembra)
  {
    return Registro::select(
      'id_siembra',
      DB::raw('SUM(cantidad) as cantidad'),
      DB::raw('SUM(biomasa) as biomasa')
    )
      ->where('id_siembra', $id_siembra)
      ->where('tipo_registro', '<>', 0)
    
      ->groupBy('id_siembra')
      ->first();
  }
  /**
   * Se devuelve la cantidad total de cada especie en una siembra
   * A partir del conteo de los registros
   */
  public function cantidadEspecieSiembra($id_siembra, $id_especie)
  {
    return Registro::select(
      'id_siembra',
      'id_especie',
      DB::raw('SUM(cantidad) as cantidad'),
      DB::raw('SUM(mortalidad) as mortalidad'),
      DB::raw('SUM(biomasa) as biomasa')
    )
      ->where('id_siembra', $id_siembra)
      ->where('id_especie', $id_especie)
      ->groupBy('id_siembra')
      ->groupBy('id_especie')
      ->first();
  }

  public function cantidadEspecieSiembraSinMortalidad($id_siembra, $id_especie)
  {
    return Registro::select(
      'id_siembra',
      'id_especie',
      DB::raw('SUM(cantidad) as cantidad'),
      DB::raw('SUM(biomasa) as biomasa')
    )
      ->where('id_siembra', $id_siembra)
      ->where('id_especie', $id_especie)
      ->where('tipo_registro', '<>', 0)
      ->groupBy('id_siembra')
      ->groupBy('id_especie')
      ->first();
  }  /**
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
