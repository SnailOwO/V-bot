<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::post('login', 'AuthController@login');

Route::group(['refresh.token',['except' => ['login,logout']]],function ($router) {
    // login
    $router->post('login', 'AuthController@login');
    //logout
    $router->post('logout', 'AuthController@logout');
});
