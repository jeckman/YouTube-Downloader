<?php
// YouTube Downloader PHP
// based on youtube-dl in Python http://rg3.github.com/youtube-dl/
// by Ricardo Garcia Gonzalez and others (details at url above)
//
// Takes a VideoID and outputs a list of formats in which the video can be
// downloaded

include_once('common.php');
ob_start();// if not, some servers will show this php warning: header is already set in line 46...

if( ! isset($_GET['videoid']) )
{
	echo '<p>No video id passed in</p>';
	exit;
}

$my_id = $_GET['videoid'];

if( \YoutubeDownloader\YoutubeDownloader::isMobileUrl($my_id) )
{
	$my_id = \YoutubeDownloader\YoutubeDownloader::treatMobileUrl($my_id);
}

$my_id = \YoutubeDownloader\YoutubeDownloader::validateVideoId($my_id);

if ( $my_id === null )
{
    echo '<p>Invalid url</p>';
    exit;
}

if (isset($_GET['type']))
{
	$my_type = $_GET['type'];
}
else
{
	$my_type = 'redirect';
}

if ($my_type == 'Download')
{
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Youtube Downloader</title>
	<meta name="keywords"
		  content="Video downloader, download youtube, video download, youtube video, youtube downloader, download youtube FLV, download youtube MP4, download youtube 3GP, php video downloader"/>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
	<style type="text/css">
		body {
			padding-top: 40px;
			padding-bottom: 40px;
			background-color: #f5f5f5;
		}

		.download {
			max-width: 400px;
			padding: 19px 29px 29px;
			margin: 0 auto 20px;
			background-color: #fff;
			border: 1px solid #e5e5e5;
			-webkit-border-radius: 5px;
			-moz-border-radius: 5px;
			border-radius: 5px;
			-webkit-box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
			-moz-box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
			box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
		}

		.download .download-heading {
			text-align: center;
			margin-bottom: 10px;
		}

		.mime, .itag {
			width: 75px;
			display: inline-block;
		}

		.itag {
			width: 15px;
		}

		.size {
			width: 20px;
		}

		.userscript {
			float: right;
			margin-top: 5px
		}

		#info {
			padding: 0 0 0 130px;
			position: relative;
			height: 100px;
		}

		#info img {
			left: 0;
			position: absolute;
			top: 0;
			width: 120px;
			height: 90px
		}
	</style>
</head>
<body>
<div class="download">
	<h1 class="download-heading">Youtube Downloader Results</h1>
<?php
} // end of if for type=Download

/* First get the video info page for this video id */
// $my_video_info = 'http://www.youtube.com/get_video_info?&video_id='. $my_id;
// thanks to amit kumar @ bloggertale.com for sharing the fix
$video_info_url = 'http://www.youtube.com/get_video_info?&video_id=' . $my_id . '&asv=3&el=detailpage&hl=en_US';
$video_info_string = \YoutubeDownloader\YoutubeDownloader::curlGet($video_info_url);

/* TODO: Check return from curl for status code */
$video_info = \YoutubeDownloader\VideoInfo::createFromString($video_info_string);

if ($video_info->getStatus() == 'fail')
{
	echo '<p>Error in video ID: ' . $video_info->getErrorReason() . '</p>';

	if ($config['debug'])
	{
		echo '<pre>';
		var_dump($video_info);
		echo '</pre>';
	}
	exit();
}

echo '<div id="info">';

switch ($config['ThumbnailImageMode'])
{
	case 2:
		echo '<a href="getimage.php?videoid=' . $my_id . '&sz=hd" target="_blank"><img src="getimage.php?videoid=' . $my_id . '" border="0" hspace="2" vspace="2"></a>';
		break;
	case 1:
		echo '<a href="getimage.php?videoid=' . $my_id . '&sz=hd" target="_blank"><img src="' . $video_info->getThumbnailUrl() . '" border="0" hspace="2" vspace="2"></a>';
		break;
	case 0:
	default:  // nothing
}

echo '<p>' . $video_info->getTitle() . '</p>';
echo '</div>';

$my_title = $video_info->getTitle();
$cleanedtitle = $video_info->getCleanedTitle();

if ( $video_info->getStreamMapString() === null )
{
	echo '<p>No encoded format stream found.</p>';
	echo '<p>Here is what we got from YouTube:</p>';
	echo '<pre>';
	var_dump($video_info_string);
	echo '</pre>';
}

$stream_map = \YoutubeDownloader\StreamMap::createFromVideoInfo($video_info);

if ($config['debug'])
{
	if ($config['multipleIPs'] === true)
	{
		echo '<pre>Outgoing IP: ';
		print_r($outgoing_ip);
		echo '</pre>';
	}

	echo '<pre>';
	var_dump($stream_map);
	echo '</pre>';
}

