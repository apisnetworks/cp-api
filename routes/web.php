<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$middleware = [];
if (!\in_array(app()->environment(), ['local', 'testing'], true)) {
	$middleware = array_merge($middleware, ['throttle:5,1']);
}

// public routes
$router->group($middleware, function () use ($router) {
	// legacy route
	$router->get('/lookup/{domain}', [
		'uses'       => 'LookupController@get'
	]);

	// legacy route
	$router->post('/lookup', function (\Illuminate\Http\Request $r) {
		return (new \App\Http\Controllers\LookupController())->get($r, $r->post('domain'));
	});

	$router->get('/domains/lookup/{domain}', ['uses' => 'LookupController@get']);
});

// privileged routes
$router->group(['middleware' => $middleware + [9999 => 'auth']], function () use ($router) {
	$router->get('version', function () {
		return API_VERSION;
	});

	$router->group(['prefix' => 'domains'], function() use ($router) {
		$router->get('server/{domain}', 'LookupController@serverFinder');
		$router->get('parent/{domain}', 'LookupController@parent');
		$router->get('all', 'LookupController@all');
	});
});