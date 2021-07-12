<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\EspecieSiembra;
use App\Especie;
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
    public function index(Request $request)
    {
        //listado general de siembras
				$listado_siembras_activas = Siembra::select('siembras.id as id', 'nombre_siembra','fecha_inicio', 'ini_descanso', 'fin_descanso','siembras.estado as estado', 'fecha_alimento')
        ->orderBy('siembras.id', 'desc')
				->where('estado', 1)
        ->get();

				$listado_siembras_inactivas = Siembra::select('siembras.id as id', 'nombre_siembra','fecha_inicio', 'ini_descanso', 'fin_descanso','siembras.estado as estado', 'fecha_alimento')
				->where('estado', 0)
        ->orderBy('siembras.id', 'desc')
        ->get();

				//Otros datos
        $siembras = Siembra::select('siembras.id as id', 'nombre_siembra', 'id_contenedor','contenedor','fecha_inicio', 'ini_descanso', 'fin_descanso','siembras.estado as estado', 'fecha_alimento')
					->join('contenedores','siembras.id_contenedor','contenedores.id')
					->orderBy('siembras.id', 'desc');

				if(isset($request['estado_siembra'])) {
					if($request['estado_siembra'] == -1) {
						$siembras = $siembras->where('siembras.estado', '!=', -1);
					}
					if($request['estado_siembra'] == 1) {
						$siembras = $siembras->where('siembras.estado', '=', 1);
						if(isset($request['id_siembra']) && $request['id_siembra']!= '') {
							$siembras = $siembras->where('siembras.id', '=', $request['id_siembra']);
						}
					}
					if($request->estado_siembra == 0) {
						$siembras = $siembras->where('siembras.estado', '=', 0);
						if(isset($request['id_siembra']) && $request['id_siembra']!= '') {
							$siembras = $siembras->where('siembras.id', '=', $request['id_siembra']);
						}
					}
				}

				$siembras = $siembras->get();
				// echo $request->estado_siembra;

				$detalles_siembra = array();
				foreach($siembras as $siembra) {

					$peces = EspecieSiembra::select('especies_siembra.id as id','id_siembra','id_especie','lote','cantidad','peso_inicial','cant_actual',  'peso_actual', 'especies.especie as especie',)
					->leftJoin('especies','especies_siembra.id_especie','especies.id')
					->where('especies_siembra.id_siembra', $siembra->id)
					->orderBy('especie', 'asc')
					->get();

					$detalles_siembra[$siembra['id']] = array(
						'id' => $siembra['id'],
						'nombre_siembra' => $siembra['nombre_siembra'],
						'contenedor' => $siembra['contenedor'],
						'fecha_alimento' => $siembra['fecha_alimento'],
						'ini_descanso' => $siembra['ini_descanso'],
						'estado' => $siembra['estado'],
						'fecha_inicio' => $siembra['fecha_inicio'],
						'fin_descanso' => $siembra['fin_descanso'],
						'id_contenedor' => $siembra['id_contenedor'],
					);

					foreach($peces as $pez) {
						$detalles_siembra[$siembra['id']]['peces'][$pez['id']] = [
							'especie' => $pez['especie'],
							'lote' => $pez['lote'],
							'cant_actual' => $pez['cant_actual'],
							'peso_actual' => $pez['peso_actual'],

						];

					}
				}

        $fecha_actual = date('Y-m-d');

        $peces = EspecieSiembra::select('especies_siembra.id as id','id_siembra','id_especie','lote','cantidad','peso_inicial','cant_actual',  'peso_actual', 'especies.especie as especie',)
					->join('especies','especies_siembra.id_especie','especies.id')
					->orderBy('especie', 'asc')
					->get()->toArray();

        $lotes = EspecieSiembra::select('lote')->distinct()->orderBy('lote', 'asc')->get();
        $pxs = array();
        $campos=array();
        foreach($peces as $p) {
            $pxs[$p['id_siembra']][$p['id']] = $p;

            $campos[$p['id_siembra']][$p['id']] = array(
                "id_especie"=>$p['id_especie'],
                "id_siembra"=>$p['id_siembra'] ,
                "peso_ganado"=>'',
                "mortalidad"=>'',
                "biomasa"=>'',
                "cantidad"=>'',
                'cant_actual'=>$p['cant_actual'],
                'peso_actual'=>$p['peso_actual']);
        }
				// exit;

        return ["siembra"=> $detalles_siembra,"listado_siembras"=> $listado_siembras_activas, "listado_siembras_inactivas" => $listado_siembras_inactivas, "pecesSiembra" =>  $peces, 'campos'=>$campos, 'lotes' => $lotes, 'fecha_actual'=> $fecha_actual];
    }

		public function listadoLotes(){
			return EspecieSiembra::select('lote')->orderBy('lote', 'asc')->distinct()->get();
		}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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
				$especieSiembra->cant_actual =  $especie['cantidad'];
				$especieSiembra->peso_actual = $especie['peso_inicial'];
				$especieSiembra->save();


				$registro = Registro::create([
					'id_especie' =>$especie['id_especie'],
					'id_siembra' => $siembra->id,
					'fecha_registro' => $request->siembra['fecha_inicio'],
					'tipo_registro' => 3,
					'peso_ganado' => $especie['peso_inicial'],
					// 'cantidad' =>$especie['cantidad']
				]);
			}
    }
    // Añadir especies a la siembra
    public function anadirEspeciesxSiembra(Request $request)
    {
			foreach($request['especies'] as $especie){
				if(!isset($especie['es_edita'])){
					$especieSiembra = new EspecieSiembra();
					$especieSiembra->id_siembra = $request->siembra['id_siembra'];
					$especieSiembra->id_especie = $especie['id_especie'];
					$especieSiembra->cantidad =  $especie['cantidad'];
					$especieSiembra->lote =  $especie['lote'];
					$especieSiembra->peso_inicial = $especie['peso_inicial'];
					$especieSiembra->cant_actual =  $especie['cantidad'];;
					$especieSiembra->peso_actual = $especie['peso_inicial'];
					$especieSiembra->save();

					$registro = Registro::create([
						'id_especie' =>$especie['id_especie'],
						'id_siembra' => $request->siembra['id_siembra'],
						'fecha_registro' => $request->siembra['fecha_inicio'],
						'tipo_registro' => 3,
						'peso_ganado' => $especie['peso_inicial'],
						// 'cantidad' =>$especie['cantidad']
					]);
				}
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
			$especieSiembras = EspecieSiembra::findOrFail($id);
			$especieSiembras->update($request->all());
			// $especieSiembra->cantidad = $request['']

			
			$registro = Registro::where('id_siembra', $especieSiembras->id_siembra)->where('id_especie', $especieSiembras->id_especie)->where('tipo_registro',3)->first();
			$registro->peso_ganado = $request->peso_inicial;
			// $registro->cantidad = $request->cantidad;
			$registro->save();

			return $especieSiembras;
    }

    public function actualizarEstado(Request $request, $id){

			$siembra = Siembra::findOrFail($id);
			$siembra->ini_descanso = $request['ini_descanso'];
			if(isset( $request['fin_descanso'])){
					$siembra->fin_descanso = $request['fin_descanso'];
			}
			$siembra->estado = 0;
			$siembra->save();

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
    public function filtroSiembras(Request $request){
			$c1 = "siembras.id"; $op1 = '!='; $c2 = '-1';
			$c3 = "siembras.id"; $op2 = '!='; $c4 = '-1';
			$c5 = "siembras.id"; $op3 = '!='; $c6 = '-1';
			$c7 = "siembras.id"; $op4 = '!='; $c8 = '-1';
			$c9 = "siembras.id"; $op5 = '!='; $c10 = '-1';

			if($request['f_siembra']!='-1'){$c1="siembras.id"; $op1='='; $c2= $request['f_siembra'];}
			if($request['f_especie']!='-1'){$c3="especies.id"; $op2='='; $c4= $request['f_especie'];}
			if($request['f_lote']!='-1'){$c5="lote"; $op3='='; $c6= $request['f_lote'];}
			if($request['f_inicio_d']!='-1'){$c7="fecha_inicio"; $op4='>='; $c8= $request['f_inicio_d'];}
			if($request['f_inicio_h']!='-1'){$c9="fecha_inicio"; $op5='<='; $c10= $request['f_inicio_h'];}

			$filtrarSiembras = Siembra::select('siembras.id as id', 'nombre_siembra', 'id_contenedor','contenedor','fecha_inicio', 'ini_descanso', 'fin_descanso','siembras.estado as estado', 'lote', 'especies.id', 'especie', 'cantidad', 'peso_inicial', 'cant_actual', 'peso_actual', 'fecha_alimento')
					->join('contenedores','siembras.id_contenedor','contenedores.id')
					->join('especies_siembra', 'siembras.id', 'especies_siembra.id_siembra')
					->join('especies', 'especies_siembra.id_especie', 'especies.id')
					->where($c1, $op1, $c2)
					->where($c3, $op2, $c4)
					->where($c5, $op3, $c6)
					->where($c7, $op4, $c8)
					->where($c9, $op5, $c10)
					->orderBy('siembras.id', 'desc')
					->get();
			return ['filtrarSiembras' => $filtrarSiembras];
    }
    public function traerSiembras(){
			$filtrarSiembras = Siembra::select('siembras.id as id', 'nombre_siembra', 'id_contenedor','contenedor','fecha_inicio', 'ini_descanso', 'fin_descanso','siembras.estado as estado', 'lote', 'especies.id', 'especie', 'cantidad', 'peso_inicial', 'cant_actual', 'peso_actual', 'fecha_alimento')
				->join('contenedores','siembras.id_contenedor','contenedores.id')
				->join('especies_siembra', 'siembras.id', 'especies_siembra.id_siembra')
				->join('especies', 'especies_siembra.id_especie', 'especies.id')
				->orderBy('siembras.id', 'desc')
				->get();

			return ['filtrarSiembras' => $filtrarSiembras];
    }

    public function getEspeciesSiembra(Request $request, $id) {
        $espxsiembra = EspecieSiembra::select('especies_siembra.id as id','cantidad','id_especie','lote','peso_inicial', 'cant_actual')
					->join('especies','especies_siembra.id_especie','especies.id')
					->where('id_siembra',$id)
					->orderBy('especies.especie')
					->get();

        $aux_id_es = array();
        $aux_es = array();
        foreach($espxsiembra as $axs) {
					$aux_id_es[] = $axs->id_especie;
					$aux_es[] = array('id'=>$axs->id,'cantidad'=>$axs->cantidad, 'cant_actual'=>$axs->cant_actual,'id_especie'=>$axs->id_especie,'lote'=> $axs->lote, 'peso_inicial' => $axs->peso_inicial,'es_edita' => '1');
        }
        if(count($aux_id_es)>0) {
					$especies = Especie::whereNotIn('id',$aux_id_es)->orderBy('especie')->get();
        }
        else {
					$especies = Especie::orderBy('especie')->get();
        }
        return ['espxsiembra' => $aux_es , 'especies' => $especies];
    }

}