<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
	public function hello()
	{
		return response()->json(['message' => 'Hello!']);
	}
	// カード登録
	// カード決済
	// カード与信
	// 顧客登録
	// カード登録＆顧客新規登録
	// 決済取消し(返金)
}
