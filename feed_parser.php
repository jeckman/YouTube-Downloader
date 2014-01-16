<?php 
/*
 ======================================================================
 YouTube Feed Parser
 
 Takes a YouTube feed (user, channel, tag, whatever) and converts it into
 an RSS feed your podcatcher can consume - saves videos in MP4 format. 

 I use this with Downcast.app on an iPad - your mileage may vary! 

 ----------------------------------------------------------------------
 LICENSE

 This program is free software; you can redistribute it and/or
 modify it under the terms of the GNU General Public License (GPL)
 as published by the Free Software Foundation; either version 2
 of the License, or (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 GNU General Public License for more details.

 To read the license please visit http://www.gnu.org/copyleft/gpl.html
 =====================================================================w=
*/

// CONFIGURATION SECTION

/*
 * Expects feeds from youtube like:
 * http://gdata.youtube.com/feeds/api/users/username/uploads
 * 
 */ 
$source_feed = 'http://gdata.youtube.com/feeds/api/users/username/uploads';  //feed from YouTube
$my_title = 'Title'; // plain text
$my_description = 'Description'; // plain text
$my_link = 'http://www.youtube.com/user/username/'; // full URL to show homepage 
$my_install_url = 'http://example.com/yt/'; // url where script is installed


/* 
 * URL where your cron job will save the feed output - this is used for the 
 * atom self-reference in the feed and should match where the feed will be 
 */ 
$my_feed_url = 'http://example.com/feed.xml'; 
$itunes_image = 'http://example.com/photo.jpg';

/* nothing to configure below here */ 

include_once('curl.php');
if ($rs = curlGet($source_feed)){
	//print_r($rs); 
} else { die('Error: Feed file not found, dude.'); }

$my_videos = simplexml_load_string($rs); 
 
/* write out the outer shell, channel, globals */ 
$updated = $my_videos->updated;
$updated= date("D, d M Y H:i:s T", strtotime($updated));
$output = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
	<rss version=\"2.0\" xmlns:itunes=\"http://www.itunes.com/dtds/podcast-1.0.dtd\"
		 xmlns:atom=\"http://www.w3.org/2005/Atom\">	
	<channel>
		<title>$my_title</title>
		<link>$my_link</link>
		<description>$my_description</description>
		<image>
			<url>$itunes_image</url>
			<link>$my_link</link>
			<description>$my_title</description>
			<title>$my_title</title>
		</image>
		<language>en-us</language>
		<lastBuildDate>$updated</lastBuildDate>
		<pubDate>$updated</pubDate>
		<itunes:explicit>no</itunes:explicit>
		<atom:link href=\"$my_feed_url\" rel=\"self\" type=\"application/rss+xml\" /> 

		";
		
		
		
/* now get the info on each item in the feed */ 
foreach ($my_videos as $entry) {	
	$pubDate = $entry->published; 
	$pubDate= date("D, d M Y H:i:s T", strtotime($pubDate));
	$videoid = explode('/',$entry->id); 
	$item_url = htmlentities($entry->link[0]['href']); 
	$item_title = htmlspecialchars($entry->title,ENT_QUOTES,'UTF-8');
	$full_item_url = $my_install_url . $item_title . '.mp4?videoid='. end($videoid) .'&format=ipad';
	$real_item_url = get_location($full_item_url); 
	$large_photo = 'http://i.ytimg.com/vi/'. end($videoid) .'/0.jpg';
	$item_size = get_size($real_item_url);
	$full_item_url = htmlentities($full_item_url); 
	$item_description = htmlspecialchars($entry->content,ENT_QUOTES,'UTF-8');
	if ($item_description == '') {
		$item_description = $item_title;
	}
	/* not clear why, but sometimes there are blank entries, which we ignore */ 
	if($item_title != '') {
		$output .= "<item>
			<pubDate>$pubDate</pubDate>
			<title>$item_title</title>
			<link>$item_url</link>
			<description>$item_description</description>
			<itunes:image href=\"$large_photo\" />
			<enclosure url=\"$full_item_url\" length=\"$item_size\" type=\"video/mpeg\" />
			<guid isPermaLink=\"true\">$item_url</guid>
		</item>
		";
	}
}

/* seems like we're getting the closing footer too early */
sleep(15); 

/* and output the closing footer */
$output .= "
	</channel>
</rss>
";
header("Content-Type: application/rss+xml");
echo $output;

/* end of main loop */ 

?>
