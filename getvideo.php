<?php
// YouTube Downloader PHP
// based on youtube-dl in Python http://rg3.github.com/youtube-dl/
// by Ricardo Garcia Gonzalez and others (details at url above)
//
// Takes a VideoID and outputs a list of formats in which the video can be
// downloaded
// if not, some servers will show this php warning: header is already set in line 46...
include_once('config.php');
ob_start();

function formatBytes($bytes, $precision = 2) { 
    $units = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'); 
    $bytes = max($bytes, 0); 
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
    $pow = min($pow, count($units) - 1); 
    $bytes /= pow(1024, $pow);
    return round($bytes, $precision) . '' . $units[$pow]; 
} 
function is_chrome(){
	$agent=$_SERVER['HTTP_USER_AGENT'];
	if( preg_match("/like\sGecko\)\sChrome\//", $agent) ){	// if user agent is google chrome
		if(!strstr($agent, 'Iron')) // but not Iron
			return true;
	}
	return false;	// if isn't chrome return false
}

if(isset($_REQUEST['videoid'])) {
	$my_id = $_REQUEST['videoid'];
	if(strlen($my_id)>11){
		$url   = parse_url($my_id);
		$my_id = NULL;
		if( is_array($url) && count($url)>0 && isset($url['query']) && !empty($url['query']) ){
			$parts = explode('&',$url['query']);
			if( is_array($parts) && count($parts) > 0 ){
				foreach( $parts as $p ){
					$pattern = '/^v\=/';
					if( preg_match($pattern, $p) ){
						$my_id = preg_replace($pattern,'',$p);
						break;
					}
				}
			}
			if( !$my_id ){
				echo '<p>No video id passed in</p>';
				exit;
			}
		}else{
			echo '<p>Invalid url</p>';
			exit;
		}
	}
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
      		text-align:center;
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
			height:100px;
	  }
	  
	  #info img{
			left: 0;
			position: absolute;
			top: 0;
			width:120px;
			height:90px
	  }
    </style>
	</head>
<body>
	<div class="download">
	<h1 class="download-heading">Youtube Downloader Results</h1>
<?php
} // end of if for type=Download

/* First get the video info page for this video id */
$my_video_info = 'http://www.youtube.com/get_video_info?&video_id='. $my_id;
$my_video_info = curlGet($my_video_info);

/* TODO: Check return from curl for status code */
$thumbnail_url = $title = $url_encoded_fmt_stream_map = $type = $url = '';
$ytd = array();

parse_str($my_video_info,$ytd);

echo '<pre>' . print_r($ytd,true) . '</pre>';

