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
    echo '2333';
});



Route::group(['namespace' => 'Home'], function () {
    //当路由中的一个参数可有可无的时候，需要在路由中给予它一个?，并且，对应的方法需要给默认值，否则会报页面不存在
    // Route::get('/home/{id}/{name?}','HomeController@index');
    // resource demo
    Route::resource('home','HomeController');
});

