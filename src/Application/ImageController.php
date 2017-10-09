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

namespace YoutubeDownloader\Application;

/**
 * The image controller
 */
class ImageController extends ControllerAbstract
{
	/**
	 * Excute the Controller
	 *
	 * @param string $route
	 * @param YoutubeDownloader\Application\App $app
	 *
	 * @return void
	 */
	public function execute()
	{
		if ( ! isset($_GET['videoid']) )
		{
			$this->responseWithErrorMessage('No video id passed in');
		}

		$my_id = $this->validateVideoId($_GET['videoid']);

		if ( $my_id === null )
		{
			$this->responseWithErrorMessage('Invalid video id passed in');
		}

		$szName = 'default';

		/**
		 * Player Background Thumbnail (480x360px) :	http://i1.ytimg.com/vi/VIDEO_ID/0.jpg
		 * Normal Quality Thumbnail (120x90px) :	http://i1.ytimg.com/vi/VIDEO_ID/default.jpg
		 * Medium Quality Thumbnail (320x180px) :	http://i1.ytimg.com/vi/VIDEO_ID/mqdefault.jpg
		 * High Quality Thumbnail (480x360px) :	http://i1.ytimg.com/vi/VIDEO_ID/hqdefault.jpg
		 * Start Thumbnail (120x90px) :   http://i1.ytimg.com/vi/VIDEO_ID/1.jpg
		 * Middle Thumbnail (120x90px) :   http://i1.ytimg.com/vi/VIDEO_ID/2.jpg
		 * End Thumbnail (120x90px) :	http://i1.ytimg.com/vi/VIDEO_ID/3.jpg
		 */
		if (!empty($_GET['sz']))
		{
			$arg = $_GET['sz'];

			switch ($arg)
			{
				case 'hd':
					$szName = 'hqdefault';
					break;
				case 'sd':
					$szName = 'default';
					break;
				default:
					$szName = $arg;
					break;
			}
		}

		$thumbnail_url = "http://i1.ytimg.com/vi/" . $my_id . "/$szName.jpg"; // make image link

		header("Content-Type: image/jpeg"); // set headers
		readfile($thumbnail_url); // show image
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
