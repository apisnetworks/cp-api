<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class LookupController extends Controller
{
    public function route(Request $r, string $domain)
	{
		$data = null;
		try {
			$record = Domain::whereDomain($domain)->firstOrFail();
			$data = $record->server_name;
		} catch (ModelNotFoundException $e) {
			$data = __("Account not found on server");
		}
		return ['status' => isset($record), 'data' => $data];
	}

}
