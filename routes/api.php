<?php

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;

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

Route::get('payment', function () {
	return 'hello';
});

// APIキー認証ミドルウェアを適用したルートグループ
Route::middleware('api_key')->group(function () {
	// 認証テスト 成功するとmessage:Hello!が帰ってくる
	Route::get('/hello', [ApiController::class, 'hello']);
	// 顧客情報
	Route::post('/customer/status/', [ApiController::class, 'customerStatus']);
	// 顧客登録
	Route::post('/registCustomer', [ApiController::class, 'registCustomer']);
	// 決済取消し(返金)

	// 決済履歴一覧
	Route::post('/payment/shop', [ApiController::class, 'shopPayments']);
});
