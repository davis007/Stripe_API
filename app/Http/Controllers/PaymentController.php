<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\customer;
use App\Models\OperateLog;
use App\Models\payment;
use MyStripe;
use common;

class PaymentController extends Controller
{
	public function payment($shopCode, $amount, $userType, $userId = null)
	{
		$dd = common::makeCustomerCode();
		$validator = Validator::make([
			'shopCode' => $shopCode,
			'amount' => $amount,
			'userType' => $userType,
		], [
			'shopCode' => 'required|string',
			'amount' => 'required|numeric|min:500',
			'userType' => 'required|string|in:newuser,userPayment,guest',
		]);

		if ($validator->fails()) {
			return response()->json([
				'error' => $validator->errors()->all(),
			], 422);
		}

		$shop = User::where('shop_code', $shopCode)->first();

		if ($shop === null) {
			return response()->json([
				'error' => 'ShopCode not found',
			], 404);
		}

		// 続けてデータ処理を行う
	}
}
