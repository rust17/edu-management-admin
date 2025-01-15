<?php

use App\Models\User;
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

    // 教师管理 - 只允许管理员访问
    $router->resource('teachers', 'TeacherController')->middleware('admin.role:admin');

    // 学生管理 - 允许管理员和教师访问
    $router->resource('students', 'StudentController')
        ->middleware('admin.role:admin,' . User::ROLE_TEACHER);
});

// 覆盖 Laravel-admin 的路由
Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {
    // 菜单管理 - 只允许管理员访问
    $router->resource('auth/menu', 'MenuController', ['except' => ['create']])
        ->names('admin.auth.menu')
        ->middleware('admin.role:admin');
});
