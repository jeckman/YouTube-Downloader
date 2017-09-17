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

use YoutubeDownloader\Container\Container;

/**
 * The main app
 */
class App
{
	/**
	 * @var string
	 */
	private $version = '0.6-dev';

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

		$this->getContainer()->get('logger')->debug('App started');
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

		$this->getContainer()->get('logger')->debug('Controller executed. App closed.');
	}
}
