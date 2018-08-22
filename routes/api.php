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
});

// 需要token验证
Route::group(['middleware' =>'token'],function ($router) {   
    // role部分是已经写好的，就暂时不做更改
    // role list
    $router->post('roleList', 'Role\RoleController@getList');
    // add role 
    $router->post('addRole', 'Role\RoleController@addRole');
    // edit role 
    $router->put('editRole', 'Role\RoleController@editRole');
    // delete role 
    $router->delete('delRole', 'Role\RoleController@delRole');

    // role permission 
    $router->get('rolePermission', 'Permission\PermissionController@getRolePermission');
    // add、edit permission
    $router->post('permissionOperate', 'Permission\PermissionController@addOrEditPermission');
});








