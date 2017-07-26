<?php

namespace YoutubeDownloader;

@trigger_error(
	'`Stream` is deprecated since version 0.2, to be removed in 0.3. '.
	'Use `Format` instead',
	E_USER_DEPRECATED
);

/**
 * Stream
 *
 * @deprecated since version 0.2, to be removed in 0.3. Use Format instead
 */
class Stream
{
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
	 * @return Stream
	 */
	public static function createFromArray(VideoInfo $video_info, array $stream_info)
	{
		return new self($video_info, $stream_info);
	}

	private $video_info;

	private $data = [];

	private $raw_data = [];

	/**
	 * Creates a StreamMap from streams and formats arrays
	 *
	 * @param VideoInfo $video_info
	 * @param array $data
	 * @return self
	 */
	private function __construct(VideoInfo $video_info, array $data)
	{
		$this->video_info = $video_info;

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

		$this->parseUrl();
	}

	/**
	 * Parses the url
	 *
	 * @return void
	 */
	private function parseUrl()
	{
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
		if (isset($this->raw_data['s']))
		{
			// Uncomment the following 2 lines to activate signature decipher
			//$playerID = SignatureDecipher::downloadPlayerScript($this->getVideoId());
			//$signature = '&ratebypass=yes&signature='.SignatureDecipher::decipherSignature($playerID, $this->raw_data['s']);
		}

		$this->data['url'] = $this->raw_data['url'].$signature;

		$type = explode(';', $this->raw_data['type']);
		$this->data['type'] = $type[0];

		$this->data['expires'] = isset($url_info['expire']) ? date("G:i:s T", $url_info['expire']) : '';
		$this->data['ipbits'] = isset($url_info['ipbits']) ? $url_info['ipbits'] : '';
		$this->data['ip'] = isset($url_info['ip']) ? $url_info['ip'] : '';
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
		return $this->data['url'];
	}

	/**
	 * Get the itag
	 *
	 * @return string
	 */
	public function getItag()
	{
		return $this->data['itag'];
	}

	/**
	 * Get the quality
	 *
	 * @return string
	 */
	public function getQuality()
	{
		return $this->data['quality'];
	}

	/**
	 * Get the type
	 *
	 * @return string
	 */
	public function getType()
	{
		return $this->data['type'];
	}

	/**
	 * Get the expires
	 *
	 * @return string
	 */
	public function getExpires()
	{
		return $this->data['expires'];
	}

	/**
	 * Get the ipbits
	 *
	 * @return string
	 */
	public function getIpbits()
	{
		return $this->data['ipbits'];
	}

	/**
	 * Get the ip
	 *
	 * @return string
	 */
	public function getIp()
	{
		return $this->data['ip'];
	}
}
