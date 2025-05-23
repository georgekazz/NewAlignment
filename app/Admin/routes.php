<?php

use App\Admin\Controllers\AboutController;
use App\Admin\Controllers\CreatelinksController;
use App\Admin\Controllers\LinkController;
use App\Admin\Controllers\ProjectController;
use App\Admin\Controllers\SettingsController;
use App\Admin\Controllers\TreeController;
use Illuminate\Routing\Router;
use App\Admin\Controllers\RegisterController;

Admin::routes();

Route::group([
    'prefix' => config('admin.route.prefix'),
    'namespace' => config('admin.route.namespace'),
    'middleware' => config('admin.route.middleware'),
    'as' => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');
    $router->resource('profile', \App\Admin\Controllers\ProfileController::class);
    $router->resource('mygraphs', \App\Admin\Controllers\FileController::class);


    Route::get('/about', [AboutController::class, 'index'])->name('about');
    Route::post('/upload/action', 'UploadController@uploadAction')->name('upload.action');
    Route::post('/mygraphs', 'App\Admin\Controllers\FileController@store')->name('mygraphs.store');
    Route::delete('/file/delete/{file}', 'FileController@destroy')->name('file.delete');
    Route::get('/myprojects', [ProjectController::class, 'index'])->name('myprojects');

    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::post('/settings','App\Admin\Controllers\SettingsController@create') -> name('settings.create');
    Route::get('/mylinks', [LinkController::class, 'index'])->name('mylinks');

    Route::get('/register', function () {
        return view('register');
    });

    Route::get('/force-directed-tree', [TreeController::class, 'index'])->name('forcetreewelcome');

});
