<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\customer;
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

	public function customers()
	{
		$user = User::find(Auth::user()->id)->first();
		$cust = customer::where(['shopCode' => $user->shop_code])->paginate(20);

		return view('members.customers', compact('user', 'cust'));
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
		return view('members.sales', compact('user'));
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
