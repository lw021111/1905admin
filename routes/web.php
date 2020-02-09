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

Route::any('/reg','TestController@reg');
Route::any('/login','TestController@login');
Route::any('/time','TestController@showTime');//获取数据
Route::any('/auth','TestController@auth');//鉴权
Route::any('/check','TestController@check');
Route::post('/test/md5test2','TestController@md5test2');//验证签名
Route::any('/decrypt','TestController@decrypt');
