<?php


abstract class ASN1
{
	/**
	 * Register standalone autoloader.
	 *
	 * This library is compatible with PSR-4 autoloaders and works seamlessly
	 * with composer. Standalone autoloader here is provided in case
	 * one does not use composer or other means to autoload classes from
	 * this namespace.
	 */
	public static function registerAutoloader() {
		spl_autoload_register(function ($cls) {
			static $prefix = __CLASS__;
			// check that the requested class is in the same namespace
			if (substr($cls, 0, strlen($prefix)) !== $prefix) {
				return false;
			}
			$path = __DIR__ . DIRECTORY_SEPARATOR .
				 str_replace("\\", DIRECTORY_SEPARATOR, $cls) . ".php";
			// check that the file exists
			if (!is_file($path)) {
				return false;
			}
			// include file
			if (!(include_once $path)) {
				return false;
			}
			// check that the requested class now exists
			if (!class_exists($cls, false)) {
				return false;
			}
			return true;
		});
	}
}
