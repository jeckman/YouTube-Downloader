<?php

namespace YoutubeDownloader;

@trigger_error(
	'StreamMap is deprecated since version 0.2, to be removed in 0.3. Use '.
	'VideoInfo::getFormats() and VideoInfo::getAdaptiveFormats() instead',
	E_USER_DEPRECATED
);

/**
 * StreamMap
 *
 * @deprecated since version 0.2, to be removed in 0.3. Use VideoInfo::getFormats() and VideoInfo::getAdaptiveFormats() instead
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

		return new self($streams, $formats, $video_info);
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
	private function __construct(array $streams, array $formats, VideoInfo $video_info)
	{
		$this->streams = $this->parseStreams($streams, $video_info);
		$this->formats = $this->parseStreams($formats, $video_info);
	}

	/**
	 * Parses an array of streams
	 *
	 * @param array $streams
	 * @return array
	 */
	private function parseStreams(array $streams, VideoInfo $video_info)
	{
		$formats = [];

		if (count($streams) === 1 and $streams[0] === '' )
		{
			return $formats;
		}

		foreach ($streams as $format)
		{
			parse_str($format, $format_info);

			$stream = Stream::createFromArray($video_info, $format_info);

			$formats[] = [
				'itag' => $stream->getItag(),
				'quality' => $stream->getQuality(),
				'type' => $stream->getType(),
				'url' => $stream->getUrl(),
				'expires' => $stream->getExpires(),
				'ipbits' => $stream->getIpbits(),
				'ip' => $stream->getIp(),
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
