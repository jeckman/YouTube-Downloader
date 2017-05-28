<?PHP
include_once('common.php');

if ( ! isset($_GET['videoid']) )
{
	echo '<p>No video id passed in</p>';
	exit;
}

$my_id = \YoutubeDownloader\YoutubeDownloader::validateVideoId($_GET['videoid']);


if ( $my_id === null )
{
	echo '<p>Invalid video id passed in</p>';
	exit;
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
