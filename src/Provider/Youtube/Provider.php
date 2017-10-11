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

namespace YoutubeDownloader\Provider\Youtube;

use YoutubeDownloader\Cache\CacheAware;
use YoutubeDownloader\Cache\CacheAwareTrait;
use YoutubeDownloader\Config;
use YoutubeDownloader\Http\HttpClientAware;
use YoutubeDownloader\Http\HttpClientAwareTrait;
use YoutubeDownloader\Logger\LoggerAware;
use YoutubeDownloader\Logger\LoggerAwareTrait;
use YoutubeDownloader\Toolkit;
use YoutubeDownloader\VideoInfo\Provider as ProviderInterface;
use YoutubeDownloader\VideoInfo\InvalidInputException;

/**
 * Provider instance for Youtube
 */
final class Provider implements ProviderInterface, CacheAware, HttpClientAware, LoggerAware
{
	use CacheAwareTrait;
	use HttpClientAwareTrait;
	use LoggerAwareTrait;

	/**
	 * Create this Provider from an options array
	 *
	 * @param Config $config
	 * @param Toolkit $toolkit
	 * @return self
	 */
	public static function createFromOptions(array $options)
	{
		return new self($options);
	}

	/**
	 * Create this Provider from Config and Toolkit
	 *
	 * @deprecated since version 0.6, to be removed in 0.7. Use YoutubeDownloader\Provider\Youtube\Provider::createFromOptions() instead
	 *
	 * @param Config $config
	 * @param Toolkit $toolkit
	 * @return self
	 */
	public static function createFromConfigAndToolkit(Config $config, Toolkit $toolkit)
	{
		@trigger_error(__METHOD__ . ' is deprecated since version 0.6, to be removed in 0.7. Use YoutubeDownloader\Provider\Youtube\Provider::createFromOptions() instead', E_USER_DEPRECATED);

		$options = [];

		if ( $config->get('multipleIPs') === true)
		{
			$options['use_ip'] = $toolkit->getRandomIp($config);
		}

		return static::createFromOptions($options);
	}

	/**
	 * @var YoutubeDownloader\Config
	 */
	private $options = [
		'use_ip' => false,
		'decipher_signature' => false,
	];

	/**
	 * Create this Provider
	 *
	 * @param Config $config
	 * @param Toolkit $toolkit
	 * @return self
	 */
	private function __construct(array $options)
	{
		foreach ($this->options as $option => $value)
		{
			if ( array_key_exists($option, $options) )
			{
				$this->options[$option] = $options[$option];
			}
		}
	}

	/**
	 * Check if this provider can create a VideoInfo from a given input
	 *
	 * This check should be done as fast as possible like run some simple
	 * regex on the input to determine a specific domain or ID pattern.
	 *
	 * There is no guarantee that after `provides()` returns true the
	 * `provide()` will return a `VideoInfo` instance. This method should only
	 * be used as a first indicator if the provider can handle the input for
	 * speed reason. So you should keep in mind that `provide()` can also
	 * throw an exception even if `provides()` returns true
	 *
	 * @param string $input The input like an url or ID
	 * @return boolean true if this provider could handle the input, else false
	 */
	public function provides($input)
	{
		$input = $this->treatUrlIfMobile($input);

		return ($this->validateVideoId($input) !== null);
	}

	/**
	 * Provides a YoutubeDownloader\VideoInfo\VideoInfo instance for the input
	 *
	 * There is no guarantee that `provides()` will be called before this.
	 * This method should also be idempotent, so a repeated call with the same
	 * input should have the same result. This can be returning a VideoInfo or
	 * throwing an Exception.
	 *
	 * An exception can be thrown if the input can't be handled or if there are
	 * other reasons that prevents the creation of a VideoInfo like connection
	 * problems or invalid responses.
	 *
	 * @param string $input The input like an url or ID
	 * @throws YoutubeDownloader\VideoInfo\Exception if the VideoInfo could not be created
	 * @throws YoutubeDownloader\VideoInfo\InvalidInputException if the input can't be handled
	 * @return YoutubeDownloader\VideoInfo\VideoInfo
	 */
	public function provide($input)
	{
		$input = $this->treatUrlIfMobile($input);

		$input = $this->validateVideoId($input);

		if ( $input === null )
		{
			throw new InvalidInputException;
		}

		/* First get the video info page for this video id */
		// $my_video_info = 'http://www.youtube.com/get_video_info?&video_id='. $input;
		// thanks to amit kumar @ bloggertale.com for sharing the fix
		$video_info_url = 'http://www.youtube.com/get_video_info?&video_id=' . $input . '&asv=3&el=detailpage&hl=en_US';

		$request = $this->getHttpClient()->createRequest(
			'GET',
			$video_info_url
		);

		$options = ['curl' => []];

		if ( $this->options['use_ip'] !== false)
		{
			$options['curl'][CURLOPT_INTERFACE] = $this->options['use_ip'];
		}

		$response = $this->getHttpClient()->send($request, $options);

		/* TODO: Check response for status code and Content-Type */
		$video_info = VideoInfo::createFromStringWithOptions(
			$response->getBodyAsString(),
			$this->options
		);

		if ( $video_info instanceOf CacheAware )
		{
			$video_info->setCache($this->getCache());
		}

		if ( $video_info instanceOf HttpClientAware )
		{
			$video_info->setHttpClient($this->getHttpClient());
		}

		if ( $video_info instanceOf LoggerAware )
		{
			$video_info->setLogger($this->getLogger());
		}

		return $video_info;
	}

	/**
	 * @param string $string
	 * @return string
	 */
	private function treatUrlIfMobile($string)
	{
		if (strpos($string, "m."))
		{
			$string = str_replace("m.", "www.", $string);
		}

		return $string;
	}

	/**
	 * Validates a video ID
	 *
	 * This can be an url, embedding url or embedding html code
	 *
	 * @param string $video_id
	 * @return string|null The validated video ID or null, if the video ID is invalid
	 */
	private function validateVideoId($video_id)
	{
		if (strlen($video_id) <= 11)
		{
			return $video_id;
		}

		if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $video_id, $match))
		{
			if (is_array($match) && count($match) > 1)
			{
				return $match[1];
			}
		}

		return null;
	}
}
