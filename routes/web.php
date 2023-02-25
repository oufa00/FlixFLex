<?php

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
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::group(['middleware' => 'auth'], function () {
Route::get('/films', [App\Http\Controllers\FrontController::class, 'films'])->name('films');
Route::get('/films_favoris/{id}', [App\Http\Controllers\FrontController::class, 'films_favoris'])->name('films_favoris');
Route::get('/films_top/{id}', [App\Http\Controllers\FrontController::class, 'films_top'])->name('films_top');
Route::get('/series', [App\Http\Controllers\FrontController::class, 'series'])->name('series');
Route::post('/favoris', [App\Http\Controllers\FrontController::class, 'favoris']);
});