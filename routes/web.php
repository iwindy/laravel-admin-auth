<?php

use Illuminate\Routing\Router;

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace' => '\Encore\Admin\Http\Controllers',
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.as'),
],function (Router $router){
    $router->post('_handle_form_', 'HandleController@handleForm')->name('handle_form');
    $router->post('_handle_action_', 'HandleController@handleAction')->name('handle_action');
    $router->get('_handle_selectable_', 'HandleController@handleSelectable')->name('handle_selectable');
    $router->get('_handle_renderable_', 'HandleController@handleRenderable')->name('handle_renderable');

    // requirejs配置
    $router->get('_require_config', 'PagesController@requireConfig')->name('require-config');

    $router->fallback('PagesController@error404')->name('error404');

    $authController = config('admin.auth.controller', AuthController::class);

    /* @var \Illuminate\Routing\Router $router */
    $router->get('auth/login', $authController . '@getLogin')->name('login');
    $router->post('auth/login', $authController . '@postLogin')->name('login_post');
    $router->get('auth/logout', $authController . '@getLogout')->name('logout');
    $router->get('auth/setting', $authController . '@getSetting')->name('setting');
    $router->put('auth/setting', $authController . '@putSetting')->name('setting_put');
});

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => '\Iwindy\Auth\Http\Controllers',
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.as'),
], function (Router $router) {
    $router->resource('auth/roles', 'RolesController');
    $router->resource('auth/admin', 'AdminController');
    $router->resource('auth/users', 'UserController')->names('auth_users');
    $router->resource('auth/menu', 'MenuController', ['except' => ['create']])->names('auth_menus');
});


Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.as'),
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');

});



