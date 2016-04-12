<?php


class ASN1
{
	/**
	 * Register standalone autoloader
	 */
	public static function registerAutoloader() {
		spl_autoload_register(function ($cls) {
			static $prefix = __CLASS__;
			// check that requested class is in the same namespace
			if (substr($cls, 0, strlen($prefix)) !== $prefix) {
				return false;
			}
			$path = __DIR__ . DIRECTORY_SEPARATOR .
				 str_replace("\\", DIRECTORY_SEPARATOR, $cls) . ".php";
			// check that file exists
			if (!is_file($path)) {
				return false;
			}
			// include file
			if (!(include_once $path)) {
				return false;
			}
			// check that requested class exists after include
			if (!class_exists($cls, false)) {
				return false;
			}
			return true;
		});
	}
}
