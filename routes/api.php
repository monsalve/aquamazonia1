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
    // 'informes-recursos' => 'API\InformeRecursosController',
]);

Route::namespace('API')->group(function () {
    Route::post('actualizarEstado/{id}', 'SiembraController@actualizarEstado');
    Route::post('searchResults', 'RecursoNecesarioController@searchResults');
    Route::post('filtroInformes', 'InformeController@filtroInformes');
    Route::post('informe-recursos', 'InformeController@informeRecursos');
    Route::get('traer-recursos', 'InformeController@traerInformes');
    Route::post('filtro-siembras', 'SiembraController@filtroSiembras');
    Route::get('traer-siembras', 'SiembraController@traerSiembras');
    Route::get('traer-existencias', 'InformeController@traerExistencias');
    Route::post('filtro-ciclos', 'InformeController@filtroExistencias');
    Route::post('filtro-parametros', 'ParametroCalidadController@filtroParametros');
});