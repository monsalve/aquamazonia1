<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\EspecieSiembra;
use App\Siembra;
use App\Contenedor;
use App\Registro;

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
        $siembra = Siembra::select('siembras.id as id', 'nombre_siembra', 'id_contenedor','contenedor','fecha_inicio', 'ini_descanso', 'fin_descanso','siembras.estado as estado')
                    ->join('contenedores','siembras.id_contenedor','contenedores.id')
                    ->where('siembras.estado','=',1)
                    ->orderBy('siembras.id', 'desc')
                    ->get();
                    
        $peces = EspecieSiembra::select('especies_siembra.id as id','id_siembra','id_especie','lote','cantidad','peso_inicial','cant_actual',  'peso_actual', 'especies.especie as especie',)
                    ->join('especies','especies_siembra.id_especie','especies.id')         
                    ->orderBy('especie', 'asc')
                    ->get()->toArray();
        $pxs = array();
        
        $campos=array();
        foreach($peces as $p) {
            $pxs[$p['id_siembra']][$p['id']] = $p;
            $campos[$p['id_siembra']][$p['id']] = array("id_especie"=>$p['id_especie'],"id_siembra"=>$p['id_siembra'] ,"peso_ganado"=>'',"mortalidad"=>'',"biomasa"=>'',"cantidad"=>'','cant_actual'=>$p['cant_actual'],'peso_actual'=>$p['peso_actual']);
        }                
        
        return ["siembra"=> $siembra, "pecesSiembra" =>  $peces, 'campos'=>$campos];
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
        $id_contenedor = $request->siembra['id_contenedor'];
     
        $siembra = new Siembra();
        $siembra->id_contenedor = $request->siembra['id_contenedor'];
        $siembra->nombre_siembra = $request->siembra['nombre_siembra'];
        $siembra->fecha_inicio = $request->siembra['fecha_inicio'];
        $siembra->estado = 1;    
        $siembra->save();        
        
        $contenedor = Contenedor::findOrFail($id_contenedor);
        $contenedor->update([$contenedor->estado = 2]);
        
        foreach($request->especies as $especie){
            $especieSiembra = new EspecieSiembra();
            $especieSiembra->id_siembra = $siembra->id;
            $especieSiembra->id_especie = $especie['id_especie'];
            $especieSiembra->cantidad =  $especie['cantidad'];
            $especieSiembra->lote =  $especie['lote'];
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
        if(isset( $request['fin_descanso'])){
            $siembra->fin_descanso = $request['fin_descanso'];
        }
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
        
        // EspecieSiembra::destroy($id);
        Siembra::destroy($id);
        $espxSiembra = EspecieSiembra::where('id_siembra', $id)->delete();
        $regxSiembra = Registro::where('id_siembra', $id)->delete();
        
        return 'eliminado';
    }
}