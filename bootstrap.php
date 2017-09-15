<?php

/*
 * PHP script for downloading videos from youtube
 * Copyright (C) 2012-2017  John Eckman
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, see <http://www.gnu.org/licenses/>.
 */

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
 * Closure to create a container class
 */
$container = call_user_func_array(
	function($custom = 'custom')
	{
		// Create Container
		$container = new \YoutubeDownloader\Container\SimpleContainer;

		// Create Config
		$ds = DIRECTORY_SEPARATOR;

		$config_dir = realpath(__DIR__) . $ds . 'config' . $ds;

		$config = \YoutubeDownloader\Config::createFromFiles(
			$config_dir . 'default.php',
			$config_dir . $custom . '.php'
		);

		$service_provider = new \YoutubeDownloader\ServiceProvider($config);
		$service_provider->register($container);

		return $container;
	},
	[getenv('CONFIG_ENV') ?: 'custom']
);

// Show all errors on debug
if ( $container->get('config')->get('debug') === true )
{
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
}

date_default_timezone_set($container->get('config')->get('default_timezone'));

return new \YoutubeDownloader\Application\App($container);
