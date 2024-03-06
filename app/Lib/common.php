<?php

namespace App\Lib;

class common
{
	public static function makeCustomerCode()
	{
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$code = '';
		for ($i = 0; $i < 8; $i++) {
			$code .= $characters[random_int(0, strlen($characters) - 1)];
		}
		return $code;
	}
}
