<?php
// YouTube Downloader PHP
// based on youtube-dl in Python http://rg3.github.com/youtube-dl/
// by Ricardo Garcia Gonzalez and others (details at url above)
//
// Takes a VideoID and outputs a list of formats in which the video can be 
// downloaded 

include_once('curl.php');

if(isset($_REQUEST['videoid'])) {
	$my_id = $_REQUEST['videoid']; 
} else {	
	echo '<p>No video id passed in</p>';
	exit; 
}

if(isset($_REQUEST['type'])) {
	$my_type =  $_REQUEST['type'];
} else {
	$my_type = 'redirect';
}
if ($my_type == 'Download') {
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title>Youtube Downloader</title>
    <meta name="keywords" content="Video downloader, download youtube, video download, youtube video, youtube downloader, download youtube FLV, download youtube MP4, download youtube 3GP, php video downloader" />
	<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
	 <style type="text/css">
      body {
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #f5f5f5;
	}
    
	  .download {
        max-width: 300px;
        padding: 19px 29px 29px;
        margin: 0 auto 20px;
        background-color: #fff;
        border: 1px solid #e5e5e5;
        -webkit-border-radius: 5px;
           -moz-border-radius: 5px;
                border-radius: 5px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                box-shadow: 0 1px 2px rgba(0,0,0,.05);
      }

      .download .download-heading {
        margin-bottom: 10px;
      }
    </style>
	</head>
<body>	
	<div class="download">
	<h1 class="download-heading">Youtube Downloader Results</h1>
<?php 
} // end of if for type=Download

/* First get the video info page for this video id */ 
$my_video_info = 'http://www.youtube.com/get_video_info?&video_id='. $my_id .'&ps=default&eurl=&gl=US&hl=en';
$my_video_info = curlGet($my_video_info);
$my_array = parse_str($my_video_info); 

/* Now get the url_encoded_fmt_stream_map, and explode on comma */ 
$my_formats_array = explode(',',$url_encoded_fmt_stream_map); 
if (count($my_formats_array) == 0) {
	echo '<p>No format stream map found - was the video id correct?</p>';
	exit;
}

/* create an array of available download formats */ 
$avail_formats[] = '';
$i = 0; 

foreach($my_formats_array as $format) {
	$my_array = parse_str($format); 
	$avail_formats[$i]['itag'] = $itag; 
	$avail_formats[$i]['quality'] = $quality; 
	$type = explode(';',$type); 
	$avail_formats[$i]['type'] = $type[0];
	$avail_formats[$i]['url'] = urldecode($url) . '&signature=' . $sig; 
	$i++; 
}

if ($my_type == 'Download') {
	echo '<ul>List of Available Formats for Download - right-click and choose "save as"</ul>'; 
	/* now that we have the array, print the options */ 
	for ($i = 0; $i< count($avail_formats); $i++) {
		echo '<li>' . $avail_formats[$i]['itag'] .' <a href="'. $avail_formats[$i]['url'] .'">'. $avail_formats[$i]['type'] .'</a> ('. $avail_formats[$i]['quality']  .')</li>';
	}
	echo '</ul>';
?>
</form>
</body>
</html>	

<?php 

} else {

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

$format =  $_REQUEST['format'];
$target_formats = ''; 
switch ($format) {
	case "best":
		/* largest formats first */ 
		$target_formats = array('38', '37', '46', '22', '45', '35', '44', '34', '18', '43', '6', '5', '17', '13');
		break;
	case "free":
		/* Here we include WebM but prefer it over FLV */ 
		$target_formats = array('38', '46', '37', '45', '22', '44', '35', '43', '34', '18', '6', '5', '17', '13');
		break;
	case "ipad":
		/* here we leave out WebM video and FLV - looking for MP4 */ 
		$target_formats = array('37','22','18','17'); 
		break;
	default: 
		/* If they passed in a number use it */
		if (is_numeric($format)) {
			$target_formats[] = $format; 
		} else {
			$target_formats = array('38', '37', '46', '22', '45', '35', '44', '34', '18', '43', '6', '5', '17', '13');
		}
	break;
}

/* Now we need to find our best format in the list of available formats */ 
$best_format = ''; 
for ($i=0; $i < count($target_formats); $i++) {
	for ($j=0; $j < count ($avail_formats); $j++) {
		if($target_formats[$i] == $avail_formats[$j]['itag']) {
			//echo '<p>Target format found, it is '. $avail_formats[$j]['itag'] .'</p>';
			$best_format = $j; 
			break 2; 
		}
	}
}

//echo '<p>Out of loop, best_format is '. $best_format .'</p>';
$redirect_url = $avail_formats[$best_format]['url'];
$content_type = $avail_formats[$best_format]['type'];
header("Location: $redirect_url"); 
} // end of else for type not being Download
?>
