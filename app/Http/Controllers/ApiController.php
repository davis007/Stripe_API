<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\customer;
use App\Models\payment;
use App\Models\OperateLog;
use App\Models\PlatCustomer;
use App\Models\PlatCard;
use MyStripe;
use common;

class ApiController extends Controller
{
	public function hello()
	{
		return response()->json(['message' => 'Hello!']);
	}
	// カード登録
	// 顧客情報
	public function customerStatus(Request $request)
	{
		try {
			// POST /api/customers/{shopCode}/{customerId}
			$cus = Customer::where('customer_id', $request->input('customerId'))->firstOrFail();
			$plc = PlatCustomer::where('customer_id', $cus->customer_id)->firstOrFail();
			$pay = Payment::where('customer_id', $plc->plat_id)
				->orderBy('id', 'desc')
				->get()
				->map(function ($payment) {
					return [
						'payment_id' => $payment->payment_log,
						'customer_id' => $payment->customer_id,
						'currency' => 'jpy',
						'amount' => number_format($payment->amount),
						'registered_at' => $payment->created_at->format('Y-m-d H:i'),
					];
				});

			$pcd = PlatCard::where('customer_id', $cus->customer_id)
				->get()
				->map(function ($card) {
					return [
						'card_id' => $card->card_id,
						'plat_name' => $card->plat_name,
						'plat_card' => $card->plat_card,
						'brand' => $card->brand,
						'last4' => $card->last4,
						'exp_month' => $card->exp_month,
						'exp_year' => $card->exp_year,
						'created_at' => $card->created_at->format('Y-m-d H:i'),
					];
				});

			$data = [
				'success' => true,
				'name' => $cus->name,
				'customer_id' => $cus->customer_id,
				'shop_code' => $cus->shopCode,
				'email' => $cus->email,
				'created_at' => $cus->created_at->format('Y-m-d H:i'),
				'purchase_history' => $pay,
				'registered_cards' => $pcd,
			];

			return response()->json($data);
		} catch (\Exception $e) {
			return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
		}
	}

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
	// 決済取消し(返金)

	// shop決済履歴一覧

	public function shopPayments(Request $request, $page = 50)
	{
		$validatedData = $request->validate([
			'shopCode' => 'required',
			'perPage' => 'nullable|integer|max:50',
		]);

		$perPage = $validatedData['perPage'] ?? $page;
		$pays = Payment::where('shop_id', $validatedData['shopCode'])
			->paginate($perPage);

		return $pays;
	}
}
