<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GetData;
use App\Http\Controllers\GetDataByMoNo;
use App\Http\Controllers\pdfController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\DB;
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





Route::post('/get-data', [GetData::class, 'getData']);
Route::get('/get-data-by-mo-no/{moNo}', [GetDataByMoNo::class, 'getDataByMoNo']);

Route::get('/', [pdfController::class, 'index']);
Route::post('/show', [pdfController::class, 'showChart']);





