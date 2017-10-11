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

namespace YoutubeDownloader\Config;

/**
 * config loader from file
 */
class FileLoader implements Loader
{
	private $config;

	/**
	 * Loads the config from a file
	 *
	 * @param string $path
	 * @return array
	 */
	public function __construct($path)
	{
		$path = (string) $path;

		if ( ! file_exists($path) )
		{
			throw new \Exception(sprintf(
				'Config file %s must exist and must be readable.',
				$path
			));
		}

		$config = require($path);

		if ( ! is_array($config) )
		{
			throw new \Exception(sprintf(
				'Config file %s must return an array.',
				$path
			));
		}

		$this->config = $config;
	}

	/**
	 * export the config as array
	 *
	 * @return array
	 */
	public function exportAsArray()
	{
		return $this->config;
	}
}
