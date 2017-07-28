<?php

namespace YoutubeDownloader\Application;

use YoutubeDownloader\Container\Container;

/**
 * The main app
 */
class App
{
	/**
	 * @var string
	 */
	private $version = '0.4-dev';

	/**
	 * @var YoutubeDownloader\Container\Container
	 */
	private $container;

	/**
	 * Create the app
	 *
	 * @param YoutubeDownloader\Container\Container $container
	 *
	 * @return void
	 */
	public function __construct(Container $container)
	{
		$this->container = $container;
	}

	/**
	 * Returns the App version
	 *
	 * @return string
	 */
	public function getVersion()
	{
		return $this->version;
	}

	/**
	 * Returns the Controller
	 *
	 * @return Controller
	 */
	public function getContainer()
	{
		return $this->container;
	}

	/**
	 * Runs the app with a specific route
	 *
	 * @param string $route
	 *
	 * @return void
	 */
	public function runWithRoute($route)
	{
		$controller_factory = $this->getContainer()->get('controller_factory');

		$controller = $controller_factory->make($route, $this);

		$controller->execute();
	}
}
