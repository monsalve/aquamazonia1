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
      $minutos_hombre = Recursos::select()->where('recurso','Minutos hombre')->orWhere('recurso','Minuto hombre')->orWhere('recurso','Minutos')->first();
      
      /*$recursosNecesarios = RecursoNecesario::orderBy('fecha_ra', 'desc')
      ->join('recursos_siembras', 'recursos_necesarios.id', 'recursos_siembras.id_registro')
      ->leftJoin('recursos', 'recursos_necesarios.id_recurso','recursos.id')
      ->join('siembras', 'recursos_siembras.id_siembra', 'siembras.id')
      ->join('actividades','recursos_necesarios.tipo_actividad','actividades.id')
      ->where('tipo_actividad', '!=', '1')
      ->where('estado',1)
      ->get();*/
      
      $recursosNecesarios = RecursoNecesario::
        select(
        'actividad',
        'id_siembra',
        'tipo_actividad', 
        'nombre_siembra',
        DB::raw('SUM(cantidad_recurso) as cantidad_recurso'),
        DB::raw('SUM(cant_manana) as c_manana'),
        DB::raw('SUM(cant_tarde) as c_tarde'),
        DB::raw('SUM(minutos_hombre) as minutos_hombre'),
        DB::raw('SUM(horas_hombre) as horas_hombre'),
      )
      ->join('recursos_siembras', 'recursos_necesarios.id', 'recursos_siembras.id_registro')
      ->join('siembras', 'recursos_siembras.id_siembra', 'siembras.id')     
      ->join('actividades','recursos_necesarios.tipo_actividad','actividades.id')
      ->groupBy('siembras.nombre_siembra')
      ->groupBy('recursos_siembras.id_siembra')
      ->groupBy('recursos_necesarios.tipo_actividad')
      ->groupBy('actividades.actividad')
      ->get();
  
      for($i=0;$i<count($recursosNecesarios); $i++){
        $costo_recursos = RecursoNecesario::select()
        ->join('actividades','recursos_necesarios.tipo_actividad','actividades.id')
        ->join('recursos_siembras', 'recursos_necesarios.id', 'recursos_siembras.id_registro')
        ->leftJoin('alimentos', 'recursos_necesarios.id_alimento','alimentos.id')    
        ->leftJoin('recursos', 'recursos_necesarios.id_recurso','recursos.id')
        ->join('siembras', 'recursos_siembras.id_siembra', 'siembras.id')
        ->get();
        
        for($j=0;$j<count($costo_recursos); $j++){               
         
          if($recursosNecesarios[$i]->id_siembra == $costo_recursos[$j]->id_siembra && $recursosNecesarios[$i]->tipo_actividad == $costo_recursos[$j]->tipo_actividad){          
            $recursosNecesarios[$i]->costo_recurso +=  ($costo_recursos[$j]->cantidad_recurso * $costo_recursos[$j]->costo);
            $recursosNecesarios[$i]->cantidad_alimento += ($costo_recursos[$j]->cant_tarde + $costo_recursos[$j]->cant_manana);
            $recursosNecesarios[$i]->costo_alimento +=  (($costo_recursos[$j]->cant_tarde + $costo_recursos[$j]->cant_manana) * $costo_recursos[$j]->costo_kg);
          }
        }        
        $recursosNecesarios[$i]->horas_hombre = number_format($recursosNecesarios[$i]->horas_hombre,2,',','');
        $recursosNecesarios[$i]->costo_minutos = (floatval($recursosNecesarios[$i]->minutos_hombre)) * $minutos_hombre->costo;
      }  
      return ['recursosNecesarios' => $recursosNecesarios];
    }
    
    public function filtroRecursos(Request $request)
    {
        //
      $minutos_hombre = Recursos::select()->where('recurso','Minutos hombre')->orWhere('recurso','Minuto hombre')->orWhere('recurso','Minutos')->first();
      
      $c1 = 'tipo_actividad'; $op1 = '!='; $c2 = '-1';
      $c3 = 'tipo_actividad'; $op2 = '!='; $c4 = '-1';
      
      if($request['f_siembra']!='-1'){$c1="id_siembra"; $op1='='; $c2= $request['f_siembra'];}
      if($request['f_actividad']!='-1'){$c3="tipo_actividad"; $op2='='; $c4= $request['f_actividad'];}
      
      $recursosNecesarios = RecursoNecesario::
        select(
        'actividad',
        'id_siembra',
        'tipo_actividad', 
        'nombre_siembra',
        DB::raw('SUM(cantidad_recurso) as cantidad_recurso'),
        DB::raw('SUM(cant_manana) as c_manana'),
        DB::raw('SUM(cant_tarde) as c_tarde'),
        DB::raw('SUM(minutos_hombre) as minutos_hombre'),
        DB::raw('SUM(horas_hombre) as horas_hombre'),
      )
      ->join('recursos_siembras', 'recursos_necesarios.id', 'recursos_siembras.id_registro')
      ->join('siembras', 'recursos_siembras.id_siembra', 'siembras.id')     
      ->join('actividades','recursos_necesarios.tipo_actividad','actividades.id')
      ->groupBy('siembras.nombre_siembra')
      ->groupBy('recursos_siembras.id_siembra')
      ->groupBy('recursos_necesarios.tipo_actividad')
      ->groupBy('actividades.actividad')
      ->where($c1, $op1, $c2)
      ->where($c3, $op2, $c4)
      ->get();
  
      for($i=0;$i<count($recursosNecesarios); $i++){
        $costo_recursos = RecursoNecesario::select()
        ->join('actividades','recursos_necesarios.tipo_actividad','actividades.id')
        ->join('recursos_siembras', 'recursos_necesarios.id', 'recursos_siembras.id_registro')
        ->leftJoin('alimentos', 'recursos_necesarios.id_alimento','alimentos.id')    
        ->leftJoin('recursos', 'recursos_necesarios.id_recurso','recursos.id')
        ->join('siembras', 'recursos_siembras.id_siembra', 'siembras.id')
        ->get();
        
        for($j=0;$j<count($costo_recursos); $j++){               
         
          if($recursosNecesarios[$i]->id_siembra == $costo_recursos[$j]->id_siembra && $recursosNecesarios[$i]->tipo_actividad == $costo_recursos[$j]->tipo_actividad){          
            $recursosNecesarios[$i]->costo_recurso +=  ($costo_recursos[$j]->cantidad_recurso * $costo_recursos[$j]->costo);
            $recursosNecesarios[$i]->cantidad_alimento += ($costo_recursos[$j]->cant_tarde + $costo_recursos[$j]->cant_manana);
            $recursosNecesarios[$i]->costo_alimento +=  (($costo_recursos[$j]->cant_tarde + $costo_recursos[$j]->cant_manana) * $costo_recursos[$j]->costo_kg);
          }
        }        
        $recursosNecesarios[$i]->horas_hombre = number_format($recursosNecesarios[$i]->horas_hombre,2,',','');
        $recursosNecesarios[$i]->costo_minutos = (floatval($recursosNecesarios[$i]->minutos_hombre)) * $minutos_hombre->costo;
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
