<?php

use App\Admin\Controllers\FileController;
use App\Admin\Controllers\FileUploadController;
use App\Admin\Controllers\ProjectController;
use App\Admin\Controllers\SettingsController;
use App\Admin\Controllers\TreeController;
use Illuminate\Support\Facades\Route;
use App\Admin\Controllers\CreatelinksController;
use App\Admin\Controllers\LinkController;
use App\Admin\Controllers\RegisterController;

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

Route::get('/register', function () {
    return view('register');
});

Route::post('/register', [RegisterController::class, 'register'])->name('register');

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

    Route::get('/createlinks/{project?}', [CreatelinksController::class, 'index']) -> name('createlinks');
    Route::post('/createlinks/utility/create', 'App\Admin\Controllers\LinkController@create') -> name('mylinks.create');
    Route::get('/createlinks/json_serializer/{file}', 'App\Admin\Controllers\CreatelinksController@json_serializer')-> name('createlinks.json');
    Route::post('/createlinks/utility/infobox', 'App\Admin\Controllers\CreatelinksController@short_infobox')-> name('createlinks.infobox');
    Route::post('/createlinks/utility/comparison/{project?}', 'App\Admin\Controllers\CreatelinksController@comparison')-> name('createlinks.comparison');
    Route::get('/createlinks/utility/connected', 'App\Admin\Controllers\LinkController@connected') -> name ('mylinks.connected');

    Route::post('/linktype/update', 'App\Admin\Controllers\LinkTypeController@updateForm')->name('linktypes.update');
    Route::get('link/ajax', 'App\Admin\Controllers\LinkController@ajax')->name('links.ajax');

    Route::post('createlinks/utility/link_table/{project?}', 'App\Admin\Controllers\LinkController@project_links') -> name ('createlinks.project_links');
    Route::post('/mylinks/import', 'App\Admin\Controllers\LinkController@import') -> name ('links.import');
    Route::get('createlinks/utility/export_table', 'App\Admin\Controllers\LinkController@export') -> name('mylinks.export');

    Route::delete('createlinks/utility/delete/{id}', 'App\Admin\Controllers\LinkController@destroy') -> name('mylinks.delete');
    Route::get('/mylinks', [LinkController::class, 'index'])->name('mylinks');

    //Force Directed Tree
    Route::get('/force-directed-tree', [TreeController::class, 'index'])->name('forcetreewelcome');

    Route::get('/chart', function () {
        return view('chart');
    })->name('chart');

    Route::post('/upload', [FileUploadController::class, 'upload'])->name('upload');
    Route::get('/force-directed-tree-main', [TreeController::class, 'showmainpage'])->name('forcetreemain');
    Route::get('/tree-data', [TreeController::class, 'getTreeData'])->name('tree-data');
    Route::post('/process-file/{fileId}', [FileUploadController::class, 'processFile'])->name('process-file');



});