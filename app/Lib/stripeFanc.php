<?php

namespace App\Lib;

use \Stripe\StripeClient;
use Stripe\PaymentIntent;

class StripeFanc
{
	protected $stripe;

	public function __construct()
	{
		$this->stripe = new StripeClient(env('STRIPE_TEST_SECRET'));
	}

	/**
	 * 即時決済処理
	 * $token: STRIPEから返却されたCardトークン tok_から始まるID
	 * $amount: 決済額
	 **/
	public function charge($token, $amount)
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
				'metadata' => ['order_id' => '6735'],
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
	public function createCustomer($name, $email, $meta)
	{
		$customer = $this->stripe->customers->create([
			'name' => $name,
			'email' => $email,
			['metadata' => $meta],
		]);

		//dd($customer);
		return $customer;
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
