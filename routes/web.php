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

/*Route::get('/', function () {
    return view('welcome');
});
Route::get('/home', 'HomeController@index')->name('home');*/

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::group(['middleware' => ['auth.admin'], 'namespace' => 'Admin', 'prefix' => 'admin'], function () {
    //后台首页
    Route::get('index', ['as' => 'admin.index', 'uses' => 'IndexController@index']);

    //权限管理(菜单)路由
    Route::get('permission/{fid?}', ['as' => 'admin.permission.index', 'uses' => 'PermissionController@index']);
    Route::post('permission/index', ['as' => 'admin.permission.index', 'uses' => 'PermissionController@index']); //查询
    Route::get('permission/{fid}/create', ['as' => 'admin.permission.create', 'uses' => 'PermissionController@create']);
    Route::resource('permission', 'PermissionController',[ 'as' => 'admin']);
    Route::put('permission/update', ['as' => 'admin.permission.edit', 'uses' => 'PermissionController@update']); //修改
    Route::post('permission/store', ['as' => 'admin.permission.create', 'uses' => 'PermissionController@store']); //添加

    //角色管理路由
    Route::get('role/index', ['as' => 'admin.role.index', 'uses' => 'RoleController@index']);
    Route::post('role/index', ['as' => 'admin.role.index', 'uses' => 'RoleController@index']);
    Route::resource('role', 'RoleController',[ 'as' => 'admin']);
    Route::put('role/update', ['as' => 'admin.role.edit', 'uses' => 'RoleController@update']); //修改
    Route::post('role/store', ['as' => 'admin.role.create', 'uses' => 'RoleController@store']); //添加

    //用户管理路由

    Route::get('user/index', ['as' => 'admin.user.index', 'uses' => 'UserController@index']);  //用户管理
    Route::post('user/index', ['as' => 'admin.user.index', 'uses' => 'UserController@index']);
    Route::resource('user', 'UserController',[ 'as' => 'admin']);
    Route::put('user/update', ['as' => 'admin.user.edit', 'uses' => 'UserController@update']); //修改
    Route::post('user/store', ['as' => 'admin.user.create', 'uses' => 'UserController@store']); //添加

    Route::post('adposition/index', ['as' => 'admin.adposition.index', 'uses' => 'AdpositionController@index']);  //用户管理
    Route::resource('adposition', 'AdpositionController',[ 'as' => 'admin']);

    Route::get('customer/index', ['as' => 'customer', 'uses' => 'CustomerController@index']);  //用户管理
    Route::post('customer/index', ['as' => 'admin.customer.index', 'uses' => 'CustomerController@index']);  //用户管理
    Route::resource('customer', 'CustomerController',[ 'as' => 'admin']);

});