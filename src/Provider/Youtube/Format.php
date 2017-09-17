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
use YoutubeDownloader\Http\HttpClientAware;
use YoutubeDownloader\Http\HttpClientAwareTrait;
use YoutubeDownloader\Logger\LoggerAware;
use YoutubeDownloader\Logger\LoggerAwareTrait;
use YoutubeDownloader\VideoInfo\Format as FormatInterface;

/**
 * a video format
 */
class Format implements FormatInterface, CacheAware, HttpClientAware, LoggerAware
{
	use CacheAwareTrait;
	use HttpClientAwareTrait;
	use LoggerAwareTrait;

	/**
	 * Creates a Stream from array
	 *
	 * Possible array key could be
	 * - "type"
	 * - "itag"
	 * - "s"
	 * - "url"
	 * - "quality"
	 *
	 * @param VideoInfo $video_info
	 * @param array $stream_info
	 * @param array $config
	 * @return Format
	 */
	public static function createFromArray(
		VideoInfo $video_info,
		array $stream_info,
		array $config
	)
	{
		return new self($video_info, $stream_info, $config);
	}

	private $video_info;

	private $config = [];

	private $data = [];

	private $data_parsed = false;

	private $raw_data = [];

	/**
	 * Creates a Format from a format data array
	 *
	 * @param VideoInfo $video_info
	 * @param array $data
	 * @return self
	 */
	private function __construct(VideoInfo $video_info, array $data, array $config)
	{
		$this->video_info = $video_info;

		$this->config = [
			'decipher_signature' => (isset($config['decipher_signature'])) ? (bool) $config['decipher_signature'] : false,
		];

		$allowed_keys = [
			'itag',
			's',
			'url',
			'quality',
		];

		foreach ($allowed_keys as $key)
		{
			if ( isset($data[$key]) )
			{
				$this->data[$key] = $data[$key];
			}
			else
			{
				$this->data[$key] = null;
			}
		}

		$this->raw_data = $data;
	}

	/**
	 * Parses the url
	 *
	 * @return void
	 */
	private function parseUrl()
	{
		if ( $this->data_parsed === true )
		{
			return;
		}

		parse_str(urldecode($this->data['url']), $url_info);

		if (isset($this->raw_data['bitrate']))
		{
			$quality = isset($this->raw_data['quality_label']) ? $this->raw_data['quality_label'] : round($this->raw_data['bitrate']/1000).'k';
		}
		else
		{
			$quality =  isset($this->raw_data['quality']) ? $this->raw_data['quality'] : '';
		}

		$this->data['quality'] = $quality;

		$signature = '';

		// The video signature need to be deciphered
		if ( isset($this->raw_data['s']) and $this->config['decipher_signature'] )
		{
			// TODO: Remove signature decipher from Format
			$player_info = SignatureDecipher::getPlayerInfoByVideoId($this->getVideoId());

			$playerID = $player_info[0];
			$playerURL = $player_info[1];

			$cache_key = 'playerscript_' . $playerID;

			$decipherScript = $this->getCache()->get($cache_key, null);

			if ( $decipherScript === null )
			{
				$decipherScript = SignatureDecipher::downloadRawPlayerScript($playerURL);

				$this->getCache()->set($cache_key, $decipherScript, 3600*24);
			}

			$sig = SignatureDecipher::decipherSignatureWithRawPlayerScript(
				$decipherScript,
				$this->raw_data['s'],
				$this->getLogger()
			);

			if ( strpos($this->raw_data['url'], 'ratebypass=') === false )
			{
				$this->raw_data['url'] .= '&ratebypass=yes';
			}

			$signature = '&signature='.$sig;
		}

		$this->data['url'] = $this->raw_data['url'].$signature;

		$type = explode(';', $this->raw_data['type']);
		$this->data['type'] = $type[0];

		$this->data['expires'] = isset($url_info['expire']) ? date("G:i:s T", $url_info['expire']) : '';
		$this->data['ipbits'] = isset($url_info['ipbits']) ? $url_info['ipbits'] : '';
		$this->data['ip'] = isset($url_info['ip']) ? $url_info['ip'] : '';

		$this->data_parsed = true;
	}

	/**
	 * Get the video_id
	 *
	 * @return string
	 */
	public function getVideoId()
	{
		return $this->video_info->getVideoId();
	}

	/**
	 * Get the url
	 *
	 * @return string
	 */
	public function getUrl()
	{
		$this->parseUrl();

		return $this->data['url'];
	}

	/**
	 * Get the itag
	 *
	 * @return string
	 */
	public function getItag()
	{
		$this->parseUrl();

		return $this->data['itag'];
	}

	/**
	 * Get the quality
	 *
	 * @return string
	 */
	public function getQuality()
	{
		$this->parseUrl();

		return $this->data['quality'];
	}

	/**
	 * Get the type
	 *
	 * @return string
	 */
	public function getType()
	{
		$this->parseUrl();

		return $this->data['type'];
	}

	/**
	 * Get the expires
	 *
	 * @return string
	 */
	public function getExpires()
	{
		$this->parseUrl();

		return $this->data['expires'];
	}

	/**
	 * Get the ipbits
	 *
	 * @return string
	 */
	public function getIpbits()
	{
		$this->parseUrl();

		return $this->data['ipbits'];
	}

	/**
	 * Get the ip
	 *
	 * @return string
	 */
	public function getIp()
	{
		$this->parseUrl();

		return $this->data['ip'];
	}
}
