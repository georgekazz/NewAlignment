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
    
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::post('/settings','App\Admin\Controllers\SettingsController@create') -> name('settings.create');


});