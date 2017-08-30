<?php

namespace YoutubeDownloader\VideoInfo;

/**
 * Interface for a video format
 *
 * This interface is agnostic about how the data is set to the
 * implementation. It is up to the provider where and how the data
 * for a format cames from.
 */
interface Format
{
	/**
	 * Get the video_id
	 *
	 * @return string
	 */
	public function getVideoId();

	/**
	 * Get the url
	 *
	 * @return string
	 */
	public function getUrl();

	/**
	 * Get the itag
	 *
	 * @return string
	 */
	public function getItag();

	/**
	 * Get the quality
	 *
	 * @return string
	 */
	public function getQuality();

	/**
	 * Get the type
	 *
	 * @return string
	 */
	public function getType();

	/**
	 * Get the expires
	 *
	 * @return string
	 */
	public function getExpires();

	/**
	 * Get the ipbits
	 *
	 * @return string
	 */
	public function getIpbits();

	/**
	 * Get the ip
	 *
	 * @return string
	 */
	public function getIp();
}
