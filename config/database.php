<?php

return [

	/*
	|--------------------------------------------------------------------------
	| PDO Fetch Style
	|--------------------------------------------------------------------------
	|
	| By default, database results will be returned as instances of the PHP
	| stdClass object; however, you may desire to retrieve records in an
	| array format for simplicity. Here you can tweak the fetch style.
	|
	*/

	'fetch' => PDO::FETCH_CLASS,

	/*
	|--------------------------------------------------------------------------
	| Default Database Connection Name
	|--------------------------------------------------------------------------
	|
	| Here you may specify which of the database connections below you wish
	| to use as your default connection for all database work. Of course
	| you may use many connections at once using the Database library.
	|
	*/

	'default' => 'sqlsrv',

	/*
	|--------------------------------------------------------------------------
	| Database Connections
	|--------------------------------------------------------------------------
	|
	| Here are each of the database connections setup for your application.
	| Of course, examples of configuring each database platform that is
	| supported by Laravel is shown below to make development simple.
	|
	|
	| All database work in Laravel is done through the PHP PDO facilities
	| so make sure you have the driver for your particular database of
	| choice installed on your machine before you begin development.
	|
	*/

	'connections' => [

		'sqlite' => [
			'driver'   => 'sqlite',
			'database' => storage_path().'/database.sqlite',
			'prefix'   => '',
		],

		'mysql' => [
			'driver'    => 'mysql',
			'host'      => env('DB_HOST', 'localhost'),
			'database'  => env('DB_DATABASE', 'forge'),
			'username'  => env('DB_USERNAME', 'forge'),
			'password'  => env('DB_PASSWORD', ''),
			'charset'   => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix'    => '',
			'strict'    => false,
		],

		'pgsql' => [
			'driver'   => 'pgsql',
			'host'     => env('DB_HOST', 'localhost'),
			'database' => env('DB_DATABASE', 'forge'),
			'username' => env('DB_USERNAME', 'forge'),
			'password' => env('DB_PASSWORD', ''),
			'charset'  => 'utf8',
			'prefix'   => '',
			'schema'   => 'public',
		],

		'sqlsrv' => [
			'driver'   => 'sqlsrv',
			'host'     => env('DB_HOST', 'localhost'),
			'database' => env('DB_DATABASE', 'forge'),
			'username' => env('DB_USERNAME', 'forge'),
			'password' => env('DB_PASSWORD', ''),
			'prefix'   => '',
			'pooling' => false
			


		],

		'sqlsrv2' => [
			'driver'   => 'sqlsrv',
			'host'     => env('DB_HOST2', 'localhost'),
			'database' => env('DB_DATABASE2', 'forge'),
			'username' => env('DB_USERNAME2', 'forge'),
			'password' => env('DB_PASSWORD2', ''),
			'prefix'   => '',
		],

		'sqlsrv3' => [
			'driver'   => 'sqlsrv',
			'host'     => env('DB_HOST3', 'localhost'),
			'database' => env('DB_DATABASE3', 'forge'),
			'username' => env('DB_USERNAME3', 'forge'),
			'password' => env('DB_PASSWORD3', ''),
			'prefix'   => '',
		],

		'sqlsrv4' => [
			'driver'   => 'sqlsrv',
			'host'     => env('DB_HOST4', 'localhost'),
			'database' => env('DB_DATABASE4', 'forge'),
			'username' => env('DB_USERNAME4', 'forge'),
			'password' => env('DB_PASSWORD4', ''),
			'prefix'   => '',
		],

		'sqlsrv5' => [
			'driver'   => 'sqlsrv',
			'host'     => env('DB_HOST5', 'localhost'),
			'database' => env('DB_DATABASE5', 'forge'),
			'username' => env('DB_USERNAME5', 'forge'),
			'password' => env('DB_PASSWORD5', ''),
			'prefix'   => '',
		],

		'sqlsrv6' => [
			'driver'   => 'sqlsrv',
			'host'     => env('DB_HOST6', 'localhost'),
			'database' => env('DB_DATABASE6', 'forge'),
			'username' => env('DB_USERNAME6', 'forge'),
			'password' => env('DB_PASSWORD6', ''),
			'prefix'   => '',
		],

		'sqlsrv7' => [
			'driver'   => 'sqlsrv',
			'host'     => env('DB_HOST7', 'localhost'),
			'database' => env('DB_DATABASE7', 'forge'),
			'username' => env('DB_USERNAME7', 'forge'),
			'password' => env('DB_PASSWORD7', ''),
			'prefix'   => '',
		],

		'sqlsrv8' => [
			'driver'   => 'sqlsrv',
			'host'     => env('DB_HOST8', 'localhost'),
			'database' => env('DB_DATABASE8', 'forge'),
			'username' => env('DB_USERNAME8', 'forge'),
			'password' => env('DB_PASSWORD8', ''),
			'prefix'   => '',
		],


	],

	/*
	|--------------------------------------------------------------------------
	| Migration Repository Table
	|--------------------------------------------------------------------------
	|
	| This table keeps track of all the migrations that have already run for
	| your application. Using this information, we can determine which of
	| the migrations on disk haven't actually been run in the database.
	|
	*/

	'migrations' => 'migrations',

	/*
	|--------------------------------------------------------------------------
	| Redis Databases
	|--------------------------------------------------------------------------
	|
	| Redis is an open source, fast, and advanced key-value store that also
	| provides a richer set of commands than a typical key-value systems
	| such as APC or Memcached. Laravel makes it easy to dig right in.
	|
	*/

	'redis' => [

		'cluster' => false,

		'default' => [
			'host'     => '127.0.0.1',
			'port'     => 6379,
			'database' => 0,
		],

	],

];
