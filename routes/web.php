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

Route::get('/info', function () {
    phpinfo();
});

Route::get('/req','TestController@req');
Route::get('/encrypt','TestController@encrypt');
Route::get('/encrypt2','TestController@encrypt2');

Route::get('/rsa1','TestController@rsa1');

//curl测试
Route::get('/curl1','TestController@curl1');
Route::any('/curl2','TestController@curl2');
Route::get('/curl3','TestController@curl3');
Route::get('/curl4','TestController@curl4');

//签名
Route::get('/sign1','TestController@sign1');
Route::get('/sign2','TestController@sign2');
