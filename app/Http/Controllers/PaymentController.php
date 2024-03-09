<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Stripe\Stripe;
use Stripe\PaymentIntent;
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
		switch ($userType) {
			case 'newuser':
				return view('payment.newuser', compact('shop', 'amount'));
				break;
			case 'userPayment':
				$customer = customer::where('user_id', $userId)->first();
				break;
			case 'guest':
				$customer = customer::where('customer_code', $userId)->first();
				break;
		}
	}

	public function newUser(Request $req)
	{
		$validatedData = $req->validate([
			'name' => 'required', // nameは必須
			'mailaddress' => 'required|email', // emailは必須であり、有効なメールアドレス形式であること
			'amount' => 'required|integer',
		]);
		try {
			DB::beginTransaction();
			$stripeFanc = new \App\Lib\StripeFanc();
			$result = $stripeFanc->createCustomer(
				$req->name,
				$req->mailaddress,
				[
					'code' => $req->code
				],
				$req->stripeToken,
			);
			$ccode = common::makeCustomerCode();
			$cus = new customer;
			$cus->shopCode = $req->code;
			$cus->customer_id = $ccode;
			$cus->name = $req->name;
			$cus->email = $req->mailaddress;
			$cus->save();

			$plc = new PlatCustomer;
			$plc->customer_id = $ccode;
			$plc->plat_name = 'stripe';
			$plc->plat_id = $result->id;
			$plc->save();

			// operate log
			$log = new OperateLog;
			$log->shop_code = $req->code;
			$log->type = 'web';
			$log->operate = 'newUser&Payment';
			$log->memo = $result->id;
			$log->save();

			// 決済処理


			DB::commit();
		} catch (ApiErrorException $e) {
			return response()->json(['success' => false, 'error' => $e->getMessage()]);
		}
	}
}
