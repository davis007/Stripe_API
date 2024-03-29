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
		} catch (\Exception $e) {
			// エラー処理を追加する
			// 例: Log::error($e->getMessage());
			throw $e;
		}
	}

	public static function cusName($cus_id)
	{

		$cus = PlatCustomer::where('plat_id', $cus_id)->first();
		if ($cus != null) {
			$ccus = customer::where('customer_id', $cus->customer_id)->first()->name;
			return $ccus;
		} else {
			// 'cus_'から始まらない場合は、'cus_'を付加して返す
			return 'guest決済';
		}
	}

	public static function atLog($shopcode, $type, $ope, $memo = null)
	{
		//shop_code
		//type
		//operate
		//memo
		try {
			$log = new OperateLog;
			$log->shop_code = $shopcode;
			$log->type = $type;
			$log->operate = $ope;
			$log->memo = $memo ? $memo : 'null';
			$log->save();
		} catch (\Exception $e) {
			return $e->getError();
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
