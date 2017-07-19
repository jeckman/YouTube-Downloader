<?php

namespace YoutubeDownloader\Application;

use Exception;

/**
 * The main app
 */
class ControllerFactory
{
	private $controller_map = [
		'download' => '\\YoutubeDownloader\\Application\\DownloadController',
		'image' => '\\YoutubeDownloader\\Application\\ImageController',
		'index' => '\\YoutubeDownloader\\Application\\MainController',
		'results' => '\\YoutubeDownloader\\Application\\ResultController',
	];

	/**
	 * Create the Controller
	 *
	 * @param string $route
	 * @param YoutubeDownloader\Application\App $app
	 *
	 * @throws Exception if a route was not found
	 *
	 * @return Controller
	 */
	public function make($route, App $app)
	{
		$route = strval($route);

		if ( ! array_key_exists($route, $this->controller_map) )
		{
			throw new Exception(
				sprintf('No controller was found for route "%s"', $route)
			);
		}

		$controller_name = $this->controller_map[$route];

		return new $controller_name($app);
	}
}
