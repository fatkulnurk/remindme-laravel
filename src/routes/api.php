<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/session', [\App\Http\Controllers\Api\SessionController::class, 'login']);
Route::put('/session', [\App\Http\Controllers\Api\SessionController::class, 'refresh']);
Route::apiResource('/reminders', App\Http\Controllers\Api\ReminderController::class)
    ->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
