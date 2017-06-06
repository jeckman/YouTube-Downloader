<?php

/**
 * PSR-4 autoloader
 *
 * @param string $class The fully-qualified class name.
 * @return void
 */
spl_autoload_register(function ($class)
{
	// project-specific namespace prefix
	$prefix = 'YoutubeDownloader\\';

	// base directory for the namespace prefix
	$base_dir = __DIR__ . '/src/';

	// does the class use the namespace prefix?
	$len = strlen($prefix);

	if (strncmp($prefix, $class, $len) !== 0)
	{
		// no, move to the next registered autoloader
		return;
	}

	// get the relative class name
	$relative_class = substr($class, $len);

	// replace the namespace prefix with the base directory, replace namespace
	// separators with directory separators in the relative class name, append
	// with .php
	$file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

	// if the file exists, require it
	if (file_exists($file))
	{
		require $file;
	}
});

/**
 * Closure to create a config class
 */
$config = call_user_func_array(
	function($custom = 'custom')
	{
		$ds = DIRECTORY_SEPARATOR;

		$config_dir = realpath(__DIR__) . $ds . 'config' . $ds;

		return \YoutubeDownloader\Config::createFromFiles(
			$config_dir . 'default.php',
			$config_dir . $custom . '.php'
		);
	},
	[getenv('CONFIG_ENV') ?: 'custom']
);

// Show all errors on debug
if ( $config->get('debug') === true )
{
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
}

date_default_timezone_set($config->get('default_timezone'));
