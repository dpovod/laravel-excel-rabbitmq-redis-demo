<?php

use App\Http\Controllers\Frontend\RowsController;
use App\Http\Controllers\Frontend\UploadController;
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
    Route::get('/', [UploadController::class, 'showUploadForm'])->name('web.showUploadForm');
});

Route::prefix('rows')->group(function () {
    Route::get('/', [RowsController::class, 'listRows'])->name('web.listRows');
});
