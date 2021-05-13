<?php

use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('contenido/contenido');
});

//CONVOCATORIA
Route::get('/convocatoria','App\Http\Controllers\ConvocatoriaController@index');
Route::post('/convocatoria/registrar','App\Http\Controllers\ConvocatoriaController@store');
//Listado Personas
Route::get('/convocatoria/personas','App\Http\Controllers\ConvocatoriaController@indexP');
//Buscar Persona
Route::get('/convocatoria/buscarPersona','App\Http\Controllers\ConvocatoriaController@buscarPersona');
//Cabecera
Route::get('/convocatoria/obtenerCabecera','App\Http\Controllers\ConvocatoriaController@obtenerCabecera');
//Listado detalles
Route::get('/convocatoria/obtenerDetalles','App\Http\Controllers\ConvocatoriaController@obtenerDetalles');
//Listado Orden dia
Route::get('/convocatoria/obtenerOrdenDias','App\Http\Controllers\ConvocatoriaController@obtenerOrdenDias');
//Listado Detalle Orden dia
Route::get('/convocatoria/obtenerDetalleOrdenDias','App\Http\Controllers\ConvocatoriaController@obtenerDetalleOrdenDias');
//Envio de Correos
Route::post('/convocatoria/storeEmail','App\Http\Controllers\ConvocatoriaController@storeEmail');
Route::post('/convocatoria/storeEmailInv','App\Http\Controllers\ConvocatoriaController@storeEmailInv');
Route::post('/convocatoria/storeEmailConf','App\Http\Controllers\ConvocatoriaController@storeEmailConf');
//Activar o Inactivcar Orden dia
Route::put('/convocatoria/activar','App\Http\Controllers\ConvocatoriaController@activar');
Route::put('/convocatoria/inactivar','App\Http\Controllers\ConvocatoriaController@inactivar');

//ARCHIVO
Route::post('/convocatoria/storeArchivo','App\Http\Controllers\ConvocatoriaController@storeArchivo');

//ACTA
Route::get('/acta','App\Http\Controllers\ActaController@index');
//Cabecera
Route::get('/acta/obtenerCabecera','App\Http\Controllers\ActaController@obtenerCabecera');
//Listado Orden dia
Route::get('/acta/obtenerOrdenDias','App\Http\Controllers\ActaController@obtenerOrdenDias');
//Busqueda Id
Route::get('/acta/obtenerIdConvocatoria','App\Http\Controllers\ActaController@obtenerIdConvocatoria');
//Buscar Convocatoria
Route::get('/acta/buscarConvocatoria','App\Http\Controllers\ActaController@buscarConvocatoria');
//Buscar Convocatorias
Route::get('/acta/buscarConvocatorias','App\Http\Controllers\ActaController@buscarConvocatorias');
//Buscar Convocatorias con Actas
Route::get('/acta/buscarConvocatoriaActa','App\Http\Controllers\ActaController@buscarConvocatoriaActa');

//Correo
Route::post('/email/send','MailController@send');

Route::get('/convocatoria/probar','App\Http\Controllers\ConvocatoriaController@probar');