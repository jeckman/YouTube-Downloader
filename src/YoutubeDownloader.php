<?php

namespace YoutubeDownloader;

/**
 * YouTubeDownloader
 */
class YoutubeDownloader
{
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
}
