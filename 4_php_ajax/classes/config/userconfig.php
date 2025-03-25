<?php

namespace config;

require __DIR__ . '/../../vendor/autoload.php';

class userConfig {

	private static $creds = null;
	// ================================================================================================
	public static function getUsersCreds()
	{
		$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . "/../../../");
		$dotenv->safeLoad();

		self::$creds = [
			"db_host" => $_ENV["db_host"],
			"db_name" => $_ENV["db_name"],
			"db_user" => $_ENV["db_user"],
			"db_pass" => $_ENV["db_pass"]
		];

		return self::$creds;
	}

	// ================================================================================================
}