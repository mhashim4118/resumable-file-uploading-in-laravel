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




Route::any('/tus/{any?}', function () {
    return app('tus-server')->serve();
})->where('any', '.*');

Route::get('/', function () {
    return view('welcome');
});