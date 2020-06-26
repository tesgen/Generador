<?php

//Vistas
Route::get('/generador/', 'TesGen\Generador\Controllers\BaseController@index');
//Route::get('/generador/crud', 'TesGen\Generador\Controllers\BaseController@crud');
//Route::get('/generador/md', 'TesGen\Generador\Controllers\BaseController@md');
Route::get('/generador/crud2', 'TesGen\Generador\Controllers\BaseController@crud2');
Route::get('/generador/md2', 'TesGen\Generador\Controllers\BaseController@md2');
Route::get('/generador/usuarios', 'TesGen\Generador\Controllers\BaseController@usuarios');
Route::get('/generador/permisos', 'TesGen\Generador\Controllers\BaseController@permisos');
Route::get('/generador/form', 'TesGen\Generador\Controllers\BaseController@form');

//Obtener datos
Route::get('/generador/getTablas', 'TesGen\Generador\Controllers\BaseController@obtenerJsonMapTablas');
Route::get('/generador/agregar', 'TesGen\Generador\Controllers\BaseController@agregarElemento');
Route::get('/generador/quitar', 'TesGen\Generador\Controllers\BaseController@quitarElemento');

//Peticiones post
Route::post('/generador/guardar', 'TesGen\Generador\Controllers\BaseController@guardar');
Route::post('/generador/guardar_y_generar', 'TesGen\Generador\Controllers\BaseController@guardaryGenerar');
Route::post('/generador/generar_autenticacion', 'TesGen\Generador\Controllers\BaseController@generarAutenticacion');
Route::post('/generador/guardar_autenticacion', 'TesGen\Generador\Controllers\BaseController@guardarAutenticacion');
Route::post('/generador/guardar_y_generar_autenticacion', 'TesGen\Generador\Controllers\BaseController@guardarYGenerarAutenticacion');
Route::post('/generador/eliminar_autenticacion', 'TesGen\Generador\Controllers\BaseController@eliminarAutenticacion');
Route::post('/generador/guardar_permisos', 'TesGen\Generador\Controllers\BaseController@guardarPermisos');
Route::post('/generador/elimimar_archivos_generados', 'TesGen\Generador\Controllers\BaseController@eliminarArchivosGenerados');
Route::post('/generador/generar_form', 'TesGen\Generador\Controllers\BaseController@generarFormulario');
