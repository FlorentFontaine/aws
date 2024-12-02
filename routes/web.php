<?php

use App\Http\Controllers\EcDeuxController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RdsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;

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
Route::get('/create-ticket', function () {
    return view('create_ticket');
})->name('tickets.create');

Route::post('/create-ticket', [TicketController::class, 'store'])->name('tickets.store');

Route::get('/', [RdsController::class, 'rds'])->name('rds');
Route::get('/ec-deux-instance', [EcDeuxController::class, 'ecDeuxInstance'])->name('ecdeuxinstance');
Route::get('/ec-deux-volume', [EcDeuxController::class, 'ecDeuxVolume'])->name('ecdeuxvolume');
Route::get('/report', [ReportController::class, 'reportErreursLogs'])->name('report');
Route::get('/report/{arn}/{identifier}', [ReportController::class, 'reportErreursLogs'])->name('report');

Route::put('/update-tag/{identifier}', [RdsController::class, 'updateTag'])->name('update-tag');
Route::put('/update-tagEcDeux/{identifier}', [EcDeuxController::class, 'updateTagEcDeux'])->name('update-tagEcDeux');
Route::put('/update-tagVolumeEcDeux/{identifier}', [EcDeuxController::class, 'updateTagVolumeEcDeux'])->name('update-tagVolumeEcDeux');
