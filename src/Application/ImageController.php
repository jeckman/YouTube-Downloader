<?php

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
		$config = $this->get('config');
		$toolkit = $this->get('toolkit');

		if ( ! isset($_GET['videoid']) )
		{
			$this->responseWithErrorMessage('No video id passed in');
		}

		$my_id = $toolkit->validateVideoId($_GET['videoid']);

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
}
