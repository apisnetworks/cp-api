<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use App\NamespaceUtilitiesTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class LookupController extends Controller
{
	use NamespaceUtilitiesTrait;

	/**
	 * Lookup domain metadata
	 *
	 * @param Request $r
	 * @param string  $domain
	 * @return array
	 */
    public function get(Request $r, string $domain)
	{
		try {
			$record = Domain::whereDomain($domain)->orderBy('status')->firstOrFail();
			$data = [
				'server'  => $record->server_name,
			];
			if ($r->input("full")) {
				Gate::denyIf(static function () use ($domain) {
					return !Gate::allows('extended', $domain);
				});

				$data += $record->getAttributes();
			}
		} catch (ModelNotFoundException $e) {
			$data = __("Account not found on server");
		}
		return ['status' => isset($record), 'data' => $data];
	}

	/**
	 * Locate servers on which a domain exists
	 *
	 * @param Request $request
	 * @param string  $domain
	 * @return array
	 */
	public function serverFinder(Request $request, string $domain): array
	{
		try {
			/** @var Collection $record */
			$records = Domain::whereDomain($domain)->orderBy('status')->get()->all();
			$data = array_map(static function ($record) use ($request) {
				if (!$request->input('full')) {
					return $record->server_name;
				}
				$record['server'] = $record->server_name;

				return $record;
			}, $records);
		} catch (ModelNotFoundException $e) {
			abort(500, __("Account not found on server"));
		}

		return $data;
	}

	/**
	 * Ascertain domain parentage
	 *
	 * @param Request $r
	 * @param string  $domain
	 * @return string
	 */
	public function parent(Request $r, string $domain): string
	{
		try {
			$tableName = Str::plural(strtolower(self::getBaseClassName(Domain::class)));
			/** @var Collection $record */
			$record = Domain::query()->selectRaw("COALESCE(p.domain, $tableName.domain) AS domain")->where("$tableName.domain", '=', $domain)
				->leftJoin("$tableName as p", static function (\Illuminate\Database\Query\Builder $join) use ($tableName) {
					$join->on("$tableName.di_invoice", "=", "p.di_invoice");
					$join->on("$tableName.server_name", '=', "p.server_name");
					$join->on("p.addon", '=', DB::raw(0));
				})->orderBy("$tableName.status")->limit(1)->firstOrFail();
		} catch (ModelNotFoundException $e) {
			abort(500, __("Account not found on server"));
		}

		return $record->domain;
	}

	/**
	 * Fetch all managed domains
	 *
	 * @param Request $r
	 * @return \Illuminate\Support\Collection
	 */
	public function all(Request $r): Collection
	{
		return Domain::all('domain')->pluck('domain');
	}

}
