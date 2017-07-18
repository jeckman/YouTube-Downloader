<?php

namespace YoutubeDownloader\Application;

/**
 * The controller interface
 */
interface Controller
{
	/**
	 * Create the controller
	 *
	 * @param YoutubeDownloader\Container\App $app
	 *
	 * @return void
	 */
	public function __construct(App $app);

	/**
	 * Excute the Controller
	 *
	 * @param string $route
	 * @param YoutubeDownloader\Application\App $app
	 *
	 * @return void
	 */
	public function execute();
}
