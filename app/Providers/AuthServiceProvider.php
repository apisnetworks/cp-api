<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
	public const AUTHORIZATION_ENV = 'API_EXTENDED_KEY';
	public const AUTHORIZATION_HEADER = 'X-Auth-Key';

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        $this->app['auth']->viaRequest('api', function (Request $request) {
	        $token = $request->header(self::AUTHORIZATION_HEADER) ?? $request->input('key');
	        return $token && $token === env(self::AUTHORIZATION_ENV) ?: null;
        });

		Gate::define('extended', static function (string $domain) {
			return !Auth::guest();
		});
    }
}