/* if use_cipher_signature is not present or is false we can just proceed */ 
if(((isset($tyd['use_cipher_signature'])) && ($ytd['use_cipher_signature'] != 'False')) || ($ytd['status'] != 'ok')) {
	$my_video_page = 'http://www.youtube.com/watch?v=' . $my_id; 
	$my_video_page = curlGet($my_video_page);

	/* TODO: Check curl status code and handle */
		
	$my_ytplayer = preg_match('/ytplayer.config.=.*}};<\/script/',$my_video_page,$matches); 
	if($my_ytplayer != 1) {
		echo '<div>No ytplayer found </div>';
	}
	//echo '<div>YT Player is '. $matches[0] . '</div>';
	$my_ytplayer = mb_substr($matches[0],18);
	$my_ytplayer = rtrim($my_ytplayer,';</script/'); 
	//echo '<div>YT Player JSON is '. $my_ytplayer . '</div>';
	
	$my_ytconfig = json_decode($my_ytplayer,true); 
	switch (json_last_error()) {
        case JSON_ERROR_NONE:
            echo ' - No errors';
        break;
        case JSON_ERROR_DEPTH:
            echo ' - Maximum stack depth exceeded';
        break;
        case JSON_ERROR_STATE_MISMATCH:
            echo ' - Underflow or the modes mismatch';
        break;
        case JSON_ERROR_CTRL_CHAR:
            echo ' - Unexpected control character found';
        break;
        case JSON_ERROR_SYNTAX:
            echo ' - Syntax error, malformed JSON';
        break;
        case JSON_ERROR_UTF8:
            echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
        break;
        default:
            echo ' - Unknown error';
        break;
    }
	//echo '<div>Var dump: '. $my_ytconfig . '</div>'; 
	//echo '<div>yt player object is <pre>'. print_r($my_ytconfig,true) . '</pre></div>';
	$my_title = $my_ytconfig['args']['title'];
	$my_formats_array = explode(',',$my_ytconfig['args']['url_encoded_fmt_stream_map']);
	//echo '<div> My Formats = '. print_r($my_formats_array,true) .'</div>'; 
	$html5player = $my_ytconfig['assets']['js'];
	if (strpos($html5player,'//') == 0) {
		$html5player = 'http://' . $html5player; 
	}
} else {
	$my_title = $ytd['title'];
	$my_thumbnail = $ytd['thumbnail_url'];
	if(isset($ytd['url_encoded_fmt_stream_map'])) {
		/* Now get the url_encoded_fmt_stream_map, and explode on comma */
		$my_formats_array = explode(',',$ytd['url_encoded_fmt_stream_map']);
		if($debug) {
			echo '<pre>';
			print_r($my_formats_array);
			echo '</pre>';
		}
	} else {
		echo '<p>No encoded format stream found.</p>';
		echo '<p>Here is what we got from YouTube:</p>';
		echo $my_video_info;
	}
	if (count($my_formats_array) == 0) {
		echo '<p>No format stream map found - was the video id correct?</p>';
		exit;
	}
}

echo '<div id="info">';
switch($config['ThumbnailImageMode'])
{
  case 2: echo '<img src="getimage.php?videoid='. $my_id .'" border="0" hspace="2" vspace="2">'; break;
  case 1: echo '<img src="'. $my_thumbnail .'" border="0" hspace="2" vspace="2">'; break;
  case 0:  default:  // nothing
}
echo '<p>'. $my_title .'</p>';
echo '</div>';

/* create an array of available download formats */
$avail_formats[] = '';
$i = 0;
$ipbits = $ip = $itag = $sig = $quality = '';
$expire = time(); 

echo '<div> My Formats array is <pre>'. print_r($my_formats_array, true) .'</pre></div>';
foreach($my_formats_array as $format) {
	parse_str($format);
	$avail_formats[$i]['itag'] = $itag;
	$avail_formats[$i]['quality'] = $quality;
	$type = explode(';',$type);
	$avail_formats[$i]['type'] = $type[0];
	if(isset($s)) {
		$sig = decodesig($s);
		$avail_formats[$i]['url'] = urldecode($url) . '&signature=' . $sig;
		echo '<div> sig decoded is '. $sig .'</div>';
	} else {
		$avail_formats[$i]['url'] = urldecode($url) . '&signature=' . $sig;
	}
	parse_str(urldecode($url));
	$avail_formats[$i]['expires'] = date("G:i:s T", $expire);
	//$avail_formats[$i]['ipbits'] = $ipbits;
	$avail_formats[$i]['ip'] = $ip;
	$i++;
}

