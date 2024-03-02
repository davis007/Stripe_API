<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\customer;
use App\Models\OperateLog;
use MyStripe;

class ApiController extends Controller
{
	public function hello()
	{
		return response()->json(['message' => 'Hello!']);
	}
	// カード登録
	// カード決済
	// 顧客登録

	public function registCustomer(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'name' => 'required', // nameは必須
			'email' => 'required|email', // emailは必須であり、有効なメールアドレス形式であること
		]);

		if ($validator->fails()) {
			return response()->json(['errors' => $validator->errors()], 422);
		}
		/* 必須パラメータ
		* name
		* email
		*/
		$user = $request->attributes->get('user');
		try {
			$stripeFanc = new \App\Lib\StripeFanc();
			$result = $stripeFanc->createCustomer(
				$request->name,
				$request->email,
				[
					'shop_code' => $user->shop_code,
					'name' => $request->name,
					'email' => $request->email,
				],
			);
			$cus = new customer;
			$cus->shopCode    = $user->shop_code;
			$cus->name        = $request->name;
			$cus->email       = $request->email;
			$cus->customer_id = $result->id;
			$cus->save();
			return response()->json([
				'success' => true,
				'shopCode' => $user->shop_code,
				'name' => $request->name,
				'email' => $request->email,
				'customer_id' => $result->id,
			]);
		} catch (ApiErrorException $e) {
			return response()->json(['success' => false, 'error' => $e->getMessage()]);
		}


		return response()->json(['shop_code' => $user->shop_code]);
	}
	// カード登録＆顧客新規登録
	// 決済取消し(返金)
}
