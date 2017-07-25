<?php

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

				$video_info_url = 'https://www.youtube.com/get_video_info?&video_id=' . $url. '&asv=3&el=detailpage&hl=en_US';
				$video_info_string = $toolkit->curlGet($video_info_url, $config);
				$video_info = \YoutubeDownloader\VideoInfo::createFromStringWithConfig($video_info_string, $config);
				$video_info->setCache($this->get('cache'));

				try
				{
					$mp3_info = $toolkit->getDownloadMP3($video_info, $config);
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
				$size = $toolkit->get_size($url, $config);
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
}
