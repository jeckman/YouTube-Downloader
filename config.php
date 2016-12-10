<?PHP

  /**********|| Thumbnail Image Configuration ||***************/
  # $config['ThumbnailImageMode']=0;   // don't show thumbnail image
  # $config['ThumbnailImageMode']=1;   // show thumbnail image directly from YouTube
  $config['ThumbnailImageMode']=2;    // show thumbnail image by proxy from this server

  /**********|| Video Download Link Configuration ||***************/
  #$config['VideoLinkMode']='direct'; // show only direct download link
  #$config['VideoLinkMode']='proxy'; // show only by proxy download link
  $config['VideoLinkMode']='both'; // show both direct and by proxy download links

  /**********|| features ||***************/
  $config['feature']['browserExtensions']=true; // show links for install browser extensions? true or false
  
  /**********|| Other ||***************/
  // Set your default timezone
  // use this link: http://php.net/manual/en/timezones.php
  date_default_timezone_set("Asia/Tehran");
  
  // Debug mode
  #$debug=true; // debug mode on
  $debug=false; // debug mode off
  
  
  /**********|| Don't edit below ||***************/
  include_once('curl.php');
?>
