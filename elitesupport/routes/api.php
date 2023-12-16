<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocationController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// Route::post('callback', 'App\Http\Controllers\CallbackController@store')->name('callback');
// Route::get('auto-msu','App\Http\Controllers\CronJobController@autoMSU')->name('auto-msu');
// Route::get('msu-failed-mail','App\Http\Controllers\CronJobController@msuFailedMail')->name('msu-failed-mail');
// Route::get('auto-escalate', 'App\Http\Controllers\CronJobController@autoEscalate')->name('auto-escalate');
// Route::get('psf-escalate','App\Http\Controllers\CronJobController@psfEscalate')->name('psf-escalate');

// Route::get('cti-ticket/{ticket_id}', [LocationController::class, 'createCTI'])->name('cti-ticket');
// Route::post('ticket-update', [LocationController::class, 'ticketUpdate'])->name('ticket-update');