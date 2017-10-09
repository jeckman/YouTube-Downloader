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

use Exception;
use YoutubeDownloader\VideoInfo\VideoInfo;

/**
 * Toolkit
 *
 * This class contains all functionallities that must be refactored.
 */
class Toolkit
{
	/**
	 * @var string random IP
	 */
	private $outgoing_ip;

	/**
	 * Select random IP from config
	 *
	 * If multipleIPs mode is enabled, select randomly one IP from
	 * the config IPs array and put it in $outgoing_ip variable.
	 *
	 * @param Config $config
	 * @return string|null The IP or null
	 */
	public function getRandomIp(Config $config)
	{
		if ($config->get('multipleIPs') !== true)
		{
			return null;
		}

		if ($this->outgoing_ip === null)
		{
			// randomly select an ip from the $config->get('IPs') array
			$ips = $config->get('IPs');
			$this->outgoing_ip = $ips[mt_rand(0, count($ips) - 1)];
		}

		return $this->outgoing_ip;
	}

	/**
	 * Validates a video ID
	 *
	 * @deprecated since version 0.6, to be removed in 0.7.
	 *
	 * This can be an url, embedding url or embedding html code
	 *
	 * @param string $video_id
	 * @return string|null The validated video ID or null, if the video ID is invalid
	 */
	public function validateVideoId($video_id)
	{
		@trigger_error(__METHOD__ . ' is deprecated since version 0.6, to be removed in 0.7.', E_USER_DEPRECATED);

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

	/**
	 * @deprecated since version 0.6, to be removed in 0.7.
	 *
	 * @param int $bytes
	 * @param int $precision
	 * @return string
	 */
	public function formatBytes($bytes, $precision = 2)
	{
		@trigger_error(__METHOD__ . ' is deprecated since version 0.6, to be removed in 0.7.', E_USER_DEPRECATED);

		$units = ['B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
		$bytes = max($bytes, 0);
		$pow = floor(($bytes ? log($bytes) : 0) / log(1024));
		$pow = min($pow, count($units) - 1);
		$bytes /= pow(1024, $pow);

		return round($bytes, $precision) . '' . $units[$pow];
	}

	/**
	 * @deprecated since version 0.6, to be removed in 0.7.
	 *
	 * @return bool
	 */
	public function is_chrome()
	{
		@trigger_error(__METHOD__ . ' is deprecated since version 0.6, to be removed in 0.7.', E_USER_DEPRECATED);

		$agent = $_SERVER['HTTP_USER_AGENT'];

		// if user agent is google chrome
		if (preg_match("/like\sGecko\)\sChrome\//", $agent))
		{
			// but not Iron
			if (!strstr($agent, 'Iron'))
			{
				return true;
			}
		}

		// if isn't chrome return false
		return false;
	}

	/**
	 * @deprecated since version 0.6, to be removed in 0.7.
	 *
	 * @param VideoInfo $video_info
	 * @param Config $config
	 *
	 * @throws Exception
	 *
	 * @return bool
	 */
	public function getDownloadMP3(VideoInfo $video_info, Config $config)
	{
		@trigger_error(__METHOD__ . ' is deprecated since version 0.6, to be removed in 0.7.', E_USER_DEPRECATED);

		// generate new url, we can re-use previously generated link and pass it
		// to "token" parameter, but it is too dangerous to use it with exec()
		// TODO: Background conversion, Ajax and Caching
		// @ewwink
		$audio_quality = 0;
		$media_url = "";
		$media_type = "";

		// find audio with highest quality
		foreach($video_info->getFormats() as $format)
		{
			if(strpos($format->getType(), 'audio') !== false && intval($format->getQuality()) > intval($audio_quality))
			{
				$audio_quality = $format->getQuality();
				$media_url = $format->getUrl();
				$media_type = str_replace("audio/", "", $format->getType());
			}
		}

		if(empty($media_url))
		{
			if( $config->get('MP3ConvertVideo') !== true )
			{
				throw new Exception(
					'MP3 downlod failed, adaptive audio format not available, try to set config "MP3ConvertVideo" to true'
				);
			}

			// some video does not have adaptive or dash format, downloading video instead
			$formats = $video_info->getAdaptiveFormats();

			if (count($formats) === 0)
			{
				throw new Exception('MP3 downlod failed, no stream was found.');
			}

			$fallbackFormat = $formats[0];
			$media_url = $fallbackFormat->getUrl();
			$media_type = str_replace("audio/", "", $fallbackFormat->getType());
		}

		$mp3dir = realpath($config->get('MP3TempDir'));
		$mediaName = $_GET['title'] . '.' . $media_type;
		// -x4: set 4 connection for each download
		$cmd = '"' . $config->get('aria2Path') . '"' . " -x4 -k1M --continue=true --dir=\"$mp3dir\" --out=$mediaName \"$media_url\" 2>&1" ;
		exec($cmd, $output);

		if(strpos(implode(" ", $output), "download completed") === false)
		{
			throw new Exception(
				'Download media url from youtube failed.',
				0,
				new Exception($output[0])
			);
		}

		// Download media from youtube success
		$mp3Name = $_GET['title'] . '.mp3';

		if($config->get('MP3Quality') !== "high" || $audio_quality === 0)
		{
			$audio_quality = intval($config->get('MP3Quality')) > intval($audio_quality) ? $audio_quality : $config->get('MP3Quality');
		}

		$cmd = '"' . $config->get('ffmpegPath') . '"' . " -i \"$mp3dir/$mediaName\" -b:a $audio_quality -vn \"$mp3dir/$mp3Name\" 2>&1";

		exec($cmd, $output);

		if(strpos(implode(" ", $output), "Output #0, mp3") !== FALSE || file_exists("$mp3dir/$mp3Name"))
		{
			// Convert media to .mp3 success
			return array(
				"status" => "success",
				"message" => "Convert media to .mp3 success",
				"mp3" => "$mp3dir/$mp3Name",
				"debugMessage" => $output
			);
		}
	}
}
