<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\RecursoNecesario;
use App\RecursoSiembra;
use App\Alimento;
use App\Recursos;
use Illuminate\Support\Facades\DB;

class InformeAlimentosController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    //
    $minutos_hombre = Recursos::select()->where('recurso', 'Minutos hombre')->orWhere('recurso', 'Minuto hombre')->orWhere('recurso', 'Minutos')->first();

    $recursosNecesarios = RecursoNecesario::select(
      'alimentos.alimento',
      'alimentos.id as alimento_id',
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
      ->join('alimentos', 'recursos_necesarios.id_alimento', 'alimentos.id')
      ->where('recursos_necesarios.tipo_actividad', 1)
      ->groupBy('siembras.nombre_siembra')
      ->groupBy('siembras.estado')
      ->groupBy('recursos_siembras.id_siembra')
      ->groupBy('siembras.id_contenedor')
      ->groupBy('recursos_necesarios.tipo_actividad')
      ->groupBy('alimentos.id')
      ->groupBy('alimentos.alimento')
      ->orderBy('id_siembra', 'DESC');

      if(isset($request['id_siembra']) && $request['id_siembra'] != '') {
        if($request['id_siembra'] == '-1' ) {
          $recursosNecesarios = $recursosNecesarios->where('id_siembra', '!=', -1);
        } else {
          $recursosNecesarios = $recursosNecesarios->where('id_siembra', '=', $request['id_siembra']);
        }
      } 
      if(isset($request['estado_siembra'])  && $request['estado_siembra'] != '') {
        if($request['estado_siembra'] == '-1' ) {
          $recursosNecesarios = $recursosNecesarios->where('estado', '!=', -1);
        } else {
          $recursosNecesarios = $recursosNecesarios->where('estado', '=', $request['estado_siembra']);
        }
      }

      if(isset($request['id_alimento'])  && $request['id_alimento'] != '') {
        if($request['id_alimento'] == '-1' ) {
          $recursosNecesarios = $recursosNecesarios->where('alimento_id', '!=', -1);
        } else {
          $recursosNecesarios = $recursosNecesarios->where('alimentos.id', '=', $request['id_alimento']);
        }
      }
     
      $recursosNecesarios = $recursosNecesarios->paginate(30);
    
    foreach ($recursosNecesarios as $recursoNecesario) {

      $costo_recursos = $this->datosAlimento($recursoNecesario->alimento_id);
      $recursoNecesario->cantidadTotalAlimento = $recursoNecesario->c_manana + $recursoNecesario->c_tarde;
      $recursoNecesario->costoAlimento = $costo_recursos->costo_kg * $recursoNecesario->cantidadTotalAlimento;
      $recursoNecesario->costoUnitarioAlimento = $costo_recursos->costo_kg;
    }
    
    return [
      'recursosNecesarios' => $recursosNecesarios,
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

  public function datosAlimento($id_alimento)
  {
    return Alimento::select('costo_kg')->where('id', $id_alimento)->first();
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
