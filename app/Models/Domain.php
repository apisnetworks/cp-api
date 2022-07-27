<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Support\Arr;

	/**
	 * App\Models\Server
	 *
	 * @property string      $server_name
	 * @property string|null $ip
	 * @property string      $auth
	 * @property string|null $auth_key
	 * @method static \Illuminate\Database\Eloquent\Builder|Server newModelQuery()
	 * @method static \Illuminate\Database\Eloquent\Builder|Server newQuery()
	 * @method static \Illuminate\Database\Eloquent\Builder|Server query()
	 * @method static \Illuminate\Database\Eloquent\Builder|Server whereAuth($value)
	 * @method static \Illuminate\Database\Eloquent\Builder|Server whereAuthName($value)
	 * @method static \Illuminate\Database\Eloquent\Builder|Server whereIp($value)
	 * @method static \Illuminate\Database\Eloquent\Builder|Server whereServerName($value)
	 * @mixin \Eloquent
	 * @property string $domain
	 * @property string|null $original_domain
	 * @property string|null $admin_email
	 * @property string $status
	 * @property int $addon
	 * @property int $site_id
	 * @property string $di_invoice
	 * @method static \Illuminate\Database\Eloquent\Builder|Domain whereAddon($value)
	 * @method static \Illuminate\Database\Eloquent\Builder|Domain whereAdminEmail($value)
	 * @method static \Illuminate\Database\Eloquent\Builder|Domain whereDiInvoice($value)
	 * @method static \Illuminate\Database\Eloquent\Builder|Domain whereDomain($value)
	 * @method static \Illuminate\Database\Eloquent\Builder|Domain whereOriginalDomain($value)
	 * @method static \Illuminate\Database\Eloquent\Builder|Domain whereSiteId($value)
	 * @method static \Illuminate\Database\Eloquent\Builder|Domain whereStatus($value)
	 */
	class Domain extends Model
	{
		use HasFactory;

		protected $fillable = [];
		public $timestamps = false;
		protected $primaryKey = ['domain', 'server_name'];
		public $incrementing = false;
		protected $keyType = 'string';

		protected const REMAPS = [
			'di_invoice'  => 'invoice',
			'admin_email' => 'email'
		];

		public function getAttributes()
		{
			$attr = parent::getAttributes();
			foreach (self::REMAPS as $old => $new) {
				$attr[$new] = Arr::pull($attr, $old);
			}

			return $attr;
		}

	}
