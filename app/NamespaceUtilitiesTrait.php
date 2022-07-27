<?php declare(strict_types=1);
	namespace App;

	trait NamespaceUtilitiesTrait
	{
		/**
		 * Get base name from fully-qualified namespace
		 *
		 * @param string $class optional class resolution override
		 * @return string
		 */
		public static function getBaseClassName(string $class = null): string
		{
			return (new \ReflectionClass($class ?? static::class))->getShortName();
		}

		/**
		 * Append a namespaced class to an existing namespace
		 *
		 * @param string $class
		 * @return string composite fully-qualified class name
		 */
		public static function appendNamespace(string $class): string
		{
			return self::getNamespace() . '\\' . $class;
		}

		/**
		 * Get namespace from class
		 *
		 * @param string $class optional class resolution override
		 * @return string
		 */
		public static function getNamespace(string $class = null): string
		{
			return (new \ReflectionClass($class ?? static::class))->getNamespaceName();
		}
	}