if ($debug) {
	echo '<p>These links will expire at '. $avail_formats[0]['expires'] .'</p>';
	echo '<p>The server was at IP address '. $avail_formats[0]['ip'] .' which is an '. $avail_formats[0]['ipbits'] .' bit IP address. ';
	echo 'Note that when 8 bit IP addresses are used, the download links may fail.</p>';
}
if ($my_type == 'Download') {
	echo '<p align="center">List of available formats for download:</p>
		<ul>';

	/* now that we have the array, print the options */
	for ($i = 0; $i < count($avail_formats); $i++) {
		echo '<li>';
		echo '<span class="itag">' . $avail_formats[$i]['itag'] . '</span> ';
		if($config['VideoLinkMode']=='direct'||$config['VideoLinkMode']=='both')
		  echo '<a href="' . $avail_formats[$i]['url'] . '" class="mime">' . $avail_formats[$i]['type'] . '</a> ';
		else
		  echo '<span class="mime">' . $avail_formats[$i]['type'] . '</span> ';
		echo '<small>(' .  $avail_formats[$i]['quality'];
		if($config['VideoLinkMode']=='proxy'||$config['VideoLinkMode']=='both')
		  echo ' / ' . '<a href="download.php?mime=' . $avail_formats[$i]['type'] .'&title='. urlencode($my_title) .'&token='.base64_encode($avail_formats[$i]['url']) . '" class="dl">download</a>';
		echo ')</small> '.
			'<small><span class="size">' . formatBytes(get_size($avail_formats[$i]['url'])) . '</span></small>'.
		'</li>';
	}
	echo '</ul><small>Note that you can Right-click and choose "save as" or click "download" to use this server as proxy.</small>';

  if(($config['feature']['browserExtensions']==true)&&(is_chrome()))
    echo '<a href="ytdl.user.js" class="userscript btn btn-mini" title="Install chrome extension to view a \'Download\' link to this application on Youtube video pages."> Install Chrome Extension </a>';
?>

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
if( (isset($best_format)) && 
  (isset($avail_formats[$best_format]['url'])) && 
  (isset($avail_formats[$best_format]['type'])) 
  ) {
	$redirect_url = $avail_formats[$best_format]['url'];
	$content_type = $avail_formats[$best_format]['type'];
}
if(isset($redirect_url)) {
	header("Location: $redirect_url"); 
}

} // end of else for type not being Download

