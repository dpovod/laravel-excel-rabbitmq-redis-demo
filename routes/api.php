<?php

use App\Http\Controllers\Backend\RowsController;
use App\Http\Controllers\Backend\UploadController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('upload')->group(function () {
    Route::post('submit-file', [UploadController::class, 'submitFile'])->name('api.submitFile');
});

Route::prefix('rows')->group(function () {
    Route::get('list', [RowsController::class, 'getList'])->name('api.rowsList');
});
