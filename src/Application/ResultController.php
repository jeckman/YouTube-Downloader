<?php

namespace YoutubeDownloader\Application;

/**
 * The result controller
 */
class ResultController extends ControllerAbstract
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
		$template = $this->get('template');
		$toolkit = $this->get('toolkit');

		if( ! isset($_GET['videoid']) )
		{
			$this->responseWithErrorMessage('No video id passed in');
		}

		$my_id = $_GET['videoid'];

		if( $toolkit->isMobileUrl($my_id) )
		{
			$my_id = $toolkit->treatMobileUrl($my_id);
		}

		$my_id = $toolkit->validateVideoId($my_id);

		if ( $my_id === null )
		{
			$this->responseWithErrorMessage('Invalid url');
		}

		if (isset($_GET['type']))
		{
			$my_type = $_GET['type'];
		}
		else
		{
			$my_type = 'redirect';
		}

		$template_data = [
			'app_version' => $this->getAppVersion(),
		];

		/* First get the video info page for this video id */
		// $my_video_info = 'http://www.youtube.com/get_video_info?&video_id='. $my_id;
		// thanks to amit kumar @ bloggertale.com for sharing the fix
		$video_info_url = 'http://www.youtube.com/get_video_info?&video_id=' . $my_id . '&asv=3&el=detailpage&hl=en_US';
		$video_info_string = $toolkit->curlGet($video_info_url, $config);

		/* TODO: Check return from curl for status code */
		$video_info = \YoutubeDownloader\VideoInfo::createFromStringWithConfig($video_info_string, $config);
		$video_info->setCache($this->get('cache'));

		if ($video_info->getStatus() == 'fail')
		{
			$message = 'Error in video ID: ' . $video_info->getErrorReason();

			if ($config->get('debug'))
			{
				$message .= '<pre>' . var_dump($video_info) . '</pre>';
			}

			$this->responseWithErrorMessage($message);
		}

		if ( $my_type !== 'Download' )
		{
			/* In this else, the request didn't come from a form but from something else
			 * like an RSS feed.
			 * As a result, we just want to return the best format, which depends on what
			 * the user provided in the url.
			 * If they provided "format=best" we just use the largest.
			 * If they provided "format=free" we provide the best non-flash version
			 * If they provided "format=ipad" we pull the best MP4 version
			 *
			 * Thanks to the python based youtube-dl for info on the formats
			 *   							http://rg3.github.com/youtube-dl/
			 */
			$redirect_url = $toolkit->getDownloadUrlByFormats($video_info->getFormats(), $_GET['format']);

			if ( $redirect_url !== null )
			{
				header("Location: $redirect_url");
			}

			exit;
		}

		switch ($config->get('ThumbnailImageMode'))
		{
			case 2:
				$template_data['show_thumbnail'] = true;
				$template_data['thumbnail_src'] = 'getimage.php?videoid=' . $my_id;
				$template_data['thumbnail_anchor'] = 'getimage.php?videoid=' . $my_id . '&sz=hd';
				break;
			case 1:
				$template_data['show_thumbnail'] = true;
				$template_data['thumbnail_src'] = $video_info->getThumbnailUrl();
				$template_data['thumbnail_anchor'] = 'getimage.php?videoid=' . $my_id . '&sz=hd';
				break;
			case 0:
			default:
				$template_data['show_thumbnail'] = false;
		}

		$my_title = $video_info->getTitle();
		$cleanedtitle = $video_info->getCleanedTitle();

		$template_data['video_title'] = $video_info->getTitle();

		if (count($video_info->getFormats()) == 0)
		{
			$this->responseWithErrorMessage(
				'No format stream map found - was the video id correct?'
			);
		}

		if ($config->get('debug'))
		{
			$debug1 = '';

			if ($config->get('multipleIPs') === true)
			{
				$debug1 .= 'Outgoing IP: ' . print_r($toolkit->getRandomIp($config), true);
			}

			$template_data['show_debug1'] = true;
			$template_data['debug1'] = @var_export($video_info, true);
		}

		/* create an array of available download formats */
		$avail_formats = $video_info->getFormats();

		if ($config->get('debug'))
		{
			$first_format = $avail_formats[0];
			$template_data['show_debug2'] = true;
			$template_data['debug2_expires'] = $first_format->getExpires();
			$template_data['debug2_ip'] = $first_format->getIp();
			$template_data['debug2_ipbits'] = $first_format->getIpbits();
		}

		$template_data['streams'] = [];
		$template_data['formats'] = [];
		$template_data['showBrowserExtensions'] = ( $toolkit->is_chrome() and $config->get('showBrowserExtensions') == true );

		/* now that we have the array, print the options */
		foreach ($avail_formats as $avail_format)
		{
			$directlink = $avail_format->getUrl();
			// $directlink = explode('.googlevideo.com/', $avail_format->getUrl());
			// $directlink = 'http://redirector.googlevideo.com/' . $directlink[1] . '&ratebypass=yes&gcr=sg';
			$directlink .= '&title=' . $cleanedtitle;

			$proxylink = 'download.php?mime=' . $avail_format->getType() . '&title=' . urlencode($my_title) . '&token=' . base64_encode($avail_format->getUrl());

			$size = $toolkit->get_size($avail_format->getUrl(), $config);
			$size = $toolkit->formatBytes($size);

			$template_data['streams'][] = [
				'show_direct_url' => ($config->get('VideoLinkMode') === 'direct' || $config->get('VideoLinkMode') === 'both'),
				'show_proxy_url' => ($config->get('VideoLinkMode') === 'proxy' || $config->get('VideoLinkMode') === 'both'),
				'direct_url'=> $directlink,
				'proxy_url'=> $proxylink,
				'type'=> $avail_format->getType(),
				'quality'=> $avail_format->getQuality(),
				'size'=> $size,
			];
		}

		foreach ($video_info->getAdaptiveFormats() as $avail_format)
		{
			$directlink = $avail_format->getUrl();
			// $directlink = explode('.googlevideo.com/', $avail_format->getUrl());
			// $directlink = 'http://redirector.googlevideo.com/' . $directlink[1] . '&ratebypass=yes&gcr=sg';
			$directlink .= '&title=' . $cleanedtitle;

			$proxylink = 'download.php?mime=' . $avail_format->getType() . '&title=' . urlencode($my_title) . '&token=' . base64_encode($avail_format->getUrl());

			$size = $toolkit->get_size($avail_format->getUrl(), $config);
			$size = $toolkit->formatBytes($size);

			$template_data['formats'][] = [
				'show_direct_url' => ($config->get('VideoLinkMode') === 'direct' || $config->get('VideoLinkMode') === 'both'),
				'show_proxy_url' => ($config->get('VideoLinkMode') === 'proxy' || $config->get('VideoLinkMode') === 'both'),
				'direct_url'=> $directlink,
				'proxy_url'=> $proxylink,
				'type'=> $avail_format->getType(),
				'quality'=> $avail_format->getQuality(),
				'size'=> $size,
			];
		}

		if($config->get('MP3Enable'))
		{
			$mp3_url = sprintf(
				'download.php?mime=audio/mp3&token=%s&title=%s&getmp3=true',
				base64_encode($my_id),
				$cleanedtitle
			);

			$template_data['showMP3Download'] = true;
			$template_data['mp3_download_url'] = $mp3_url;
			$template_data['mp3_download_quality'] = $config->get('MP3Quality');
		}

		echo $template->render('getvideo.php', $template_data);
	}
}
