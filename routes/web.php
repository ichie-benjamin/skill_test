<?php

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

Route::get('/', 'ProductController@index')->name('home');

Route::post('api/product', 'ProductController@store');

Route::put('api/product/{id}', 'ProductController@update');

Route::delete('/api/product/{id}','ProductController@destroy');
