<?PHP
include_once('curl.php');

$my_id=$_GET["videoid"];
if(isset($_REQUEST['videoid'])) {
        $my_id = $_REQUEST['videoid'];
        if(strlen($my_id)>11){
                $url = parse_url($my_id);
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
/* First get the video info page for this video id */
$my_video_info = 'http://www.youtube.com/get_video_info?&video_id='. $my_id;
$my_video_info = curlGet($my_video_info);

/* TODO: Check return from curl for status code */

$thumbnail_url = $title = $url_encoded_fmt_stream_map = $type = $url = '';

parse_str($my_video_info);

header("Content-Type: image/jpeg");
readfile($thumbnail_url);

?>
