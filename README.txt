Project: YouTube Downloader
Author: John Eckman
URL: https://github.com/jeckman/YouTube-Downloader
License: GPL v2 or Later

PHP Scripts to download videos from YouTube.  

In order for direct download links to work (clicking on the file type in results list)
your server must be using IPv6. If you are using IPv4, YouTube checks to see if the token
matches the IP and returns an access forbidden error. If you are unable to use IPv6, you
can use the "download" link instead, which uses a simple proxy to open the file from
the server and read the file out to the browser. 

You can manually visit a web form (the index.php file), enter a YouTube
video id, and get in return a list of links showing the various formats in which
that video can be downloaded. You can simply choose "save link as" or the 
equivalent to download the file. 

Second, you can directly target the getvideo.php script, passing in a videoID and
preferred format, and you will get redirected to the file itself. 

http://example.com/yt/getvideo.mp4?videoid=GkvvH8pBoTg&format=ipad

Potential formats:
 * best = just give me the largest file / best quality
 * free = give the largest version including WebM, lower priority to FLV
 * ipad = ignore WebM and FLV, look for best MP4 file

You can also pass in a specific format number, if you know it. 

Note this approach, because it redirects you to the file itself, currently bypasses the
proxy option, so if your browser/server setup requires the proxy to work these will fail. 
  
Enjoy!

John
