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

class HomeController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Contracts\Support\Renderable
	 */
	public function index()
	{
		$user = User::where(['id' => Auth::user()->id])->first();

		return view('home', compact('user'));
	}

	public function basics()
	{
		$user = User::find(Auth::user()->id)->first();

		return view('members.basics', compact('user'));
	}

	public function apiLogs()
	{
		$user = Auth::user();
		$logs = OperateLog::where('shop_code', $user->shop_code)->paginate(20);

		return view('members.logs', compact('logs'));
	}

	public function customers()
	{
		$user = User::find(Auth::user()->id)->first();
		$cust = customer::where(['shopCode' => $user->shop_code])->paginate(20);

		return view('members.customers', compact('user', 'cust'));
	}

	public function addCustomer(Request $req)
	{
		$validatedData = $req->validate([
			'name' => 'required', // nameは必須
			'mailaddress' => 'required|email', // emailは必須であり、有効なメールアドレス形式であること
		]);

		try {
			$stripeFanc = new \App\Lib\StripeFanc();
			$result = $stripeFanc->createCustomer(
				$req->name,
				$req->mailaddress,
				['type' => 'WebCreate'],
			);

			$cus = new customer;
			$cus->shopCode = Auth::user()->shop_code;
			$cus->name = $req->name;
			$cus->email = $req->mailaddress;
			$cus->customer_id = $result->id;
			$cus->save();

			// operate log
			$log = new OperateLog;
			$log->shop_code = Auth::user()->shop_code;
			$log->type = 'web';
			$log->operate = '顧客作成';
			$log->memo = $result->id;
			$log->save();
			return redirect()->back()->with('msg', '顧客を制作しました。');
		} catch (\Stripe\Exception\ApiErrorException $e) {
			// Stripe APIのエラーを捕捉
			return redirect()->back()->with('Stripeエラー: ', $e->getMessage());
		}
	}

	public function deleteCustomer($customer_id)
	{
		$user = User::find(Auth::user()->id)->first();

		// stripe customer delete 完了後にこっちのDBからも削除する
		$stripeFanc = new \App\Lib\StripeFanc();
		$del = $stripeFanc->deleteCustomer($customer_id);
		if ($del->deleted) {
			$cusDel = customer::where([
				'shopCode' => $user->shop_code,
				'customer_id' => $customer_id
			])->delete();

			return redirect()->back()->with('msg', '顧客情報が削除されました。サブスクもクレジットカード情報も削除されています。');
		} else {

			return redirect()->back()->with('msg', '顧客情報の削除に失敗しました。');
		}
	}

	public function sales()
	{
		$user = User::find(Auth::user()->id)->first();
		$sales = payment::where('shop_id', $user->shop_code)->paginate(20);

		return view('members.sales', compact('user', 'sales'));
	}

	public function settings()
	{
		$user = User::find(Auth::user()->id)->first();

		return view('members.settings', compact('user'));
	}

	public function regenerateApiKey(Request $req)
	{
		$user = User::find(Auth::user()->id)->first();
		$user->api_key = Str::uuid()->toString();
		$user->save();

		return redirect()->back()->with('msg', 'APIキーを再生成しました。以前のKeyは利用出来ません。');
	}
}
