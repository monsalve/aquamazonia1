<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CalidadAgua;
use App\CalidadSiembra;
use App\Siembra;
use App\Contenedor;


class ParametroCalidadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      //
        $calidad_agua = CalidadAgua::select(
            'calidad_agua.id as id',
            'contenedores.id as id_contenedor',
            '4_am',
            '4_pm',
            '7_am',
            '8_pm',
            '12_am',
            'amonio',
            'contenedor',
            'fecha_parametro',
            'nitrato',
            'nitrito',
            'otros',
            'ph',
            'temperatura')
            ->join('contenedores', 'calidad_agua.id_contenedor', 'contenedores.id')
            ->orderBy('fecha_parametro', 'desc')
            ->get();
        
        $promedios = array();
        $prom_12am = 0;
        $prom_4am = 0;
        $prom_7am = 0;
        $prom_4pm = 0;
        $prom_8pm = 0;
        $prom_temperatura = 0;
        $prom_ph = 0;
        $prom_amonio = 0;
        $prom_nitrito = 0;
        $prom_nitrato = 0;
        $prom_otros = 0;
 
        if(count($calidad_agua)>0){
            for($i=0;$i<count($calidad_agua);$i++){
                $prom_12am += $calidad_agua[$i]['12_am'];
                $promedios['promedio_12_am'] = (round($prom_12am/(count($calidad_agua)),2));
                
                $prom_4am += $calidad_agua[$i]['4_am'];
                $promedios['promedio_4_am'] = (round($prom_4am/(count($calidad_agua)),2));
                
                $prom_7am += $calidad_agua[$i]['7_am'];
                $promedios['promedio_7_am'] = (round($prom_7am/(count($calidad_agua)),2));
                
                $prom_4pm += $calidad_agua[$i]['4_pm'];
                $promedios['promedio_4_pm'] = (round($prom_4pm/(count($calidad_agua)),2));
               
                $prom_8pm += $calidad_agua[$i]['8_pm'];
                $promedios['promedio_8_pm'] = (round($prom_8pm/(count($calidad_agua)),2));
                
                $prom_temperatura += $calidad_agua[$i]['temperatura'];
                $promedios['promedio_temperatura'] = (round($prom_temperatura/(count($calidad_agua)),2));
                
                $prom_ph += $calidad_agua[$i]['ph'];
                $promedios['promedio_ph'] = (round($prom_ph/(count($calidad_agua)),2));
                
                $prom_amonio += $calidad_agua[$i]['amonio'];
                $promedios['promedio_amonio'] = (round($prom_amonio/(count($calidad_agua)),2));
                
                $prom_nitrito += $calidad_agua[$i]['nitrito'];
                $promedios['promedio_nitrito'] = (round($prom_nitrito/(count($calidad_agua)),2));
                
                $prom_nitrato += $calidad_agua[$i]['nitrato'];
                $promedios['promedio_nitrato'] = (round($prom_nitrato/(count($calidad_agua)),2));
                
                $prom_otros += $calidad_agua[$i]['otros'];
                $promedios['promedio_otros'] = (round($prom_otros/(count($calidad_agua)),2));
            }
            // print_r('Promedio:'.$div_promedio);
        }
        
         
      return ['calidad_agua'=> $calidad_agua,'promedios' => $promedios];
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
        foreach($request->id_contenedor as $contenedor){
            $calidad_agua = new CalidadAgua();
            $calidad_agua->fecha_parametro = $request['fecha_parametro'];
            // $calidad_agua->id_contenedor = $request['id_contenedor'];
            $calidad_agua->id_contenedor = $contenedor;
            $calidad_agua->{'12_am'} = $request['12_am'];
            $calidad_agua->{'4_am'} = $request['4_am'];
            $calidad_agua->{'7_am'} = $request['7_am'];
            $calidad_agua->{'4_pm'} = $request['4_pm'];
            $calidad_agua->{'8_pm'} = $request['8_pm'];
            $calidad_agua->temperatura = $request['temperatura'];
            $calidad_agua->ph = $request['ph'];
            $calidad_agua->amonio = $request['amonio'];
            $calidad_agua->nitrito = $request['nitrito'];
            $calidad_agua->nitrato = $request['nitrato'];
            $calidad_agua->otros = $request['otros'];
            $calidad_agua->save();
        }
        
        // foreach($request->id_contenedor as $contenedor){
        //     $calidad_siembra = new CalidadSiembra();
        //     $calidad_siembra->id_calidad_parametros = $calidad_agua->id;
        //     $calidad_siembra->id_contenedor = $contenedor;
        //     $calidad_siembra->save();
        // }
        
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
        $calidad_agua = CalidadAgua::findOrFail($id);
        $calidad_agua->update($request->all());
        return 'ok';
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
        CalidadAgua::destroy($id);
        // $cs = CalidadSiembra::where('id_calidad_parametros', $id)->delete();
       return 'eliminado';
    }
    
    public function filtroParametros(Request $request){
        $c1 = "calidad_agua.id"; $op1 = '!='; $c2 = '-1';
        $c3 = "calidad_agua.id"; $op2 = '!='; $c4 = '-1';
        $c5 = "calidad_agua.id"; $op3 = '!='; $c6 = '-1';
        // $c5 = "siembras.id"; $op3 = '!='; $c6 = '-1';
        
        // if($request['f_siembra']!='-1'){$c1="siembras.id"; $op1='='; $c2= $request['f_siembra'];}
        if($request['f_inicio_d']!='-1'){$c1="fecha_parametro"; $op1='>='; $c2= $request['f_inicio_d'];}
        if($request['f_inicio_h']!='-1'){$c3="fecha_parametro"; $op2='<='; $c4= $request['f_inicio_h'];}
        if($request['id_contenedor']!='-1'){$c3="id_contenedor"; $op2='='; $c4= $request['id_contenedor'];}
    
        $calidad_agua = CalidadAgua::select()
            ->join('contenedores', 'calidad_agua.id_contenedor', 'contenedores.id')
            ->where($c1, $op1, $c2)
            ->where($c3, $op2, $c4)
            ->where($c5, $op3, $c6)
            ->orderBy('fecha_parametro', 'desc')
            ->get();
        
        $promedios = array();
        $prom_12am = 0;
        $prom_4am = 0;
        $prom_7am = 0;
        $prom_4pm = 0;
        $prom_8pm = 0;
        $prom_temperatura = 0;
        $prom_ph = 0;
        $prom_amonio = 0;
        $prom_nitrito = 0;
        $prom_nitrato = 0;
        $prom_otros = 0;
 
        if(count($calidad_agua)>0){
            for($i=0;$i<count($calidad_agua);$i++){
                $prom_12am += $calidad_agua[$i]['12_am'];
                $promedios['promedio_12_am'] = (round($prom_12am/(count($calidad_agua)),2));
                
                $prom_4am += $calidad_agua[$i]['4_am'];
                $promedios['promedio_4_am'] = (round($prom_4am/(count($calidad_agua)),2));
                
                $prom_7am += $calidad_agua[$i]['7_am'];
                $promedios['promedio_7_am'] = (round($prom_7am/(count($calidad_agua)),2));
                
                $prom_4pm += $calidad_agua[$i]['4_pm'];
                $promedios['promedio_4_pm'] = (round($prom_4pm/(count($calidad_agua)),2));
               
                $prom_8pm += $calidad_agua[$i]['8_pm'];
                $promedios['promedio_8_pm'] = (round($prom_8pm/(count($calidad_agua)),2));
                
                $prom_temperatura += $calidad_agua[$i]['temperatura'];
                $promedios['promedio_temperatura'] = (round($prom_temperatura/(count($calidad_agua)),2));
                
                $prom_ph += $calidad_agua[$i]['ph'];
                $promedios['promedio_ph'] = (round($prom_ph/(count($calidad_agua)),2));
                
                $prom_amonio += $calidad_agua[$i]['amonio'];
                $promedios['promedio_amonio'] = (round($prom_amonio/(count($calidad_agua)),2));
                
                $prom_nitrito += $calidad_agua[$i]['nitrito'];
                $promedios['promedio_nitrito'] = (round($prom_nitrito/(count($calidad_agua)),2));
                
                $prom_nitrato += $calidad_agua[$i]['nitrato'];
                $promedios['promedio_nitrato'] = (round($prom_nitrato/(count($calidad_agua)),2));
                
                $prom_otros += $calidad_agua[$i]['otros'];
                $promedios['promedio_otros'] = (round($prom_otros/(count($calidad_agua)),2));
            }
            // print_r('Promedio:'.$div_promedio);
        }
        
         
      return ['calidad_agua'=> $calidad_agua,'promedios' => $promedios];
        // return $calidad_agua;
    }   
    
    public function listadoParametrosContenedores() {
        $contenedores = Contenedor::select(
        'capacidad',
        'contenedores.id as id',
        'contenedores.estado as estado',
        'contenedor',
        'nombre_siembra',
        'siembras.id as id_siembra'
        )
        ->leftJoin('siembras', 'contenedores.id', 'siembras.id_contenedor')
        ->get()
        ;
        
        return $contenedores;
    }
    
    public function mostrarParametrosxContenedores($id){
        //
          $calidad_agua = CalidadAgua::select(
            'calidad_agua.id as id',
            'contenedores.id as id_contenedor',
            '4_am',
            '4_pm',
            '7_am',
            '8_pm',
            '12_am',
            'amonio',
            'contenedor',
            'fecha_parametro',
            'nitrato',
            'nitrito',
            'otros',
            'ph',
            'temperatura'
          )
          ->where('contenedores.id', '=',$id)          
          ->rightJoin('contenedores', 'calidad_agua.id_contenedor', 'contenedores.id')
          ->orderBy('fecha_parametro', 'desc')
          ->get();
          
          $promedios = array();
          $prom_12am = 0;
          $prom_4am = 0;
          $prom_7am = 0;
          $prom_4pm = 0;
          $prom_8pm = 0;
          $prom_temperatura = 0;
          $prom_ph = 0;
          $prom_amonio = 0;
          $prom_nitrito = 0;
          $prom_nitrato = 0;
          $prom_otros = 0;
   
          if(count($calidad_agua)>0){
              for($i=0;$i<count($calidad_agua);$i++){
                  $prom_12am += $calidad_agua[$i]['12_am'];
                  $promedios['promedio_12_am'] = (round($prom_12am/(count($calidad_agua)),2));
                  
                  $prom_4am += $calidad_agua[$i]['4_am'];
                  $promedios['promedio_4_am'] = (round($prom_4am/(count($calidad_agua)),2));
                  
                  $prom_7am += $calidad_agua[$i]['7_am'];
                  $promedios['promedio_7_am'] = (round($prom_7am/(count($calidad_agua)),2));
                  
                  $prom_4pm += $calidad_agua[$i]['4_pm'];
                  $promedios['promedio_4_pm'] = (round($prom_4pm/(count($calidad_agua)),2));
                 
                  $prom_8pm += $calidad_agua[$i]['8_pm'];
                  $promedios['promedio_8_pm'] = (round($prom_8pm/(count($calidad_agua)),2));
                  
                  $prom_temperatura += $calidad_agua[$i]['temperatura'];
                  $promedios['promedio_temperatura'] = (round($prom_temperatura/(count($calidad_agua)),2));
                  
                  $prom_ph += $calidad_agua[$i]['ph'];
                  $promedios['promedio_ph'] = (round($prom_ph/(count($calidad_agua)),2));
                  
                  $prom_amonio += $calidad_agua[$i]['amonio'];
                  $promedios['promedio_amonio'] = (round($prom_amonio/(count($calidad_agua)),2));
                  
                  $prom_nitrito += $calidad_agua[$i]['nitrito'];
                  $promedios['promedio_nitrito'] = (round($prom_nitrito/(count($calidad_agua)),2));
                  
                  $prom_nitrato += $calidad_agua[$i]['nitrato'];
                  $promedios['promedio_nitrato'] = (round($prom_nitrato/(count($calidad_agua)),2));
                  
                  $prom_otros += $calidad_agua[$i]['otros'];
                  $promedios['promedio_otros'] = (round($prom_otros/(count($calidad_agua)),2));
              }
              // print_r('Promedio:'.$div_promedio);
              return ['calidad_agua'=> $calidad_agua,'promedios' => $promedios];
          }
          return ['calidad_agua'=> $calidad_agua,'promedios' => $promedios];
           
        
    }
    
}
