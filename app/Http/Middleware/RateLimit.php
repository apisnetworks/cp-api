<?php

	namespace App\Http\Middleware;

	class RateLimit  extends \Illuminate\Routing\Middleware\ThrottleRequests
	{
		protected function resolveRequestSignature($request)
		{
			if (app()->environment() === 'local') {
				return microtime(true);
			}

			return sha1(implode('|', [
					$request->method(),
					$request->root(),
					$request->path(),
					$request->ip(),
					$request->query('access_token')
				]
			));

			return $request->fingerprint();
		}
	}