function decodesig($s) {
	switch(strlen($s)) {
		case 93:
			return strrev(substr($s,29,57)) . substr($s,88,1) . strrev(substr($s,5,23));
			break;
		case 92:
			return substr($s,25,1) . substr($s,3,22) . substr($s,0,1) . substr($s,26,16) . substr($s,79,1) . substr($s,43,36) . substr($s,91,1) . substr($s,80,3); 
			break; 
		case 91:
			return strrev(substr($s,27,57)) . substr($s,86,1) . strrev(substr($s,5,21));
			break;
		case 90:
			return substr($s,25,1) . substr($s,3,22) . substr($s,2,1) . substr($s,26,14) . substr($s,77,1) . substr($s,41,36) . substr($s,89,1) . substr($s,78,3); 
			break;
		case 89:
			return strrev(substr($s,78,6)) . substr($s,87,1) . strrev(substr($s,60,17)) . substr($s,0,1) . strrev(substr($s,3,56));
			break;
		case 88:
			return substr($s,7,21) . substr($s,87,1) . substr($s,29,16) . substr($s,55,1) . substr($s,46,9) . substr($s,2,1) . substr($s,56,31) . substr($s,28,1);
			break;
		case 87:
			return substr($s,6,21) . substr($s,4,1) . substr($s,28,11) . substr($s,27,1) . substr($s,40,19) . substr($s,2,1) . substr($s,60,26);
			break; 
		case 86:
			return strrev(substr($s,72,8)) . substr($s,16,1) . strrev(substr($s,39,32)) . substr($s,72,1) . strrev(substr($s,16,22)) . substr($s,82,1) . strrev(subtr($s,0,15));
			break;
		case 85:
			return substr($s,3,8) . substr($s,0,1) . substr($s,12,43) . substr($s,84,1) . substr($s,56,28); 
			break;
		case 84:
			return strrev(substr($s,70,8)) . substr($s,14,1) . strrev(substr($s,37,32)) . substr($s,70,1) . strrev(substr($s,14,22)) . substr($s,80,1) . strrev(substr($s,0,14));
			break;
		case 83:
			return strrev(substr($s,63,17)) . substr($s,0,1) . strrev(substr($s,0,62)) . substr($s,63,1); 
			break;
		case 82:
			return strrev(substr($s,37,43)) . substr($s,7,1) . strrev(substr($s,7,29)) . substr($s,0,1) . strrev(substr($s,0,6)) . substr($s,37,1);
			break;
		case 81:
			return substr($s,56,1) . strrev(substr($s,57,23)) . substr($s,41,1) . strrev(substr($s,42,14)) . substr($s,80,1) . strrev(substr($s,35,6)) . substr($s,0,1) . strrev(substr($s,30,4)) . substr($s,34,1) . strrev(substr($s,10,19)) . substr($s,29,1) . strrev(substr($s,1,8)). substr($s,9,1); 
			break; 
		case 80:
			return substr($s,1,18) . substr($s,0,1) . substr($s,20,48) . substr($s,19,1) . substr($s,69,11);
			break;
		case 79:
			return substr($s,54,1) . strrev(substr($s,55,22)) . substr($s,39,1) . strrev(substr($s,40,13)) . substr($s,78,1) . strrev(substr($s,35,3)) . substr($s,0,1) . strrev(substr($s,30,3)) . substr($s,34,1) . strrev(substr($s,10,18)) . substr($s,29,1) . strrev(substr($s,1,7)) . substr($s,9,1);
			break;
	}
	/* original functions from youtube-dl	
	in python, single # = one character from index, two = start:end, three = start:end:step
	
        if len(s) == 93:
            return s[86:29:-1] + s[88] + s[28:5:-1]
        elif len(s) == 92:
            return s[25] + s[3:25] + s[0] + s[26:42] + s[79] + s[43:79] + s[91] + s[80:83]
        elif len(s) == 91:
            return s[84:27:-1] + s[86] + s[26:5:-1]
        elif len(s) == 90:
            return s[25] + s[3:25] + s[2] + s[26:40] + s[77] + s[41:77] + s[89] + s[78:81]
        elif len(s) == 89:
            return s[84:78:-1] + s[87] + s[77:60:-1] + s[0] + s[59:3:-1]
        elif len(s) == 88:
            return s[7:28] + s[87] + s[29:45] + s[55] + s[46:55] + s[2] + s[56:87] + s[28]
        elif len(s) == 87:
            return s[6:27] + s[4] + s[28:39] + s[27] + s[40:59] + s[2] + s[60:]
        elif len(s) == 86:
            return s[80:72:-1] + s[16] + s[71:39:-1] + s[72] + s[38:16:-1] + s[82] + s[15::-1]
        elif len(s) == 85:
            return s[3:11] + s[0] + s[12:55] + s[84] + s[56:84]
        elif len(s) == 84:
            return s[78:70:-1] + s[14] + s[69:37:-1] + s[70] + s[36:14:-1] + s[80] + s[:14][::-1]
        elif len(s) == 83:
            return s[80:63:-1] + s[0] + s[62:0:-1] + s[63]
        elif len(s) == 82:
            return s[80:37:-1] + s[7] + s[36:7:-1] + s[0] + s[6:0:-1] + s[37]
        elif len(s) == 81:
            return s[56] + s[79:56:-1] + s[41] + s[55:41:-1] + s[80] + s[40:34:-1] + s[0] + s[33:29:-1] + s[34] + s[28:9:-1] + s[29] + s[8:0:-1] + s[9]
        elif len(s) == 80:
            return s[1:19] + s[0] + s[20:68] + s[19] + s[69:80]
        elif len(s) == 79:
            return s[54] + s[77:54:-1] + s[39] + s[53:39:-1] + s[78] + s[38:34:-1] + s[0] + s[33:29:-1] + s[34] + s[28:9:-1] + s[29] + s[8:0:-1] + s[9]

        else:
            raise ExtractorError(u'Unable to decrypt signature, key length %d not supported; retrying might work' % (len(s)))
            }
*/ 
} 
?>
