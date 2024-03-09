<?php

namespace App\Lib;

use Illuminate\Support\Facades\DB;
use App\Models\card;
use App\Models\User;
use App\Models\customer;
use App\Models\OperateLog;
use App\Models\payment;
use App\Models\PlatCard;
use App\Models\PlatCustomer;

class common
{
	public static function addCustomerDB($req, $spc, $ccode, $resultId, $plat, $where)
	{
		try {
			DB::beginTransaction();
			$cus = new customer;
			$cus->shopCode = $spc;
			$cus->customer_id = $ccode;
			$cus->name = $req->name;
			$cus->email = $req->mailaddress ? $req->mailaddress : $req->email;
			$cus->save();

			$plc = new PlatCustomer;
			$plc->customer_id = $ccode;
			$plc->plat_name = $plat;
			$plc->plat_id = $resultId;
			$plc->save();

			// operate log
			$log = new OperateLog;
			$log->shop_code = $spc;
			$log->type = $where;
			$log->operate = '顧客作成';
			$log->memo = $resultId;
			$log->save();

			DB::commit();
		} catch (\Exception $e) {
			DB::rollBack();
			// エラー処理を追加する
			// 例: Log::error($e->getMessage());
			throw $e;
		}
	}

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

		return 'cus_' . $code;
	}

	public static function makeCardCode()
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

		return 'car_' . $code;
	}
}
