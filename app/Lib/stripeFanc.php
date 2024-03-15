<?php

namespace App\Lib;

use Illuminate\Support\Facades\DB;
use \Stripe\StripeClient;
use Stripe\PaymentIntent;
use Stripe\PaymentMethod;
use App\Models\card;
use App\Models\User;
use App\Models\customer;
use App\Models\OperateLog;
use App\Models\payment;
use App\Models\PlatCard;
use common;

class StripeFanc
{
	protected $stripe;

	public function __construct()
	{
		$this->stripe = new StripeClient(env('STRIPE_TEST_SECRET'));
	}

	public function cardToken($token)
	{
		try {
			$payCard = $this->stripe->paymentMethods->create([
				'type' => 'card',
				'card' => ['token' => $token],
			]);

			return $payCard;
		} catch (\Stripe\Exception\ApiErrorException $e) {
			// エラーハンドリング
			return 'S API error: ' . $e->getMessage();
		}
	}

	/**
	 * 即時決済処理
	 * $token: STRIPEから返却されたCardトークン tok_から始まるID
	 * $amount: 決済額
	 **/
	public function charge($token, $amount, $meta = null)
	{
		try {
			$paymentMethod = $this->stripe->paymentMethods->create([
				'type' => 'card',
				'card' => ['token' => $token],
			]);

			$paymentIntent = $this->stripe->paymentIntents->create([
				'amount' => $amount,
				'currency' => 'jpy',
				'payment_method' => $paymentMethod->id, // 作成されたPaymentMethodのIDを使用
				'confirm' => true,
				'automatic_payment_methods' => [
					'enabled' => true,
					'allow_redirects' => 'never',
				],
				'metadata' => $meta,
			]);

			return $paymentIntent->id;
		} catch (\Stripe\Exception\ApiErrorException $e) {
			// エラーハンドリング
			return 'S API error: ' . $e->getMessage();
		}
	}

	public function paymentIntent($shopcode, $amount, $customerId, $paymentMethodId)
	{
		try {
			$paymentIntent = $this->stripe->paymentIntents->create([
				'amount' => $amount, // amount in JPY
				'currency' => 'jpy',
				'customer' => $customerId,
				'payment_method' => $paymentMethodId,
				'off_session' => true,
				'confirm' => true,
			]);

			return $paymentIntent;
		} catch (\Stripe\Exception\ApiErrorException $e) {
			// エラーハンドリング
			return 'S API error: ' . $e->getMessage();
		}
	}

	/**
	 *取り消し処理
	 * $chargeId: 決済ID 'pi_xxxxx' pi_から始まるID
	 * @param = cancellation_reason
	 * 		duplicate, 重複
	 * 		fraudulent, 不正
	 * 		requested_by_customer, お客様の申し出
	 * 		abandoned 放棄
	 **/
	public function refund($chargeId, $reason = null)
	{
		$params = [];
		if (is_null($reason)) {
			$reason = '記載なし';
		}
		try {
			$refund = $this->stripe->refunds->create([
				'payment_intent' => $chargeId,
				'metadata' => ['reason' => $reason],
			]);

			//dd($refund);
			return $refund;
		} catch (\Stripe\Exception\ApiErrorException $e) {
			// エラーハンドリング
			return 'S API error: ' . $e->getMessage();
		}
	}

	// 単純にユーザー生成するだけ
	public function createCustomer($name, $email)
	{
		try {
			$customer = $this->stripe->customers->create([
				'name' => $name,
				'email' => $email,
			]);
			//dd($customer);
			return $customer;
		} catch (\Stripe\Exception\ApiErrorException $e) {
			// エラーハンドリング
			return 'S API error: ' . $e->getMessage();
		}
	}

	// 顧客支払いヒモ付け
	public function attachSetupIntents($shopCode, $customer_id, $stripeToken, $ccode)
	{
		try {
			$setupIntent = $this->stripe->setupIntents->create([
				'customer' => $customer_id,
				'payment_method_types' => ['card']
			]);

			$paymentMethod = $this->stripe->paymentMethods->create([
				'type' => 'card',
				'card' => ['token' => $stripeToken],
			]);
			$pid       = $paymentMethod->id;
			$cards = $paymentMethod->card;
			$exp_month = $cards->exp_month;
			$exp_year = $cards->exp_year;
			$last4 = $cards->last4;
			$brand = $cards->brand;

			// SetupIntentにPaymentMethodを関連付ける
			$setupIntent = $this->stripe->setupIntents->confirm(
				$setupIntent->id,
				['payment_method' => $paymentMethod->id]
			);

			// operateLog記録
			common::atLog($shopCode, 'web', '顧客＆カード制作', $setupIntent->id);
			// card情報を保存
			$cardc = common::makeCardCode();
			$card = new card;
			$card->customer_id = $ccode;
			$card->card_id     = $cardc;
			$card->save();

			$pcard = new PlatCard;
			$pcard->customer_id = $ccode;
			$pcard->card_id   = $cardc;
			$pcard->plat_name = 'stripe';
			$pcard->plat_card = $pid;
			$pcard->brand     = $brand;
			$pcard->last4     = $last4;
			$pcard->exp_month = $exp_month;
			$pcard->exp_year  = $exp_year;
			$pcard->save();
			common::atLog($shopCode, 'web', '顧客カード登録', $cardc);
			return $paymentMethod;
		} catch (\Stripe\Exception\ApiErrorException $e) {
			return 'Stripe API error: ' . $e->getMessage();
		}
	}

	// 顧客を削除
	//その顧客の有効なサブスクリプションも直ちにキャンセルされます。
	public function deleteCustomer($customer_id)
	{
		try {
			$del = $this->stripe->customers->delete($customer_id, []);
			common::atLog($shopCode, 'web', '顧客削除', $customer_id);
			return $del;
		} catch (\Stripe\Exception\ApiErrorException $e) {
			// エラーハンドリング
			return 'S API error: ' . $e->getMessage();
		}
	}

	// ユーザー生成とカード割り当て
	public function customerAndCard($name, $email, $source, $meta = null)
	{
		try {
			$customer = $this->stripe->customers->create([
				'name' => $name,
				'email' => $email,
				'source' => $source,
				['metadata' => $meta],
			]);

			//dd($customer);
			return $customer;
		} catch (\Stripe\Exception\ApiErrorException $e) {
			// エラーハンドリング
			return 'S API error: ' . $e->getMessage();
		}
	}

	public function payUser($amount, $customerID, $paymentMethodId)
	{
		try {
			$paymentIntent = $this->stripe->paymentIntents->create([
				'amount' => $amount, // 金額(単位は最小単位、つまり円の場合は円)
				'currency' => 'jpy', // 通貨コード
				'customer' => $customerID, // ユーザーのCustomerID
				'payment_method' => $paymentMethodId, // PaymentMethodのID
				'off_session' => true, // サーバー側で処理するため、trueに設定
				'confirm' => true, // 決済を確定するため、trueに設定
			]);
			return $paymentIntent;
		} catch (\Stripe\Exception\ApiErrorException $e) {
			// エラーハンドリング
			return 'S API error: ' . $e->getMessage();
		}
	}
}
