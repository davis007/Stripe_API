<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MyStripe;

class TestController extends Controller
{
	// Paymentフォーム view
	public function payment()
	{
		return view('test.payment');
	}


	// 顧客登録 view
	public function customer()
	{
		return view('test.customer');
	}

	public function addCustomer(Request $req)
	{
		$validatedData = $req->validate([
			'name' => 'required', // nameは必須
			'mailaddress' => 'required|email', // emailは必須
		]);

		$stripeFanc = new \App\Lib\StripeFanc();
		$result = $stripeFanc->createCustomer(
			$req->name,
			$req->mailaddress,
			'めためた',
		);
		return $result;
	}

	// 支払い
	public function makeToken(Request $request)
	{
		//dd($request);
		// 単発支払い User情報なしで決済
		if ($request->registration_option == 'payment') {
			if ($request->payment_amount) {
				$stripeFanc = new \App\Lib\StripeFanc();
				$result = $stripeFanc->charge($request->stripeToken, $request->payment_amount);
				return $result;
			} else {
				return redirect()->back()->with('msg', '決済の場合 金額を入力して下さい。');
			}
		} else {
			try {
				// ユーザー作ってクレカ登録(ヒモ付け)
				$stripeFanc = new \App\Lib\StripeFanc();
				$cus = $stripeFanc->customerAndCard(
					$request->user_name,
					$request->mailaddress,
					$request->stripeToken,
					'めためた'
				);
				dd($cus);
				//"id" => "cus_PdlbIgfqNofV13"
				//"object" => "customer"
				//"address" => null
				//"balance" => 0
				//"created" => 1709053438
				//"currency" => null
				//"default_source" => "card_1OoU06JX4jQMJo2WyE0mOSru"
				//"delinquent" => false
				//"description" => null
				//"discount" => null
				//"email" => "agdaemon@gmail.com"
				//"invoice_prefix" => "382226D0"
				//"invoice_settings" =>
				//Stripe
				//\
				//StripeObject {#376 ▶}
				//"livemode" => false
				//"metadata" =>
				//Stripe
				//\
				//StripeObject {#377 ▶}
				//"name" => "テストユーザー"
				//"next_invoice_sequence" => 1
				//"phone" => null
				//"preferred_locales" => []
				//"shipping" => null
				//"tax_exempt" => "none"
				//"test_clock" => null
			} catch (ApiErrorException $e) {
				// エラーハンドリング
				return response()->json(['success' => false, 'error' => $e->getMessage()]);
			}
		}
	}

	// 返金
	public function refund(Request $req)
	{
		if ($req->PaymentID) {
			$stripeFanc = new \App\Lib\StripeFanc();
			$result = $stripeFanc->refund($req->PaymentID);
			return $result;
		}
	}
}
