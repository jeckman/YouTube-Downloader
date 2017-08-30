# YouTube Downloader

[![Author](http://img.shields.io/badge/author-jeckman-blue.svg)](https://github.com/jeckman)
[![Source Code](http://img.shields.io/badge/source-jeckman/YouTubeDownloader-blue.svg)](https://github.com/jeckman/YouTube-Downloader)
[![Software License](https://img.shields.io/badge/license-GPL2-brightgreen.svg)](LICENSE)
[![Build Status](https://img.shields.io/travis/jeckman/YouTube-Downloader/master.svg)](https://travis-ci.org/jeckman/YouTube-Downloader)
[![Gitter](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/jeckman-YouTube-Downloader/Lobby)

PHP Library with Web UI to download videos from YouTube.

## Support for sipher signature

YouTube Downloader finally supports YouTube videos with a cipher signature too. :tada: Please note that this functionallity is hidden behind a config flag because it downloads javascript code from a 3rd party (YouTube) server and interprets it. This MAY harm your server, if the 3rd party server delivers malicious code.

You can activate this by the the `enable_youtube_decipher_signature` to `true` in `/config/custom.php`. If the file don't exists you can simple create it or copy from `/config/default.php`.

```php
<?php
// in config/custom.php
return [
    'enable_youtube_decipher_signature' => true,
];
```

## Goals

- Create a library that delivers data and download links for youtube videos.
- Create a UI for downloading the videos.
- Have no external dependencies to other services.
- Have no external dependencies to other libraries in production.
- Installation should be foolproof (unzip on server and go)

## Requirements

You must fit at least this requirements to use YouTube-Downloader:

- PHP >= 5.4
- Web server (Apache/Nginx/PHP built-in)

## Installation

There are multiple ways to set up YouTube-Downloader

### ZIP Download

- Download the code for the newest release: https://github.com/jeckman/YouTube-Downloader/releases
- Unzip the code to your web server
- Open the folder with your browser

### Git

- Clone the code on your server with `$ git clone https://github.com/jeckman/YouTube-Downloader.git`
- Open the folder with your browser

### Composer

The library code can be used in other projects via [Composer](https://getcomposer.org).

The code isn't available on packagist.org at the moment, so you must add the repository in your `composer.json`. Your `composer.json` should look like this

```
{
	"require": {
		"jeckman/YouTube-Downloader": "dev-master"
	},
	"repositories": [
		{"type": "vcs", "url": "https://github.com/jeckman/YouTube-Downloader"}
	]
}
```

Now install the dependencies with `$ composer update`

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

## Upgrading

### ZIP Download

- Backup your config file from `config/custom.php`.
- Delete all files in the project folder
- Download the newest release from https://github.com/jeckman/YouTube-Downloader/releases
- Unzip the code to your project folder
- Place your config file back to `config/custom.php`.

### Git

```
$ git remote update
$ git pull origin master
```
### Composer

```
$ composer update
```

## Contributing

You can help making this project better by reporting bugs or submitting pull requests. Please see our [contributing guideline](https://github.com/jeckman/YouTube-Downloader/blob/master/CONTRIBUTING.md) for more information.
