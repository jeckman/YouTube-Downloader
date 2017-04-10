<?php

namespace YoutubeDownloader;

/**
 * YouTubeDownloader
 */
class YoutubeDownloader
{
	/**
	 * Validates a video ID
	 *
	 * @param string $video_id
	 * @param string|null The validated video ID or null, if the video ID is invalid
	 */
	public static function validateVideoId($video_id)
	{
		if (strlen($video_id) <= 11)
		{
			return $video_id;
		}

		$url = parse_url($video_id);
		$video_id = null;

		if ( ! is_array($url) or count($url) === 0 or ! isset($url['query']) or empty($url['query']) )
		{
			return null;
		}

		$parts = explode('&', $url['query']);

		if (is_array($parts) && count($parts) > 0)
		{
			foreach ($parts as $p)
			{
				$pattern = '/^v\=/';

				if (preg_match($pattern, $p))
				{
					$video_id = preg_replace($pattern, '', $p);
					break;
				}
			}
		}

		if ( ! $video_id )
		{
			return null;
		}

		return $video_id;
	}

	public static function clean($string)
	{
		// Replaces all spaces with hyphens.
		$string = str_replace(' ', '-', $string);

		// Removes special chars.
		return preg_replace('/[^A-Za-z0-9\-]/', '', $string);
	}

	public static function isMobileUrl($string)
	{
		if (strpos($string, "m."))
		{
			return true;
		}

		return false;
	}

	public static function treatMobileUrl($string)
	{
		return str_replace("m.", "www.");
	}

	public static function formatBytes($bytes, $precision = 2)
	{
		$units = ['B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
		$bytes = max($bytes, 0);
		$pow = floor(($bytes ? log($bytes) : 0) / log(1024));
		$pow = min($pow, count($units) - 1);
		$bytes /= pow(1024, $pow);

		return round($bytes, $precision) . '' . $units[$pow];
	}

	public static function is_chrome()
	{
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
	 * function to get via cUrl
	 *
	 * From lastRSS 0.9.1 by Vojtech Semecky, webmaster @ webdot . cz
	 * See	  http://lastrss.webdot.cz/
	 */
	public static function curlGet($URL)
	{
		global $config; // get global $config to know if $config['multipleIPs'] is true

		$ch = curl_init();
		$timeout = 3;

		if ($config['multipleIPs'] === true)
		{
			// if $config['multipleIPs'] is true set outgoing ip to $outgoing_ip
			global $outgoing_ip;
			curl_setopt($ch, CURLOPT_INTERFACE, $outgoing_ip);
		}

		curl_setopt($ch, CURLOPT_URL, $URL);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

		/* if you want to force to ipv6, uncomment the following line */
		//curl_setopt( $ch , CURLOPT_IPRESOLVE , 'CURLOPT_IPRESOLVE_V6');
		$tmp = curl_exec($ch);
		curl_close($ch);

		return $tmp;
	}

	public static function get_size($url)
	{
		global $config;

		$my_ch = curl_init();

		if ($config['multipleIPs'] === true)
		{
			global $outgoing_ip;
			curl_setopt($my_ch, \CURLOPT_INTERFACE, $outgoing_ip);
		}

		curl_setopt($my_ch, \CURLOPT_URL, $url);
		curl_setopt($my_ch, \CURLOPT_HEADER, true);
		curl_setopt($my_ch, \CURLOPT_NOBODY, true);
		curl_setopt($my_ch, \CURLOPT_RETURNTRANSFER, true);
		curl_setopt($my_ch, \CURLOPT_TIMEOUT, 10);
		$r = curl_exec($my_ch);

		foreach (explode("\n", $r) as $header)
		{
			if (strpos($header, 'Content-Length:') === 0)
			{
				return trim(substr($header, 16));
			}
		}

		return '';
	}

	public static function getDownloadUrlByFormats($avail_formats, $format)
	{
		$target_formats = '';

		switch ($format)
		{
			case "best":
				/* largest formats first */
				$target_formats = ['38', '37', '46', '22', '45', '35', '44', '34', '18', '43', '6', '5', '17', '13'];
				break;
			case "free":
				/* Here we include WebM but prefer it over FLV */
				$target_formats = ['38', '46', '37', '45', '22', '44', '35', '43', '34', '18', '6', '5', '17', '13'];
				break;
			case "ipad":
				/* here we leave out WebM video and FLV - looking for MP4 */
				$target_formats = ['37', '22', '18', '17'];
				break;
			default:
				/* If they passed in a number use it */
				if (is_numeric($format))
				{
					$target_formats[] = $format;
				}
				else
				{
					$target_formats = ['38', '37', '46', '22', '45', '35', '44', '34', '18', '43', '6', '5', '17', '13'];
				}
				break;
		}

		/* Now we need to find our best format in the list of available formats */
		$best_format = '';

		for ($i = 0; $i < count($target_formats); $i++)
		{
			for ($j = 0; $j < count($avail_formats); $j++)
			{
				if ($target_formats[$i] == $avail_formats[$j]['itag'])
				{
					//echo '<p>Target format found, it is '. $avail_formats[$j]['itag'] .'</p>';
					$best_format = $j;
					break 2;
				}
			}
		}

		$redirect_url = null;

		if (
			(isset($best_format)) &&
			(isset($avail_formats[$best_format]['url'])) &&
			(isset($avail_formats[$best_format]['type']))
		)
		{
			$redirect_url = $avail_formats[$best_format]['url'] . '&title=' . $cleanedtitle;
			$content_type = $avail_formats[$best_format]['type'];
		}

		return $redirect_url;
	}

	public static function createStreamMapFromVideoInfo(array $video_info)
	{
		return explode(',', $video_info['url_encoded_fmt_stream_map']);
	}

	public static function parseStreamMapToFormats(array $stream_map)
	{
		$avail_formats = [];
		$sig = '';

		foreach ($stream_map as $format)
		{
			parse_str($format, $format_info);
			parse_str(urldecode($format_info['url']), $url_info);

			$type = explode(';', $format_info['type']);

			$avail_formats[] = [
				'itag' => $format_info['itag'],
				'quality' => $format_info['quality'],
				'type' => $type[0],
				'url' => urldecode($format_info['url']) . '&signature=' . $sig,
				'expires' => date("G:i:s T", $url_info['expire']),
				'ipbits' => $url_info['ipbits'],
				'ip' => $url_info['ip'],
			];
		}

		return $avail_formats;
	}
}
