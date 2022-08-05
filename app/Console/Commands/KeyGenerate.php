<?php

	namespace App\Console\Commands;

	use App\Providers\AuthServiceProvider;
	use Illuminate\Console\Command;
	use Illuminate\Console\ConfirmableTrait;
	use Illuminate\Support\Env;
	use Illuminate\Support\Str;

	class KeyGenerate extends Command
	{
		use ConfirmableTrait;

		/**
		 * The name and signature of the console command.
		 *
		 * @var string
		 */
		protected $signature = 'key:generate
                    {--show : Display the key instead of modifying files}
                    {--force : Force the operation to run when in production rolling authorization keys}';

		/**
		 * The console command description.
		 *
		 * @var string
		 */
		protected $description = 'Set the authorization key';

		/**
		 * Execute the console command.
		 *
		 * @return void
		 */
		public function handle()
		{
			$key = $this->generateRandomKey();

			if ($this->option('show')) {
				return $this->line('<comment>' . $key . '</comment>');
			}

			// Next, we will replace the application key in the environment file so it is
			// automatically setup for this developer. This key gets generated using a
			// secure random byte generator and is later base64 encoded for storage.
			if (!$this->setKeyInEnvironmentFile($key)) {
				return;
			}

			$this->info("New application key: " . $key);
		}

		/**
		 * Generate a random key for the application.
		 *
		 * @return string
		 */
		protected function generateRandomKey()
		{
			return Str::random();
		}

		/**
		 * Set the application key in the environment file.
		 *
		 * @param string $key
		 * @return bool
		 */
		protected function setKeyInEnvironmentFile($key)
		{
			$currentKey = Env::get(AuthServiceProvider::AUTHORIZATION_ENV);

			if ($currentKey && !$this->confirmToProceed()) {
				return false;
			}

			$this->writeNewEnvironmentFileWith($key);

			return true;
		}

		/**
		 * Write a new environment file with the given key.
		 *
		 * @param string $key
		 * @return void
		 */
		protected function writeNewEnvironmentFileWith($key)
		{
			$envName = [$this->laravel->basePath(), '.env'];
			$env = new \Laravel\Lumen\Bootstrap\LoadEnvironmentVariables(...$envName);

			$envData = file_get_contents(implode('/', $envName));
			$replacement = AuthServiceProvider::AUTHORIZATION_ENV . '=' . $key;

			if (null === env(AuthServiceProvider::AUTHORIZATION_ENV)) {
				$envData = rtrim($envData) . "\n" . $replacement;
			} else {
				$envData = preg_replace($this->keyReplacementPattern(), $replacement, $envData);
			}

			file_put_contents(implode('/', $envName), $envData);

			$env->bootstrap();
			if (env(AuthServiceProvider::AUTHORIZATION_ENV) !== $key) {
				throw new \RuntimeException("Key update failed");
			}
		}


		/**
		 * Get a regex pattern that will match env APP_KEY with any random key.
		 *
		 * @return string
		 */
		protected function keyReplacementPattern()
		{
			$escaped = preg_quote('=' . env(AuthServiceProvider::AUTHORIZATION_ENV), '/');

			return "/^" . AuthServiceProvider::AUTHORIZATION_ENV . "{$escaped}/m";
		}

	}