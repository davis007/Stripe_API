<?php

namespace App\Lib;

use \Stripe\StripeClient;
use Stripe\PaymentIntent;
use Stripe\PaymentMethod;
use App\Models\card;
use App\Models\User;
use App\Models\customer;
use App\Models\OperateLog;
use App\Models\payment;

class StripeFanc
{
	protected $stripe;

	public function __construct()
	{
		$this->stripe = new StripeClient(env('STRIPE_TEST_SECRET'));
	}

	public function cardToken($token)
	{
		$payCard = $this->stripe->paymentMethods->create([
			'type' => 'card',
			'card' => ['token' => $token],
		]);

		return $payCard;
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
			return 'Stripe API error: ' . $e->getMessage();
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
		$refund = $this->stripe->refunds->create([
			'payment_intent' => $chargeId,
			'metadata' => ['reason' => $reason],
		]);

		//dd($refund);
		return $refund;
	}

	// 単純にユーザー生成するだけ
	public function createCustomer($name, $email, $meta, $source = null)
	{
		if ($source) {
			$customer = $this->stripe->customers->create([
				'name' => $name,
				'email' => $email,
				'source' => $source,
				['metadata' => $meta],
			]);
		} else {
			$customer = $this->stripe->customers->create([
				'name' => $name,
				'email' => $email,
				['metadata' => $meta],
			]);
		}


		//dd($customer);
		return $customer;
	}

	// 顧客を削除
	//その顧客の有効なサブスクリプションも直ちにキャンセルされます。
	public function deleteCustomer($customer_id)
	{
		$del = $this->stripe->customers->delete($customer_id, []);
		return $del;
	}

	// ユーザー生成とカード割り当て
	public function customerAndCard($name, $email, $source, $meta = null)
	{
		$customer = $this->stripe->customers->create([
			'name' => $name,
			'email' => $email,
			'source' => $source,
			['metadata' => $meta],
		]);

		//dd($customer);
		return $customer;
	}
}
