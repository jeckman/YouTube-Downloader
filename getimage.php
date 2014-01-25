<?PHP
include_once('config.php');

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

$thumbnail_url="http://i1.ytimg.com/vi/".$my_id."/default.jpg"; // make image link
header("Content-Type: image/jpeg"); // set headers
readfile($thumbnail_url); // show image

?>
