<?php

/*
 * PHP script for downloading videos from youtube
 * Copyright (C) 2012-2017  John Eckman
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, see <http://www.gnu.org/licenses/>.
 */

namespace YoutubeDownloader\VideoInfo;

/**
 * Interface for a VideoInfo instance
 *
 * This interface provides some information and Format instances for a video
 */
interface VideoInfo
{
	/**
	 * Get the Provider-ID, e.g. 'youtube', 'vimeo', etc
	 *
	 * @return string
	 */
	public function getProviderId();

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
