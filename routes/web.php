<?php

use App\Http\Controllers\RowsController;
use App\Http\Controllers\UploadController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::prefix('upload')->group(function () {
    Route::get('/', [UploadController::class, 'showUploadForm'])->name('showUploadForm');
    Route::post('submit-file', [UploadController::class, 'submitFile'])->name('submitFile');
});

Route::prefix('rows')->group(function () {
    Route::get('/', [RowsController::class, 'listRows'])->name('listRows');
});