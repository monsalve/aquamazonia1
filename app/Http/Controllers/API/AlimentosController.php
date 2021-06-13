<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Alimento;
use App\HistorialAlimento;

class AlimentosController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    //
    $alimentos = Alimento::all();
    return $alimentos;
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

    $val = $request->validate([
      'alimento' => 'required',
      'costo_kg' => 'required'
    ]);
    $alimento = Alimento::create([
      'alimento' => $request['alimento'],
      'costo_kg' => $request['costo_kg']
    ]);

    HistorialAlimento::create([
      'id_alimento' => $alimento['id'],
      'costo' => $alimento['costo_kg'],
      'fecha_registro' => date('Y-m-d')
    ]);

    // $this->validate($request, [
    //     'alimento' => 'alimento',
    //     'costo_kg' => 'costo_kg',
    // ]);

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
    $alimento = Alimento::findOrFail($id);
    $alimento->update($request->all());

    HistorialAlimento::create([
      'id_alimento' => $request['id'],
      'costo' => $request['costo_kg'],
      'fecha_registro' => date('Y-m-d')
    ]);
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
    Alimento::destroy($id);
    HistorialAlimento::where('id_alimento', $id)->delete();

    return 'eliminado';
  }
}
