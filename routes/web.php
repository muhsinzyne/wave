<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Wave\Wave;
use TCG\Voyager\Voyager;

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

// Authentication routes
Auth::routes();

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

// Include Wave Routes
Wave::routes();

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
