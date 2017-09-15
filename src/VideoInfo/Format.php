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
