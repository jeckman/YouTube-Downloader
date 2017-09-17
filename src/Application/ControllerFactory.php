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
