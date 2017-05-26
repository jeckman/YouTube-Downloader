<?php

namespace YoutubeDownloader;

/**
 * StreamMap
 */
class StreamMap
{
	/**
	 * Creates a StreamMap from VideoInfo
	 *
	 * @param VideoInfo $video_info
	 * @return StreamMap
	 */
	public static function createFromVideoInfo(VideoInfo $video_info)
	{
		// get the url_encoded_fmt_stream_map, and explode on comma
		$streams = explode(',', $video_info->getStreamMapString());
		$formats = explode(',', $video_info->getAdaptiveFormatsString());

		return new self($streams, $formats);
	}

	private $streams = [];

	private $formats = [];

	/**
	 * Creates a StreamMap from streams and formats arrays
	 *
	 * @param array $streams
	 * @param array $formats
	 * @return self
	 */
	private function __construct(array $streams, array $formats)
	{
		$this->streams = $this->parseStreams($streams);
		$this->formats = $this->parseStreams($formats);
	}

	/**
	 * Parses an array of streams
	 *
	 * @param array $streams
	 * @return array
	 */
	private function parseStreams(array $streams)
	{
		$formats = [];

		if (count($streams) === 1 and $streams[0] === '' )
		{
			return $formats;
		}

		foreach ($streams as $format)
		{
			parse_str($format, $format_info);
			parse_str(urldecode($format_info['url']), $url_info);

			if (isset($format_info['bitrate']))
			{
				$quality = isset($format_info['quality_label']) ? $format_info['quality_label'] : round($format_info['bitrate']/1000).'k';
			}
			else
			{
				$quality =  isset($format_info['quality']) ? $format_info['quality'] : '';
			}

			$type = explode(';', $format_info['type']);

			$formats[] = [
				'itag' => $format_info['itag'],
				'quality' => $quality,
				'type' => $type[0],
				'url' => $format_info['url'],
				'expires' => isset($url_info['expire']) ? date("G:i:s T", $url_info['expire']) : '',
				'ipbits' => isset($url_info['ipbits']) ? $url_info['ipbits'] : '',
				'ip' => isset($url_info['ip']) ? $url_info['ip'] : '',
			];
		}

		return $formats;
	}

	/**
	 * Get the streams
	 *
	 * @return string
	 */
	public function getStreams()
	{
		return $this->streams;
	}

	/**
	 * Get the formats
	 *
	 * @return string
	 */
	public function getFormats()
	{
		return $this->formats;
	}
}
