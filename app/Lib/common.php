<?php

namespace App\Lib;

use App\Models\customer;

class common
{
	public static function makeCustomerCode()
	{
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$code = '';
		$maxAttempts = 10; // 最大試行回数を設定
		$attempts = 0;

		do {
			$code = '';
			for ($i = 0; $i < 8; $i++) {
				$code .= $characters[random_int(0, strlen($characters) - 1)];
			}

			$customer = Customer::where('customer_id', $code)->first();

			$attempts++;
		} while ($customer && $attempts < $maxAttempts);

		if ($attempts >= $maxAttempts) {
			// 最大試行回数を超えた場合の処理
			// 例えば、エラーを投げるなど
			throw new Exception('Could not generate a unique customer ID after ' . $maxAttempts . ' attempts.');
		}

		return $code;
	}
}
