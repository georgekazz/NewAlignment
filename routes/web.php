<?php

use App\Admin\Controllers\FileController;
use App\Admin\Controllers\ProjectController;
use App\Admin\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => ['web']], function () {
    
    Route::view('/login', 'auth.login')->name('login');

    Route::post('/upload/action', 'UploadController@uploadAction')->name('upload.action');
    Route::post('/mygraphs', 'App\Admin\Controllers\FileController@store')->name('mygraphs.store');
    Route::get('/mygraphs', [FileController::class, 'mygraphs'])->name('mygraphs');
    Route::delete('/file/delete/{file}', [FileController::class, 'destroy'])->name('file.delete');
    Route::post('/file/parse/{file}', [FileController::class, 'parse'])->name('mygraphs.parse');

    Route::get('/myprojects', [ProjectController::class, 'index'])->name('myprojects');
    Route::delete('myprojects/delete/{id}', 'App\Admin\Controllers\ProjectController@destroy')->name('myprojects.delete');

    
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::post('/settings','App\Admin\Controllers\SettingsController@create') -> name('settings.create');

    Route::get('/settings/ajax','App\Admin\Controllers\SettingsController@ajax')-> name('settings.ajax');
    Route::get('settings/validate','App\Admin\Controllers\SettingsController@validateSettingsFile')->name('settings.validate');
    Route::delete('settings/delete/{id}', 'App\Admin\Controllers\SettingsController@destroy') -> name('settings.delete');
    Route::post('settings/create_config/{project_id}', 'App\Admin\Controllers\SettingsController@create_config')->name('settings.create_config');



    Route::post('/myprojects', 'App\Admin\Controllers\ProjectController@create') -> name('myprojects.create');

});