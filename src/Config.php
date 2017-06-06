<?php

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
	 * @return StreamMap
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
