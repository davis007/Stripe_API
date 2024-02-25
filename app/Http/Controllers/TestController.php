<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MyStripe;

class TestController extends Controller
{
	public function payment()
	{
		return view('test.payment');
	}

	public function makeToken(Request $request)
	{
		$stripeFanc = new \App\Lib\StripeFanc();
		$result = $stripeFanc->charge($request->stripeToken, 1200);
		return $result;
	}

	public function refund(Request $req)
	{
		if ($req->PaymentID) {
			$stripeFanc = new \App\Lib\StripeFanc();
			$result = $stripeFanc->refund($req->PaymentID);
			return $result;
		}
	}
}
