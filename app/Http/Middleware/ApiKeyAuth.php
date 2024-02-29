<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyAuth
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
	 */
	public function handle($request, Closure $next)
	{
		$apiKey = $request->header('X-API-KEY');
		if (!$apiKey) {
			return response()->json(['error' => 'API Key is missing'], 401);
		}

		$user = User::where('api_key', $apiKey)->first();
		if (!$user) {
			return response()->json(['error' => 'Invalid API Key'], 401);
		}

		return $next($request);
	}
}
