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

/*Route::prefix('auth')->group(function($router) {
    $router->post('login', 'AuthController@login');
    $router->post('logout', 'AuthController@logout');
});*/

//Route::post('login', 'AuthController@login');

Route::post('login', function(Request $request)
{
    return $request->only(['username', 'password','method']);
});

/*Route::middleware('refresh.token', ['except' => ['login,logout']])
        ->group(function ($router) {
    // login
    $router->post('login', 'AuthController@login');
    //logout
    $router->post('logout', 'AuthController@logout');
});*/
