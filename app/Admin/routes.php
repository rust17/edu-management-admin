<?php

use Encore\Admin\Facades\Admin;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {
    // 首页
    $router->get('/', 'HomeController@index')->name('home');

    // 教师管理
    $router->resource('teachers', 'TeacherController');
});
