# YouTube Downloader

[![Author](http://img.shields.io/badge/author-jeckman-blue.svg)](https://github.com/jeckman)
[![Source Code](http://img.shields.io/badge/source-jeckman/YouTubeDownloader-blue.svg)](https://github.com/jeckman/YouTube-Downloader)
[![Software License](https://img.shields.io/badge/license-GPL2-brightgreen.svg)](LICENSE)
[![Build Status](https://img.shields.io/travis/jeckman/YouTube-Downloader/master.svg)](https://travis-ci.org/jeckman/YouTube-Downloader)
[![Gitter](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/jeckman/YouTube-Downloader)

PHP Scripts to download videos from YouTube.  

NOTE: YouTube Downloader does not work with videos using a cipher signature.

See https://github.com/jeckman/YouTube-Downloader/issues/9

## Usage

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
