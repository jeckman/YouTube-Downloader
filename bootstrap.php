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

		$container->set('config', $config);

		// Create Template\Engine
		$template = \YoutubeDownloader\Template\Engine::createFromDirectory(
			__DIR__ . DIRECTORY_SEPARATOR . 'templates'
		);

		$container->set('template', $template);

		// Create Application\ControllerFactory
		$factory = new \YoutubeDownloader\Application\ControllerFactory;

		$container->set('controller_factory', $factory);

		// Create Toolkit
		$container->set('toolkit', new \YoutubeDownloader\Toolkit);

		// Create Cache
		$cache = \YoutubeDownloader\Cache\FileCache::createFromDirectory(
			__DIR__ . DIRECTORY_SEPARATOR . 'cache'
		);

		$container->set('cache', $cache);

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
