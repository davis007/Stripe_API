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
		dd($result);
		return $stripe;
	}
}
