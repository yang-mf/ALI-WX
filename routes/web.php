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


Route::get('/test/index','WX\WXController@index');
Route::get('/phpinfo','WX\WXController@info');
Route::get('/wx','WX\WXController@wx');
Route::get('/wx/receiv','WX\WXController@receiv');