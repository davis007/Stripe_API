<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\customer;

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
