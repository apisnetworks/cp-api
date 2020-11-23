<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;

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
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereAuthKey($value)
 */
	class Server extends Model
	{
		use HasFactory;

		protected $fillable = [];
		public $timestamps = false;
		protected $primaryKey = 'server_name';
		public $incrementing = false;
		protected $keyType = 'string';

		public function getAuthKeyAttribute(): ?string
		{
			return null;
		}
	}
