<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\EspecieSiembra;
use App\Siembra;

class SiembraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        //$especieSiembras = EspecieSiembra::all();
        $siembra = Siembra::select('siembras.id as id', 'id_contenedor','contenedor','fecha_inicio', 'ini_descanso', 'fin_descanso','siembras.estado as estado')
                    ->join('contenedores','siembras.id_contenedor','contenedores.id')
                    ->where('siembras.estado','=',1)
                    ->get();
                    
        $peces = EspecieSiembra::select('especies_siembra.id as id','id_siembra','id_especie','cantidad','peso_inicial','cant_actual',  'peso_actual', 'especies.especie as especie')
                    ->join('especies','especies_siembra.id_especie','especies.id')                    
                    ->get();
        $pxs = array();
        
        foreach($peces as $p) {
            $pxs[$p->id_siembra][$p->id] = $p;
        }                
        
        return ["siembra"=> $siembra, "pecesSiembra" =>  $peces];
    }
    public function getPecesSiembras()
    {
        $peces = EspecieSiembra::select('especies_siembra.id as id','id_siembra','id_especie','cantidad','peso_inicial','cant_actual','peso_actual', 'especies.especie as especie')
                    ->join('especies','especies_siembra.id_especie','especies.id')
                    ->get();
        return $peces;
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
            // 'id_siembra' => 'required',            
            // 'id_especie' => 'required',
            // 'cantidad' => 'required',
            // 'peso_inicial' => 'required'
        ]);
       
        $siembra = new Siembra();
        $siembra->id_contenedor = $request->siembra['id_contenedor'];
        $siembra->fecha_inicio = $request->siembra['fecha_inicio'];   
        $siembra->ini_descanso = $request->siembra['ini_descanso'];   
        $siembra->fin_descanso = $request->siembra['fin_descanso'];  
        $siembra->estado = 1;
        $siembra->save();
        
        foreach($request->especies as $especie){
            $especieSiembra = new EspecieSiembra();
            $especieSiembra->id_siembra = $siembra->id;
            $especieSiembra->id_especie = $especie['id_especie'];
            $especieSiembra->cantidad =  $especie['cantidad'];
            $especieSiembra->peso_inicial = $especie['peso_inicial'];
            $especieSiembra->cant_actual =  $especie['cantidad'];;
            $especieSiembra->peso_actual = $especie['peso_inicial'];
            $especieSiembra->save();
        }
        
    }

    /**
     * Display the specified unidad.
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
        // $especieSiembras = EspecieSiembra::findOrFail($id);
        // $especieSiembras->update($request->all());
          
            
        return $request;
      
    }
    public function actualizarEstado(Request $request, $id){
        $val = $request->validate([
            'ini_descanso' => 'required',            
        ]);
        $siembra = Siembra::findOrFail($id);
        $siembra->ini_descanso = $request['ini_descanso'];   
        $siembra->fin_descanso = $request['fin_descanso'];  
        $siembra->save();
        
        print_r($id);
        return $request;
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
        EspecieSiembra::destroy($id);
        Siembra::destroy(id);
        return 'eliminado';
    }
}