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
		try {
			$stripe = new \Stripe\StripeClient('sk_test_51OJgufJX4jQMJo2W6Q6Y4W21WwkaSz6H9tEd2T4nWngI2Zf3owsCujDQeAuyc17DlMQFRfaxspR4CKpd3nvRsDtE00WiJEwanE');
			$token = $stripe->tokens->create([
				'card' => [
					'number' => $request->input('number'),
					'exp_month' => $request->input('exp_month'),
					'exp_year' => $request->input('exp_year'),
					'cvc' =>  $request->input('cvc'),
				],
			]);
			dd($token);
			return $token;
		} catch (\Stripe\Exception\CardException $e) {
			$declineCode = $e->getDeclineCode();
			switch ($declineCode) {
				case 'generic_decline':
					$mess = '支払いが拒否されました。';
					break;
				case 'insufficient_funds':
					$mess = '残高不足により支払い拒否されました。';
					break;
				case 'lost_card':
					$mess = '紛失されたカードにより拒否されました。';
					break;
				case 'stolen_card':
					$mess = '盗難されたカードにより拒否されました。';
					break;
				case 'expired_card':
					$mess = '期限切れにより拒否されました。';
					break;
				case 'incorrect_cvc':
					$mess = 'セキュリティーコードが違います。';
					break;
				case 'processing_error':
					$mess = '処理エラーにより拒否されました。';
					break;
				case 'incorrect_number':
					$mess = '有効なカード番号ではありません。';
					break;
				default:
					$mess = '支払いが拒否されました。';
					break;
			}
			echo $mess;
			//return response()->json(['error' => $e->getMessage()], 400);
		}
	}
}
