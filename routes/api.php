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

// Route::post('roleList', 'Role\RoleController@getList');

// 无需token验证
Route::group([],function ($router) {  
    // login
    $router->post('login', 'AuthController@login');
    //logout
    $router->post('logout', 'AuthController@logout');
    // register
    $router->post('register', 'AuthController@register');
    $router->post('roleList', 'Role\RoleController@getList');
});

// 需要token验证
// Route::group(['middleware' =>'tokenOperate'],function ($router) {   
//     // role list
//     $router->post('roleList', 'Role\RoleController@getList');
// });



