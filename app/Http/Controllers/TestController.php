<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
	public function payment()
	{
		return view('test.payment');
	}

	public function makeToken(Request $request)
	{
		dd($request, $request->stripeToken);
		$stripe = new \Stripe\StripeClient("env('STRIPE_TEST_SECRET')");
		$stripe->charges->create([
			'amount' => 1099,
			'currency' => 'jpy',
			'source' => $request->stripeToken,
		]);

		dd($request->stripeToken);
	}
}
