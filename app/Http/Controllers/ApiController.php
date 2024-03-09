<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\customer;
use App\Models\OperateLog;
use App\Models\PlatCustomer;
use MyStripe;
use common;

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
				$request->email
			);

			$spc = $user->shop_code;
			$ccode = common::makeCustomerCode();
			$rst = common::addCustomerDB($request, $spc, $ccode, $result->id, 'stripe', 'api');

			return response()->json([
				'success' => true,
				'shopCode' => $spc,
				'name' => $request->name,
				'email' => $request->email,
				'customer_id' => $ccode,
			]);
		} catch (ApiErrorException $e) {

			return response()->json(['success' => false, 'error' => $e->getMessage()]);
		}
	}
	// カード登録＆顧客新規登録
	// 決済取消し(返金)
}
