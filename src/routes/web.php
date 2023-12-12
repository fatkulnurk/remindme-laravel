<?php

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
    return \Inertia\Inertia::render('Index', [
        'app_url' => config('app.url')
    ]);
});

// fallback if hit api without header Accept: application/json
Route::get('/login', fn() => abort(404))->name('login');
