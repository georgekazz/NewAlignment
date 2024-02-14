<?php

use App\Admin\Controllers\ProjectController;
use App\Admin\Controllers\SettingsController;
use Illuminate\Routing\Router;

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
    Route::post('/upload/action', 'UploadController@uploadAction')->name('upload.action');
    Route::post('/mygraphs', 'App\Admin\Controllers\FileController@store')->name('mygraphs.store');
    Route::delete('/file/delete/{file}', 'FileController@destroy')->name('file.delete');
    Route::get('/myprojects', [ProjectController::class, 'index'])->name('myprojects');

    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::post('/settings', ['uses' => 'SettingsController@create', 'as' => 'settings.create']);

});
