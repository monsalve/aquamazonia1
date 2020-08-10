<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CalidadAgua;
use App\CalidadSiembra;
use App\Siembra;

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
      $calidad_agua = CalidadSiembra::select()
        ->join('calidad_agua', 'calidad_siembra.id_calidad_parametros', 'calidad_agua.id')
        ->join('siembras', 'calidad_siembra.id_siembra', 'siembras.id')
        ->get();
      return $calidad_agua;
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
        $calidad_agua = new CalidadAgua();
        $calidad_agua->fecha_parametro = $request['fecha_parametro'];
        $calidad_agua->{'12_am'} = $request['h_12am'];
        $calidad_agua->{'4_am'} = $request['h_4am'];
        $calidad_agua->{'7_am'} = $request['h_7am'];
        $calidad_agua->{'4_pm'} = $request['h_4pm'];
        $calidad_agua->{'8_pm'} = $request['h_8pm'];
        $calidad_agua->temperatura = $request['temperatura'];
        $calidad_agua->ph = $request['ph'];
        $calidad_agua->amonio = $request['amonio'];
        $calidad_agua->nitrito = $request['nitrito'];
        $calidad_agua->nitrato = $request['nitrato'];
        $calidad_agua->otros = $request['otros'];
        $calidad_agua->save();
        
        foreach($request->id_siembra as $siembra){
            $calidad_siembra = new CalidadSiembra();
            $calidad_siembra->id_calidad_parametros = $calidad_agua->id;
            $calidad_siembra->id_siembra = $siembra;
            $calidad_siembra->save();
        }
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
