<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Youtube Downloader</title>
	<meta name="keywords"
		  content="Video downloader, download youtube, video download, youtube video, youtube downloader, download youtube FLV, download youtube MP4, download youtube 3GP, php video downloader"/>
	<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
	<link href="css/custom.css" rel="stylesheet">
</head>
<body>
	<form class="form-download" method="get" id="download" action="getvideo.php">
		<h1 class="form-download-heading">Youtube Downloader</h1>
		<input type="text" name="videoid" id="videoid" size="40" placeholder="YouTube Link or VideoID" autofocus/>
		<input class="btn btn-primary" type="submit" name="type" id="type" value="Download" />
		<p>Valid inputs are YouTube links or VideoIDs:</p>
		<ul>
			<li>youtube.com/watch?v=...</li>
			<li>youtu.be/...</li>
			<li>youtube.com/embed/...</li>
			<li>youtube-nocookie.com/embed/...</li>
			<li>youtube.com/watch?feature=player_embedded&v=...</li>
		</ul>

	<!-- @TODO: Prepend the base URI -->
<?php
include_once('common.php');

if ( \YoutubeDownloader\YoutubeDownloader::is_chrome() and $config->get('showBrowserExtensions') == true )
{
	echo '<a href="ytdl.user.js" class="userscript btn btn-mini" title="Install chrome extension to view a \'Download\' link to this application on Youtube video pages."> Install Chrome Extension </a>';
}
?>
</form>
</body>
</html>
