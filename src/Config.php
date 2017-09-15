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

namespace YoutubeDownloader;

/**
 * Config class
 */
class Config
{
	/**
	 * Creates the config from files
	 *
	 * @param string $default full path to default config php file
	 * @param string $custom full path to custom config php file
	 * @return Config
	 */
	public static function createFromFiles($default, $custom = null)
	{
		$default_config = require($default);
		$custom_config = [];

		if ( file_exists($custom) )
		{
			$custom_config = require($custom);
		}

		$config = array_replace_recursive($default_config, $custom_config);

		return new self($config);
	}

	private $data = [];

	private $allowed_keys = [
		'enable_youtube_decipher_signature',
		'ThumbnailImageMode',
		'VideoLinkMode',
		'MP3Enable',
		'MP3ConvertVideo',
		'MP3Quality',
		'MP3TempDir',
		'ffmpegPath',
		'aria2Path',
		'showBrowserExtensions',
		'multipleIPs',
		'IPs',
		'default_timezone',
		'debug',
	];

	/**
	 * Creates a Config from an array
	 *
	 * @param array $config
	 * @return self
	 */
	private function __construct(array $config)
	{
		foreach ($this->allowed_keys as $key)
		{
			if ( array_key_exists($key, $config) )
			{
				$this->data[$key] = $config[$key];
			}
		}
	}

	/**
	 * Get a config value
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function get($key)
	{
		if ( array_key_exists($key, $this->data) )
		{
			return $this->data[$key];
		}

		throw new \InvalidArgumentException;
	}
}
