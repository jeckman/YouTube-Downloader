<?PHP
  // Thumbnail Image Configuration
  # $config['ThumbnailImageMode']=0;   // don't show thumbnail image
  # $config['ThumbnailImageMode']=1;   // show thumbnail image directly from YouTube
  $config['ThumbnailImageMode']=2;    // show thumbnail image by proxy from this server

  // Video Download Link Configuration
  #$config['VideoLinkMode']='direct'; // show only direct download link
  #$config['VideoLinkMode']='proxy'; // show only by proxy download link
  $config['VideoLinkMode']='both'; // show both direct and by proxy download links

  // Other features
  $config['feature']['browserExtensions']=true; // show links for install browser extensions? true or false
?>
