<?php

namespace YoutubeDownloader\Provider\Youtube;

use YoutubeDownloader\Cache\CacheAware;
use YoutubeDownloader\Cache\CacheAwareTrait;
use YoutubeDownloader\Config;
use YoutubeDownloader\Http\HttpClientAware;
use YoutubeDownloader\Http\HttpClientAwareTrait;
use YoutubeDownloader\Logger\LoggerAware;
use YoutubeDownloader\Logger\LoggerAwareTrait;
use YoutubeDownloader\Toolkit;
use YoutubeDownloader\VideoInfo\Provider as ProviderInterface;
use YoutubeDownloader\VideoInfo\InvalidInputException;

/**
 * Provider instance for Youtube
 */
final class Provider implements ProviderInterface, CacheAware, HttpClientAware, LoggerAware
{
	use CacheAwareTrait;
	use HttpClientAwareTrait;
	use LoggerAwareTrait;

	/**
	 * Create this Provider from Config and Toolkit
	 *
	 * @param Config $config
	 * @param Toolkit $toolkit
	 * @return self
	 */
	public static function createFromConfigAndToolkit(Config $config, Toolkit $toolkit)
	{
		return new self($config, $toolkit);
	}

	/**
	 * @var YoutubeDownloader\Config
	 */
	private $config;

	/**
	 * @var YoutubeDownloader\Toolkit
	 */
	private $toolkit;

	/**
	 * Create this Provider
	 *
	 * @param Config $config
	 * @param Toolkit $toolkit
	 * @return self
	 */
	private function __construct(Config $config, Toolkit $toolkit)
	{
		$this->config = $config;
		$this->toolkit = $toolkit;
	}

	/**
	 * Check if this provider can create a VideoInfo from a given input
	 *
	 * This check should be done as fast as possible like run some simple
	 * regex on the input to determine a specific domain or ID pattern.
	 *
	 * There is no guarantee that after `provides()` returns true the
	 * `provide()` will return a `VideoInfo` instance. This method should only
	 * be used as a first indicator if the provider can handle the input for
	 * speed reason. So you should keep in mind that `provide()` can also
	 * throw an exception even if `provides()` returns true
	 *
	 * @param string $input The input like an url or ID
	 * @return boolean true if this provider could handle the input, else false
	 */
	public function provides($input)
	{
		$input = $this->treatUrlIfMobile($input);

		return ($this->validateVideoId($input) !== null);
	}

	/**
	 * Provides a YoutubeDownloader\VideoInfo\VideoInfo instance for the input
	 *
	 * There is no guarantee that `provides()` will be called before this.
	 * This method should also be idempotent, so a repeated call with the same
	 * input should have the same result. This can be returning a VideoInfo or
	 * throwing an Exception.
	 *
	 * An exception can be thrown if the input can't be handled or if there are
	 * other reasons that prevents the creation of a VideoInfo like connection
	 * problems or invalid responses.
	 *
	 * @param string $input The input like an url or ID
	 * @throws YoutubeDownloader\VideoInfo\Exception if the VideoInfo could not be created
	 * @throws YoutubeDownloader\VideoInfo\InvalidInputException if the input can't be handled
	 * @return YoutubeDownloader\VideoInfo\VideoInfo
	 */
	public function provide($input)
	{
		$input = $this->treatUrlIfMobile($input);

		$input = $this->validateVideoId($input);

		if ( $input === null )
		{
			throw new InvalidInputException;
		}

		/* First get the video info page for this video id */
		// $my_video_info = 'http://www.youtube.com/get_video_info?&video_id='. $input;
		// thanks to amit kumar @ bloggertale.com for sharing the fix
		$video_info_url = 'http://www.youtube.com/get_video_info?&video_id=' . $input . '&asv=3&el=detailpage&hl=en_US';

		$request = $this->getHttpClient()->createRequest(
			'GET',
			$video_info_url
		);

		$options = ['curl' => []];

		if ( $this->config->get('multipleIPs') === true)
		{
			$options['curl'][CURLOPT_INTERFACE] = $this->toolkit->getRandomIp($this->config);
		}

		$response = $this->getHttpClient()->send($request, $options);

		/* TODO: Check response for status code and Content-Type */
		$video_info = VideoInfo::createFromStringWithConfig(
			$response->getBodyAsString(),
			$this->config
		);

		if ( $video_info instanceOf CacheAware )
		{
			$video_info->setCache($this->getCache());
		}

		if ( $video_info instanceOf HttpClientAware )
		{
			$video_info->setHttpClient($this->getHttpClient());
		}

		if ( $video_info instanceOf LoggerAware )
		{
			$video_info->setLogger($this->getLogger());
		}

		return $video_info;
	}

	/**
	 * @param string $string
	 * @return string
	 */
	private function treatUrlIfMobile($string)
	{
		if (strpos($string, "m."))
		{
			$string = str_replace("m.", "www.", $string);
		}

		return $string;
	}

	/**
	 * Validates a video ID
	 *
	 * This can be an url, embedding url or embedding html code
	 *
	 * @param string $video_id
	 * @return string|null The validated video ID or null, if the video ID is invalid
	 */
	private function validateVideoId($video_id)
	{
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
}
