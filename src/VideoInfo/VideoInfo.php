<?php

namespace YoutubeDownloader\VideoInfo;

/**
 * Interface for a VideoInfo instance
 *
 * This interface provides some information and Format instances for a video
 */
interface VideoInfo
{
	/**
	 * Get the video_id
	 *
	 * @return string
	 */
	public function getVideoId();

	/**
	 * Get the status
	 *
	 * @return string
	 */
	public function getStatus();

	/**
	 * Get the error reason
	 *
	 * @return string
	 */
	public function getErrorReason();

	/**
	 * Get the thumbnail_url
	 *
	 * @return string
	 */
	public function getThumbnailUrl();

	/**
	 * Get the title
	 *
	 * @return string
	 */
	public function getTitle();

	/**
	 * Get the cleaned title
	 *
	 * @return string
	 */
	public function getCleanedTitle();

	/**
	 * Get the Formats
	 *
	 * @return Format[] array with Format instances
	 */
	public function getFormats();

	/**
	 * Get the adaptive Formats
	 *
	 * @return Format[] array with Format instances
	 */
	public function getAdaptiveFormats();
}
