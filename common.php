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

include_once('config.php');

// Show all errors on debug
if ( $config['debug'] )
{
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
}

/*
 * If multipleIPs mode is enabled, select randomly one IP from
 * the config IPs array and put it in $outgoing_ip variable.
 */
if (isset($config['multipleIPs']) && $config['multipleIPs'] === true)
{
	// randomly select an ip from the $config['IPs'] array
	$outgoing_ip = $config['IPs'][mt_rand(0, count($config['IPs']) - 1)];
}

date_default_timezone_set($config['default_timezone']);
