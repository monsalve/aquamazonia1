<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {

    return $request->user();
});

Route::apiResources([
    'contenedores' => 'API\ContenedorController',
    'alimentos' => 'API\AlimentosController',
    'recursos' => 'API\RecursosController',
    'especies' => 'API\EspeciesController',
    'usuarios' => 'API\UsuarioController',
    'siembras' => 'API\SiembraController',
    'registros' => 'API\RegistroController',
    'recursos-necesarios' => 'API\RecursoNecesarioController',
    'recursos-siembras' => 'API\RecursoSiembraController',
    'informes' => 'API\InformeController',
    'parametros-calidad' => 'API\ParametroCalidadController',
    'informes-siembras' => 'API\InformeSiembraController',
    // 'informes-recursos' => 'API\InformeRecursosController',
]);

Route::namespace('API')->group(function () {
    Route::post('registros-siembra/{id}', 'RegistroController@registrosxSiembra');
    Route::post('actualizarEstado/{id}', 'SiembraController@actualizarEstado');
    Route::post('filtro-siembras', 'InformeSiembraController@filtroSiembras');
    Route::post('searchResults', 'RecursoNecesarioController@searchResults');
    Route::post('filtroInformes', 'InformeController@filtroInformes');
    Route::post('filtro-ciclos', 'InformeController@filtroExistencias');
    Route::post('informe-recursos', 'InformeController@informeRecursos');
    Route::post('filtro-parametros', 'ParametroCalidadController@filtroParametros');
    Route::post('parametro-x-contenedor/{id}', 'ParametroCalidadController@mostrarParametrosxContenedores');
    Route::post('siembras-alimentacion/{id}', 'RecursoNecesarioController@siembraxAlimentacion');   
    Route::post('anadir-especie-siembra', 'SiembraController@anadirEspeciesxSiembra');   
    
    Route::get('especies-siembra-edita/{id}', 'SiembraController@getEspeciesSiembra');    
    Route::get('lista-alimentacion', 'RecursoNecesarioController@alimentacion');
    Route::get('traer-siembras', 'SiembraController@traerSiembras');    
    Route::get('traer-recursos', 'InformeController@traerInformes');
    Route::get('traer-existencias', 'InformeController@traerExistencias');
    Route::get('parametros-contenedores', 'ParametroCalidadController@listadoParametrosContenedores');
});