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

First, you can manually visit a web form (the index.php file), enter a YouTube
video id, and get in return a list of links showing the various formats in which
that video can be downloaded. You can simply choose "save link as" or the 
equivalent to download the file. 

Second, you can configure feed_parser.php with the details of a specific YouTube
feed - for example, all videos by a given username - and it will return an xml
doc usable in a podcatcher, with the links to the videos turned into proper xml
enclosure links. (I use this with Downcast app on an iPad - your mileage may 
vary if you use other podcatchers - in particular iTunes is quite picky about 
what it considers valid feeds). 

Once feed_parser.php is working well when called directly from a web browser,
configure a cron job to output the rss to a file:

For example, my crontab has this line:

* 3 * * * /usr/bin/php /var/www/example.com/feed_parser.php > /var/www/example.com/feed.xml

(Your mileage will vary, as you'll need to set the right path to your php
file and the right path to the directory where it can write). 

Then configure podcatcher clients to point at the feed.xml

Set the cronjob to the desired frequency - every time it runs, it will
overwrite the feed.xml with new content. 
  
Enjoy!

John
