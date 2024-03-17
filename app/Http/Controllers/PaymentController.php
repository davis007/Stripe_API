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
use App\Models\PlatCustomer;
use App\Models\PlatCard;
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
				$customer = customer::where('customer_id', $userId)->first();
				$platC = PlatCard::where('customer_id', $userId)->get();
				if ($customer === null) {
					return response()->json([
						'error' => 'ユーザーデータが登録されていません。',
					], 404);
				}
				//echo $customer . ' ' . $plat;
				return view('payorder', compact('platC', 'customer', 'amount'));
				break;
			case 'guest':
				$customer = $shop;
				return view('payment.guestPayment', compact('shop', 'amount', 'userType', 'customer'));
				break;
		}
	}

	public function paymentGuest(Request $req)
	{
		//dd($req);
		$shop = User::where('shop_code', $req->code)->first();

		if (!$shop) {
			return response()->json([
				'error' => 'Code not found',
			], 404);
		}
		$stripeFanc = new \App\Lib\StripeFanc();
		$charge     = $stripeFanc->charge($req->stripeToken, $req->amount, ['payment' => 'ゲスト決済']);

		if ($charge->status == 'succeeded') {
			$pay = new payment;
			$pay->shop_id = $req->input('code');
			$pay->payment_log = $charge['id'];
			$pay->customer_id = 'guest決済';
			$pay->amount = $req->input('amount');
			$pay->save();

			common::atLog($req->code, 'web', '決済処理:' . $req->input('amount') . ' guest決済', $charge['id']);
			return redirect()->back()->with('msg', '決済処理が完了しました。');
		} else {
			return redirect()->back()->with('msg', '決済処理に失敗しました。');
		}
	}

	public function newUser(Request $req)
	{
		$validatedData = $req->validate([
			'name' => 'required', // nameは必須
			'email' => 'required|email', // emailは必須であり
			'amount' => 'required|integer',
		]);
		//"_token" => "lYPClMOAoEXeTsJTNjogCKDClX2umL6au4lRWBdc"
		//  "code" => "6JUR"
		//  "name" => "玉手箱"
		//  "email" => "tamate@bako.com"
		//  "amount" => "2500"
		//  "stripeToken" => "tok_1Ot5zDJX4jQMJo2Wz5SWHCBF"
		//]
		try {
			DB::beginTransaction();
			$stripeFanc = new \App\Lib\StripeFanc();
			// 顧客制作
			$result = $stripeFanc->createCustomer($req->name, $req->email);
			// DB記録
			$ccode = common::makeCustomerCode();
			$adcus = common::addCustomerDB($req, $req->code, $ccode, $result->id, 'stripe', 'web');

			// 顧客にカード情報ヒモ付け
			$atcard = $stripeFanc->attachSetupIntents($req->code, $result->id, $req->input('stripeToken'), $ccode);
			DB::commit();

			// 決済処理
			DB::beginTransaction();
			$paymentIntent = $stripeFanc->paymentIntent($req->code, $req->input('amount'), $result->id, $atcard->id);
			if ($paymentIntent->status == 'succeeded') {
				$pay = new payment;
				$pay->shop_id = $req->code;
				$pay->payment_log = $paymentIntent->id;
				$pay->customer_id = $result->id;
				$pay->amount = $req->input('amount');
				$pay->save();
				// operateLog記録
				common::atLog($req->code, 'web', '決済処理:' . $req->input('amount') . ' ' . $result->id, $paymentIntent->id);
			}
			DB::commit();
			return redirect()->back()->with('msg', '決済処理が完了しました。');
		} catch (ApiErrorException $e) {
			DB::rollBack();
			return redirect()->back()->with('msg', '決済処理に失敗しました。' . $e->getMessage());
		}
	}

	public function userPayment(Request $req)
	{
		//dd($req);
		$cus  = customer::where('customer_id', $req->input('customer_id'))->first();
		$pcus = PlatCustomer::where('customer_id', $req->input('customer_id'))->first();
		$pcar = PlatCard::where('customer_id', $req->input('customer_id'))->first();
		DB::beginTransaction();
		$stripeFanc = new \App\Lib\StripeFanc();
		$paymentIntent = $stripeFanc->payUser($req->input('amount'), $pcus->plat_id, $pcar->plat_card);
		if ($paymentIntent->status == 'succeeded') {
			$pay = new payment;
			$pay->shop_id = $req->input('code');
			$pay->payment_log = $paymentIntent->id;
			$pay->customer_id = $pcus->plat_id;
			$pay->amount = $req->input('amount');
			$pay->save();
			// operateLog記録
			common::atLog($req->input('code'), 'web', '決済処理:' . $req->input('amount') . ' ' . $pcus->plat_id, $paymentIntent->id);
			DB::commit();
			return redirect()->back()->with('msg', '決済処理が完了しました。');
		} else {
			DB::rollBack();
			return redirect()->back()->with('msg', '決済処理に失敗しました。' . $e->getMessage());
		}
	}
}
