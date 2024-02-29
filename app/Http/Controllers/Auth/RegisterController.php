<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
	/*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

	use RegistersUsers;

	/**
	 * Where to redirect users after registration.
	 *
	 * @var string
	 */
	protected $redirectTo = '/home';

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('guest');
	}

	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	protected function validator(array $data)
	{
		return Validator::make($data, [
			'name' => ['required', 'string', 'max:255'],
			'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
			'domain' => ['required', 'string', 'max:255', 'unique:users'],
			'password' => ['required', 'string', 'min:8', 'confirmed'],
		]);
	}

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array  $data
	 * @return \App\Models\User
	 */
	protected function create(array $data)
	{
		$attempt = 0;
		$maxAttempts = 10; // 最大試行回数を設定

		do {
			$shop_code = $this->generateRandomString(4); // 4文字のランダムな英数字を生成
			$exists = User::where('shop_code', $shop_code)->exists(); // 生成したshop_codeがユニークかチェック
			$attempt++;
			if ($attempt > $maxAttempts) {
				throw new \Exception('shop_code の生成に失敗しました。');
			}
		} while ($exists);

		try {
			$user = User::create([
				'name' => $data['name'],
				'email' => $data['email'],
				'domain' => $data['domain'],
				'shop_code' => $shop_code,
				'api_key' => Str::uuid()->toString(),
				'password' => Hash::make($data['password']),
			]);

			return $user;
		} catch (\Exception $e) {
			// ここで例外処理を行う
			// ログ記録やエラーレスポンスの送信など
			return redirect()->back()->withErrors(['msg' => $e->getMessage()]);
		}
	}

	private function generateRandomString($length = 4)
	{
		$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[random_int(0, $charactersLength - 1)];
		}
		return $randomString;
	}
}