if (count($stream_map->getStreams()) == 0)
{
	echo '<p>No format stream map found - was the video id correct?</p>';
	exit;
}

/* create an array of available download formats */
$avail_formats = $stream_map->getStreams();

if ($config['debug'])
{
	echo '<p>These links will expire at ' . $avail_formats[0]['expires'] . '</p>';
	echo '<p>The server was at IP address ' . $avail_formats[0]['ip'] . ' which is an ' . $avail_formats[0]['ipbits'] . ' bit IP address. ';
	echo 'Note that when 8 bit IP addresses are used, the download links may fail.</p>';
}

if ($my_type == 'Download')
{
	echo '<p align="center">List of available formats for download:</p>
		<ul>';

	/* now that we have the array, print the options */
	foreach ($avail_formats as $avail_format)
	{
		echo '<li>';

		if ($config['VideoLinkMode'] == 'direct' || $config['VideoLinkMode'] == 'both')
		{
			$directlink = $avail_format['url'];
			// $directlink = explode('.googlevideo.com/', $avail_format['url']);
			// $directlink = 'http://redirector.googlevideo.com/' . $directlink[1] . '&ratebypass=yes&gcr=sg';
			echo '<a href="' . $directlink . '&title=' . $cleanedtitle . '" class="mime">' . $avail_format['type'] . '</a> ';
			echo '(quality: ' . $avail_format['quality'];
		}
		else
		{
			echo '<span class="mime">' . $avail_format['type'] . '</span> ';
			echo '(quality: ' . $avail_format['quality'];
		}

		if ($config['VideoLinkMode'] == 'proxy' || $config['VideoLinkMode'] == 'both')
		{
			echo ' / ' . '<a href="download.php?mime=' . $avail_format['type'] . '&title=' . urlencode(
					$my_title
				) . '&token=' . base64_encode($avail_format['url']) . '" class="dl">download</a>';
		}

		$size = \YoutubeDownloader\YoutubeDownloader::get_size($avail_format['url']);

		echo ') ' .
			'<small><span class="size">' . \YoutubeDownloader\YoutubeDownloader::formatBytes($size) . '</span></small>' .
			'</li>';
	}

	echo '</ul><p align="center">Separated video and audio format:</p><ul>';

	foreach ($stream_map->getFormats() as $avail_format)
	{
		echo '<li>';

		if ($config['VideoLinkMode'] == 'direct' || $config['VideoLinkMode'] == 'both')
		{
			$directlink = $avail_format['url'];
			// $directlink = explode('.googlevideo.com/', $avail_format['url']);
			// $directlink = 'http://redirector.googlevideo.com/' . $directlink[1] . '&ratebypass=yes&gcr=sg';
			echo '<a href="' . $directlink . '&title=' . $cleanedtitle . '" class="mime">' . $avail_format['type'] . '</a> ';
			echo '(quality: ' . $avail_format['quality'];
		}
		else
		{
			echo '<span class="mime">' . $avail_format['type'] . '</span> ';
			echo '(quality: ' . $avail_format['quality'];
		}

		if ($config['VideoLinkMode'] == 'proxy' || $config['VideoLinkMode'] == 'both')
		{
			echo ' / ' . '<a href="download.php?mime=' . $avail_format['type'] . '&title=' . urlencode(
					$my_title
				) . '&token=' . base64_encode($avail_format['url']) . '" class="dl">download</a>';
		}

		$size = \YoutubeDownloader\YoutubeDownloader::get_size($avail_format['url']);

		echo ') ' .
			'<small><span class="size">' . \YoutubeDownloader\YoutubeDownloader::formatBytes($size) . '</span></small>' .
			'</li>';
	}

	echo '</ul>';

	echo '<small>Note that you initiate download either by clicking video format link or click "download" to use this server as proxy.</small>';

	if ( \YoutubeDownloader\YoutubeDownloader::is_chrome() and $config['feature']['browserExtensions'] == true )
	{
		echo '<a href="ytdl.user.js" class="userscript btn btn-mini" title="Install chrome extension to view a \'Download\' link to this application on Youtube video pages."> Install Chrome Extension </a>';
	}

	echo '
</body>
</html>';
}
else
{
	/* In this else, the request didn't come from a form but from something else
	 * like an RSS feed.
	 * As a result, we just want to return the best format, which depends on what
	 * the user provided in the url.
	 * If they provided "format=best" we just use the largest.
	 * If they provided "format=free" we provide the best non-flash version
	 * If they provided "format=ipad" we pull the best MP4 version
	 *
	 * Thanks to the python based youtube-dl for info on the formats
	 *   							http://rg3.github.com/youtube-dl/
	 */

	$redirect_url = \YoutubeDownloader\YoutubeDownloader::getDownloadUrlByFormats($avail_formats, $_GET['format']);

	if ( $redirect_url !== null )
	{
		header("Location: $redirect_url");
	}

} // end of else for type not being Download
