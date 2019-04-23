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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::post('/', 'HandleController@handle_post');

Route::post('/add_order', 'RestController@add_order');
Route::get('/get_order', 'RestController@get_order');
Route::post('update_order', 'RestController@update_order');
Route::post('update_photo', 'RestController@update_photo');
Route::get('export_pdf','ReportController@export_pdf');
Route::get('export_xls','ReportController@export_exl');
Route::get('export_csv','ReportController@export_csv');