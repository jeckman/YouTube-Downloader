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
 * Config class with build-in transformator for deprecated configs
 */
class TransformationConfig implements Config
{
	/**
	 * Creates the config from Loaders
	 *
	 * @param Loader $default the default config
	 * @param Loader $custom the custom config
	 * @return Config
	 */
	public static function createFromLoaders(Loader $default, Loader $custom = null)
	{
		$custom_config = [];

		if ( $custom !== null )
		{
			$custom_config = $custom->exportAsArray();
		}

		return new self($default->exportAsArray(), $custom_config);
	}

	private $data = [];

	private $warnings = [];

	/**
	 * Creates a Config from two arrays
	 *
	 * @param array $default
	 * @param array $custom
	 * @return self
	 */
	private function __construct(array $default, array $custom)
	{
		$default = $this->transform($default, $custom);

		$config = $this->merge($default, $custom);

		$this->data = $config;
	}

	/**
	 * Transform deprecated custom configuration into the default configuration
	 *
	 * @param array $default
	 * @param array $custom
	 * @return array The transformed $default array
	 */
	private function transform(array $default, array $custom)
	{
		// @deprecated since 0.6: ThumbnailImageMode => gui.ThumbnailImageMode
		if ( array_key_exists('ThumbnailImageMode', $custom) )
		{
			switch ($custom['ThumbnailImageMode'])
			{
				case 0:
					$value = 'none';
					break;

				case 1:
					$value = 'direct';
					break;

				case 2:
				default:
					$value = 'proxy';
					break;
			}

			$default['gui']['ThumbnailImageMode'] = $value;
			$this->warnings[] = '$config[\'ThumbnailImageMode\'] is deprecated, use $config[\'gui\'][\'ThumbnailImageMode\'] instead';
		}

		// @deprecated since 0.6: VideoLinkMode => gui.VideoLinkMode
		if ( array_key_exists('VideoLinkMode', $custom) )
		{
			$default['gui']['VideoLinkMode'] = $custom['VideoLinkMode'];
			$this->warnings[] = '$config[\'VideoLinkMode\'] is deprecated, use $config[\'gui\'][\'VideoLinkMode\'] instead';
		}

		// @deprecated since 0.6: showBrowserExtensions => gui.showBrowserExtensions
		if ( array_key_exists('showBrowserExtensions', $custom) )
		{
			$default['gui']['showBrowserExtensions'] = $custom['showBrowserExtensions'];
			$this->warnings[] = '$config[\'showBrowserExtensions\'] is deprecated, use $config[\'gui\'][\'showBrowserExtensions\'] instead';
		}

		return $default;
	}

	/**
	 * merge two configuration arrays
	 *
	 * @param array $default
	 * @param array $custom
	 * @return self
	 */
	private function merge(array $default, array $custom)
	{
		foreach ($default as $key => $value)
		{
			if ( ! array_key_exists($key, $custom) )
			{
				continue;
			}

			if ( is_array($value) and ! empty($value) )
			{
				$default[$key] = $this->merge($value, $custom[$key]);

				continue;
			}

			$default[$key] = $custom[$key];
		}

		return $default;
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

		throw new \InvalidArgumentException(sprintf(
			'The key "" don\' exist in this Config',
			(string) $key
		));
	}
}
