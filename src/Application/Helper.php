<?php

namespace YoutubeDownloader\Application;

use YoutubeDownloader\VideoInfo\VideoInfo;

class Helper
{
    
    /**
	 * Get the download url for a specific format
	 *
	 * @param array $avail_formats
	 * @param string $format
	 * @return string|null
	 */
    function getDownloadUrlByFormat(VideoInfo $video_info, $format)
	{
		$target_formats = [];

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
		$best_format = null;

		$avail_formats = $video_info->getFormats() + $video_info->getAdaptiveFormats();

		foreach ( $target_formats as $target_format )
		{
			foreach ( $avail_formats as $format )
			{
				if ($target_format == $format->getItag())
				{
					$best_format = $format;
					break 2;
				}
			}
		}

		$redirect_url = null;

		if ( $best_format === '' )
		{
			return null;
		}

		$redirect_url = $best_format->getUrl();

		if ( ! empty($redirect_url) )
		{
			$redirect_url .= '&title=' . $video_info->getCleanedTitle();
		}

		return $redirect_url;
	}

    /**
	 * Get the type for a specific format
	 *
	 * @param array $avail_formats
	 * @param string $format
	 * @return string|null
	 */
    function getTypeByFormat(VideoInfo $video_info, $format)
	{
		$target_formats = [];

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
		$best_format = null;

		$avail_formats = $video_info->getFormats() + $video_info->getAdaptiveFormats();

		foreach ( $target_formats as $target_format )
		{
			foreach ( $avail_formats as $format )
			{
				if ($target_format == $format->getItag())
				{
					$best_format = $format;
					break 2;
				}
			}
		}

		$format = null;

		if ( $best_format === '' )
		{
			return null;
		}

		$format = $best_format->getType();

		if ( ! empty($redirect_url) )
		{
			$format .= '&title=' . $video_info->getCleanedTitle();
		}

		return $format;
	}
    
    	/**
	 * Format a byte integer into a human readable string
	 *
	 * e.g. 1024 => 1kB
	 *
	 * @param int $bytes
	 * @param int $precision
	 * @return string
	 */
	function formatBytes($bytes, $precision = 2)
	{
		$units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
		$bytes = max($bytes, 0);
		$pow = floor(($bytes ? log($bytes) : 0) / log(1024));
		$pow = min($pow, count($units) - 1);
		$bytes /= pow(1024, $pow);

		return round($bytes, $precision) . '' . $units[$pow];
	}
}