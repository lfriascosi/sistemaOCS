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

//Convocatoria
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