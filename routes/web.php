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

Auth::routes();

// USUARIOS
Route::name('users.')->prefix('users')->group(function () {
    // Página principal de usuarios
    Route::get('index', 'UserController@index')->name('index');
    // Guardar informacion del usuario
    Route::put('save_inf', 'UserController@save_inf')->name('save_inf');
    // Obtener todos los roles
    Route::get('get_roles', 'UserController@get_roles')->name('get_roles');
    // Guardar usuario (Individual)
    Route::post('store', 'UserController@store')->name('store');
    // Acru informacion del usuario
    Route::put('update', 'UserController@update')->name('update');
    // Guardar usuarios (Masivamente)
    Route::post('store_mass', 'UserController@store_mass')->name('store_mass');
    // Obtener los usuarios por roles
    Route::get('get_users', 'UserController@get_users')->name('get_users');
    // Deshabilitar/Habilitar usuario
    Route::put('des_habilitar', 'UserController@des_habilitar')->name('des_habilitar');
    // Obtener los usuarios por libro y rol
    Route::get('by_libro', 'UserController@by_libro')->name('by_libro');
    // Obtener los usuarios por estado y rol
    Route::get('by_estado', 'UserController@by_estado')->name('by_estado');
    // Descargar lista de usuarios por libro y rol
    Route::get('download_bysearch/{role_id}/{libro_id}/{estado}', 'UserController@download_bysearch')->name('download_bysearch');
});

// LIBROS
Route::name('libros.')->prefix('libros')->group(function () {
    // Obtener todos los libros (Por coincidencia)
    Route::get('show', 'LibroController@show')->name('show');
    // Obtener todos los libros (Por coincidencia) PAGINADO
    Route::get('show_paginate', 'LibroController@show_paginate')->name('show_paginate');
    // Guardar libro
    Route::post('store', 'LibroController@store')->name('store');
    // Obtener todos los libros
    Route::get('index', 'LibroController@index')->name('index');
    // Actualizar libro
    Route::put('update', 'LibroController@update')->name('update');
    // Desactivar/Activar libro
    Route::put('des_activar', 'LibroController@des_activar')->name('des_activar');
    // Guardar recurso
    Route::post('store_recurso', 'LibroController@store_recurso')->name('store_recurso');
    // Obtener recursos
    Route::get('get_recursos', 'LibroController@get_recursos')->name('get_recursos');
    // Actualizar recurso
    Route::put('update_recurso', 'LibroController@update_recurso')->name('update_recurso');
    // Obtener recursos no usados por libro
    Route::get('available_recursos', 'LibroController@available_recursos')->name('available_recursos');
    // Guardar recursos en el libro
    Route::post('save_recursos', 'LibroController@save_recursos')->name('save_recursos');
    // Obtener recursos por libro
    Route::get('recursos_bylibro', 'LibroController@recursos_bylibro')->name('recursos_bylibro');
    // Asignar link del recurso
    Route::put('set_link', 'LibroController@set_link')->name('set_link');
    // Eliminar recurso del libro
    Route::delete('delete_recursolibro', 'LibroController@delete_recursolibro')->name('delete_recursolibro');
    // Obtener los tipos
    Route::get('get_tipos', 'LibroController@get_tipos')->name('get_tipos');
    // Guardar enlaces
    Route::post('save_enlaces', 'LibroController@save_enlaces')->name('save_enlaces');
    // Obtener enlaces
    Route::get('get_enlaces', 'LibroController@get_enlaces')->name('get_enlaces');
    // Actualizar enlaces
    Route::put('update_enlaces', 'LibroController@update_enlaces')->name('update_enlaces');
    // Eliminar enlaces
    Route::delete('delete_enlaces', 'LibroController@delete_enlaces')->name('delete_enlaces');
    // Obtener libros por tipo
    Route::get('by_tipo', 'LibroController@by_tipo')->name('by_tipo');
    // Descargar lista de libros
    Route::get('download_bysearch/{libro}/{tipo_id}', 'LibroController@download_bysearch')->name('download_bysearch');
});

// *** USUARIOS
// ADMIN
Route::name('admin.')->prefix('admin')->group(function () {
    Route::get('usuarios', 'AdminController@usuarios')->name('usuarios');
    Route::get('libros', 'AdminController@libros')->name('libros');
});
