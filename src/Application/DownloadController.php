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
 * The download controller
 */
class DownloadController extends ControllerAbstract
{
	/**
	 * Excute the Controller
	 *
	 * @param string $route
	 * @param YoutubeDownloader\Application\App $app
	 *
	 * @return void
	 */
	public function execute()
	{
		$config = $this->get('config');
		$toolkit = $this->get('toolkit');

		// Check download token
		if (empty($_GET['mime']) OR empty($_GET['token']))
		{
			$this->responseWithErrorMessage('Invalid download token 8{');
		}

		// Set operation params
		$mime = filter_var($_GET['mime']);
		$ext = str_replace(['/', 'x-'], '', strstr($mime, '/'));
		$url = base64_decode(filter_var($_GET['token']));
		$name = urldecode($_GET['title']) . '.' . $ext;

		// Fetch and serve
		if ($url)
		{
			// prevent unauthorized download
			if($config->get('VideoLinkMode') === "direct" and !isset($_GET['getmp3']))
			{
				$this->responseWithErrorMessage(
					'VideoLinkMode: proxy download not enabled'
				);
			}

			if(
				$config->get('VideoLinkMode') !== "direct"
				and ! isset($_GET['getmp3'])
				and ! preg_match('@https://[^\.]+\.googlevideo.com/@', $url)
			)
			{
				$this->responseWithErrorMessage('unauthorized access (^_^)');
			}

			// check if request for mp3 download
			if(isset($_GET['getmp3']))
			{
				if( ! $config->get('MP3Enable') )
				{
					$this->responseWithErrorMessage(
						'Option for MP3 download is not enabled.'
					);
				}

				$youtube_provider = $this->get('YoutubeDownloader\Provider\Youtube\Provider');

				$video_info = $youtube_provider->provide($url);

				try
				{
					$mp3_info = $this->getDownloadMP3($video_info, $config);
				}
				catch (Exception $e)
				{
					$message = $e->getMessage();

					if($config->get('debug') && $e->getPrevious() !== null)
					{
						$message .= " " . $e->getPrevious()->getMessage();
					}

					$this->responseWithErrorMessage($message);
				}

				$url = $mp3_info['mp3'];
			}

			if(isset($mp3_info['mp3']))
			{
				$size = filesize($mp3_info['mp3']);
			}
			else
			{
				$size = $this->getSize($url, $config, $toolkit);
			}

			// Generate the server headers
			header('Content-Type: "' . $mime . '"');
			header('Content-Disposition: attachment; filename="' . $name . '"');
			header("Content-Transfer-Encoding: binary");
			header('Expires: 0');
			header('Content-Length: '.$size);
			header('Pragma: no-cache');

			if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== FALSE)
			{
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Pragma: public');
			}

			readfile($url);
			exit;
		}

		// Not found
		$this->responseWithErrorMessage('File not found 8{');
	}

	/**
	 * @param VideoInfo $video_info
	 * @param Config $config
	 *
	 * @throws Exception
	 *
	 * @return bool
	 */
	private function getDownloadMP3(VideoInfo $video_info, Config $config)
	{
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
