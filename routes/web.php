<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
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

Auth::routes();
Route::get('/', function () {
	return view('welcome');
});

Route::prefix('test')->group(function () {
	Route::get('payment', [TestController::class, 'payment']);
	Route::post('payment', [TestController::class, 'makeToken']);
});


Route::middleware('auth')->group(function () {
	Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});
