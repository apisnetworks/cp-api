<?php

	namespace App\Console\Commands;

	use App\Providers\AuthServiceProvider;
	use Illuminate\Console\Command;

	class Key extends Command
	{
		/**
		 * The name and signature of the console command.
		 *
		 * @var string
		 */
		protected $signature = 'key';

		/**
		 * The console command description.
		 *
		 * @var string
		 */
		protected $description = 'Display the authorization key';

		/**
		 * Execute the console command.
		 *
		 * @return void
		 */
		public function handle()
		{
			if (!$key = env(AuthServiceProvider::AUTHORIZATION_ENV)) {
				throw new \RuntimeException("Call key:generate first");
			}

			return $this->line('<comment>' . $key . '</comment>');
		}
	}