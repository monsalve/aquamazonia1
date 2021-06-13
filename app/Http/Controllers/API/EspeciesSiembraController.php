<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\EspecieSiembra;

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
