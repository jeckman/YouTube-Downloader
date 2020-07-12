# YouTube Downloader

[![Author](http://img.shields.io/badge/author-jeckman-blue.svg)](https://github.com/jeckman)
[![Source Code](http://img.shields.io/badge/source-jeckman/YouTubeDownloader-blue.svg)](https://github.com/jeckman/YouTube-Downloader)
[![Software License](https://img.shields.io/badge/license-GPL2-brightgreen.svg)](LICENSE)
[![Build Status](https://img.shields.io/travis/jeckman/YouTube-Downloader/master.svg)](https://travis-ci.org/jeckman/YouTube-Downloader)
[![Gitter](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/jeckman-YouTube-Downloader/Lobby)


# THIS PROJECT IS NO LONGER ACTIVELY DEVELOPED

PHP Library with Web UI to download videos from YouTube.

## Goals

- Create a library that delivers data and download links for youtube videos.
- Create a UI for downloading the videos.
- Have no external dependencies to other services.
- Have no external dependencies to other libraries in production.
- Installation should be simple (unzip on server and go)

To achieve this goals this project contains two parts:

1. A [semantic versioning 2 following](http://semver.org/spec/v2.0.0.html) PHP library that delivers data and download links for youtube videos.
2. A Web interface that uses this library

## Support for Cipher signature

YouTube Downloader supports YouTube videos with a cipher signature too. :tada: Please note that **this functionality is hidden behind a config flag** because it downloads JavaScript code from a 3rd party (YouTube) server and interprets it. This MAY harm your server, if the 3rd party server delivers malicious code.

You can **activate this by setting the `enable_youtube_decipher_signature` to `true`** in `/config/custom.php`. If the file don't exists you can simple create it or copy from `/config/default.php`.

```php
<?php
// in config/custom.php
return [
    'enable_youtube_decipher_signature' => true,
];
```

## Requirements

You must fit at least this requirements to use YouTube-Downloader:

- PHP >= 5.6
- Web server (Apache/Nginx/PHP built-in)

## Installation

There are multiple ways to set up YouTube-Downloader

### ZIP Download

1. Download the code for the newest release: https://github.com/jeckman/YouTube-Downloader/releases
2. Unzip the code to your web server
3. Open the folder with your browser

### Git

1. Clone the code on your server with

        git clone https://github.com/jeckman/YouTube-Downloader.git

2. Checkout the latest release tag with

        git checkout $(git describe --abbrev=0 --tags)

3. Open the folder with your browser

### Composer

You can use the PHP library in your project by installing the code via [Composer](https://getcomposer.org).

The library isn't available on packagist.org at the moment, so you must add the repository in your `composer.json` manually. Your `composer.json` should look like this

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

**Note:** Instead of using the `master` branch you should use a specific release like `"jeckman/YouTube-Downloader": "0.XX"`. You can found all releases [here](https://github.com/jeckman/YouTube-Downloader/releases).

## Usage

You can manually visit a web form (the index.php file), enter a YouTube
video id, and get in return a list of links showing the various formats in which
that video can be downloaded. You can simply choose "save link as" or the
equivalent to download the file.

Second, you can directly target the getvideo.php script, passing in a videoID and
preferred format, and you will get redirected to the file itself.

http://example.com/yt/getvideo.mp4?videoid=GkvvH8pBoTg&format=ipad

Potential formats:
- best = just give me the largest file / best quality
- free = give the largest version including WebM, lower priority to FLV
- ipad = ignore WebM and FLV, look for best MP4 file

You can also pass in a specific format number, if you know it.

Note this approach, because it redirects you to the file itself, currently bypasses the
proxy option, so if your browser/server setup requires the proxy to work these will fail.

### Feed subscription

You can subscribe both to YouTube channels and users via RSS. Feeds can be generated in
the formats listed above.

Generating a feed for a YouTube channel works as follows:

http://example.com/yt/feed.php?channelid=UChELZ_JMGNYuxObfrXoER6A&format=best

To generate a feed for a YouTube user:

http://example.com/yt/feed.php?user=heisenewsticker&format=free

The generated feed is a standard RSS feed and can be subscribed to in any feed reader.

## Upgrading

### ZIP Download

1. Backup your config file from `config/custom.php`.
2. Delete all files in the project folder
3. Download the newest release from https://github.com/jeckman/YouTube-Downloader/releases
4. Unzip the code to your project folder
5. Place your config file back to `config/custom.php`.

### Git

Fetch the master branch and checkout the latest annotated tag.

```shell
git remote update
git fetch origin master
git checkout $(git describe --abbrev=0 --tags master)
```

### Composer

Update your `composer.json` to use the latest version. Then run:

```shell
composer update
```

## Contributing

You can help making this project better by reporting bugs or submitting pull requests. Please see our [contributing guideline](https://github.com/jeckman/YouTube-Downloader/blob/master/CONTRIBUTING.md) for more information.
