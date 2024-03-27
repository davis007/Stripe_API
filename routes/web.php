<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\paymentController;
use App\Http\Controllers\PaymentController as ControllersPaymentController;

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

Route::get('payment/{shopCode}/{amount}/{userType}/{userId?}', [PaymentController::class, 'payment']);
Route::post('payment/newUser', [PaymentController::class, 'newUser'])->name('newUser');
Route::post('payorder/payment', [PaymentController::class, 'userPayment'])->name('userPayment');
Route::post('payment/guest', [PaymentController::class, 'paymentGuest'])->name('paymentGuest');
Route::get('register/{shopCode}/{userId}', [PaymentController::class, 'registCard']);
Route::post('regist/card', [PaymentController::class, 'registerCard'])->name('registCard');

Route::prefix('test')->group(function () {
	Route::get('payment', [TestController::class, 'payment']);
	Route::post('payment', [TestController::class, 'makeToken']);
	Route::get('refund', [TestController::class, 'refund']);
	Route::get('createCustomer', [TestController::class, 'customer']);
	Route::post('createCustomer', [TestController::class, 'addCustomer']);
});


Route::middleware('auth')->group(function () {
	Route::get('/home', [HomeController::class, 'index'])->name('home');
	Route::get('/basics', [HomeController::class, 'basics']);
	Route::get('/customers', [HomeController::class, 'customers']);
	Route::get('/customer/details/{id}', [HomeController::class, 'custDeails']);
	Route::post('/create/customer', [HomeController::class, 'addCustomer']);
	Route::get('/delete/customer/{customer_id}', [HomeController::class, 'deleteCustomer']);
	Route::get('/sales', [HomeController::class, 'sales']);
	Route::get('/sales/refund/{refund_id}', [HomeController::class, 'salesRefund']);
	Route::get('/settings', [HomeController::class, 'settings']);
	Route::post('/regenerateApiKey', [HomeController::class, 'regenerateApiKey']);
	Route::get('/logs', [HomeController::class, 'apiLogs']);
});
