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
    // Homepage
    $router->get('/', 'HomeController@index')->name('home');

    // Teacher management - admin only
    $router->resource('teachers', 'TeacherController')->middleware('admin.role:admin');

    // Student management - admin and teachers
    $router->resource('students', 'StudentController')
        ->middleware('admin.role:admin,' . User::ROLE_TEACHER);
});

// Override Laravel-admin routes
Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {
    // Menu management - admin only
    $router->resource('auth/menu', 'MenuController', ['except' => ['create']])
        ->names('admin.auth.menu')
        ->middleware('admin.role:admin');
});
