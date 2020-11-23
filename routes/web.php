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
	$middleware = ['throttle:5,1'];
}

$router->get('/lookup/{domain}', [
	'uses' => 'LookupController@route',
	'middleware' => $middleware
]);

$router->post('/lookup', function (\Illuminate\Http\Request $r) {
	return (new \App\Http\Controllers\LookupController())->route($r, $r->post('domain'));
});

