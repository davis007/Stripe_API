<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\customer;
use App\Models\OperateLog;
use App\Models\payment;
use MyStripe;

class PaymentController extends Controller
{
	public function payment($shopCode, $amount, $userType, $userId = null)
	{
		$shop = User::where('shop_code', $shopCode)->first();
		return $shop->shop_code;
	}
}
