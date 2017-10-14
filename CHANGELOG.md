# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- `YoutubeDownloader\Provider\Youtube\Provider::createFromOptions()` to create the Youtube Provider with an options array
- `YoutubeDownloader\Provider\Youtube\VideoInfo::createFromStringWithOptions()` to create the Youtube VideoInfo with an options array

### Changed

- The mp3 downloader was improved and has no dependendy to aria2 anymore

### Fixed

- A bug in the downlaoder with adaptive format was fixed
- The path to the yearly logs folder was fixed

### Deprecated

- `YoutubeDownloader\Provider\Youtube\Provider::createFromConfigAndToolkit()` will be removed in 0.7, use `YoutubeDownloader\Provider\Youtube\Provider::createFromOptions()` instead
- `YoutubeDownloader\Provider\Youtube\VideoInfo::createFromStringWithConfig()` will be removed in 0.7, use `YoutubeDownloader\Provider\Youtube\VideoInfo::createFromStringWithOptions()` instead
- `YoutubeDownloader\Toolkit::validateVideoId()` isn't used anymore and will be removed in 0.7
- `YoutubeDownloader\Toolkit::formatBytes()` isn't used anymore and will be removed in 0.7
- `YoutubeDownloader\Toolkit::getDownloadMP3()` isn't used anymore and will be removed in 0.7

### Removed

- **Breaking:** `YoutubeDownloader\Provider\Youtube\VideoInfo::setToCache()` was removed, use `YoutubeDownloader\Provider\Youtube\VideoInfo::getCache()->set()` instead
- **Breaking:** `YoutubeDownloader\Provider\Youtube\VideoInfo::getFromCache()` was removed, use `YoutubeDownloader\Provider\Youtube\VideoInfo::getCache()->get()` instead
- **Breaking:** `YoutubeDownloader\Container\SimpleContainer::set()` requires an optional Closure or a string as alias in second argument ($value)
- **Breaking:** `YoutubeDownloader\Toolkit::curlGet()` was removed, use `YoutubeDownloader\Http\CurlClient` instead
- **Breaking:** `YoutubeDownloader\Toolkit::get_size()` was removed, use `YoutubeDownloader\Http\CurlClient` instead
- **Breaking:** `YoutubeDownloader\Toolkit::getDownloadUrlByFormats()` was removed

## [0.5.1] - 2017-09-22

### Fixed

- A bug in the mp3 downloader was fixed

## [0.5] - 2017-09-15

### Added

- new `YoutubeDownloader\Http\CurlClient` as an implementation of the new `YoutubeDownloader\Http\Client` interface
- new `YoutubeDownloader\Http\HttpClientAware` interface
- `YoutubeDownloader\Provider\Youtube\Format` implements `YoutubeDownloader\Http\HttpClientAware` interface
- `YoutubeDownloader\Provider\Youtube\Provider` implements `YoutubeDownloader\Http\HttpClientAware` interface
- `YoutubeDownloader\Provider\Youtube\VideoInfo` implements `YoutubeDownloader\Http\HttpClientAware` interface
- new `YoutubeDownloader\Cache\CacheAware` interface
- `YoutubeDownloader\Provider\Youtube\Format` implements `YoutubeDownloader\Cache\CacheAware` interface
- `YoutubeDownloader\Provider\Youtube\Provider` implements `YoutubeDownloader\Cache\CacheAware` interface
- `YoutubeDownloader\Provider\Youtube\VideoInfo` implements `YoutubeDownloader\Cache\CacheAware` interface

### Deprecated

- `YoutubeDownloader\Provider\Youtube\VideoInfo::setToCache()` will be removed in 0.6, use `YoutubeDownloader\Provider\Youtube\VideoInfo::getCache()->set()` instead
- `YoutubeDownloader\Provider\Youtube\VideoInfo::getFromCache()` will be removed in 0.6, use `YoutubeDownloader\Provider\Youtube\VideoInfo::getCache()->get()` instead
- `YoutubeDownloader\Container\SimpleContainer::set()` will require an optional Closure or a string as alias in second argument ($value) in 0.6, provide a Closure or a string as alias in second argument ($value) instead
- `YoutubeDownloader\Toolkit::curlGet()` will be removed in 0.6, use `YoutubeDownloader\Http\CurlClient` instead
- `YoutubeDownloader\Toolkit::get_size()` will be removed in 0.6, use `YoutubeDownloader\Http\CurlClient` instead
- `YoutubeDownloader\Toolkit::getDownloadUrlByFormats()` isn't used anymore and will be removed in 0.6

### Removed

- **Breaking:** The `YoutubeDownloader\Format` class was removed, use the `YoutubeDownloader\Provider\Youtube\Format` class instead
- **Breaking:** The `YoutubeDownloader\SignatureDecipher` class was removed, use the `YoutubeDownloader\Provider\Youtube\SignatureDecipher` class instead
- **Breaking:** The `YoutubeDownloader\VideoInfo` class was removed, use the `YoutubeDownloader\Provider\Youtube\VideoInfo` class instead
- **Breaking:** `YoutubeDownloader\Toolkit::isMobileUrl()` isn't used anymore and was removed
- **Breaking:** `YoutubeDownloader\Toolkit::treatMobileUrl()` isn't used anymore and was removed

## [0.4] - 2017-08-30

### Added

- new PSR-3 compatible logger implementation `YoutubeDownloader\Logger\Logger` to log all kind of events
- new `YoutubeDownloader\VideoInfo\Provider` interface for describing an implementation how to get a `VideoInfo` for an input like a youtube url
- (a not so) new provider for downloading videos from Youtube
- `YoutubeDownloader\Container\SimpleContainer` has a new `logger` service with a `YoutubeDownloader\Logger\Logger` instance
- new folder `/logs` for log files
- `YoutubeDownloader\Format` implements `Logger\LoggerAware` interface
- `YoutubeDownloader\VideoInfo` implements `Logger\LoggerAware` interface
- `YoutubeDownloader\SignatureDecipher::decipherSignatureWithRawPlayerScript()` expects an optional logger as 3rd parameter

### Changed

- Logs are now stored in `/logs`, the file `Deciphers.log` can be deleted

### Deprecated

- The `YoutubeDownloader\Format` class will be removed in 0.5, use the `YoutubeDownloader\Provider\Youtube\Format` class instead
- The `YoutubeDownloader\SignatureDecipher` class will be removed in 0.5, use the `YoutubeDownloader\Provider\Youtube\SignatureDecipher` class instead
- The `YoutubeDownloader\VideoInfo` class will be removed in 0.5, use the `YoutubeDownloader\Provider\Youtube\VideoInfo` class instead
- `YoutubeDownloader\Toolkit::isMobileUrl()` isn't used anymore and will be removed in 0.5
- `YoutubeDownloader\Toolkit::treatMobileUrl()` isn't used anymore and will be removed in 0.5

### Removed

- **Breaking:** method `YoutubeDownloader\SignatureDecipher::downloadPlayerScript()` was removed, use `YoutubeDownloader\SignatureDecipher::downloadRawPlayerScript()` instead
- **Breaking:** method `YoutubeDownloader\SignatureDecipher::decipherSignature()` was removed, use `YoutubeDownloader\SignatureDecipher::decipherSignatureWithRawPlayerScript()` instead

## [0.3] - 2017-07-28

### Added

- new PSR-16 compatible cache implementation `Cache\FileCache` to store data in the filesystem
- `SignatureDecipher::getPlayerInfoByVideoId()` to get the player ID and player url of a cipher video
- `SignatureDecipher::downloadRawPlayerScript()` to download the raw player script of a cipher video
- `SignatureDecipher::decipherSignatureWithRawPlayerScript()` to decipher a signature with a raw dicipher script
- `VideoInfo::setCache()` to set a Cache implemantation
- `VideoInfo::getFromCache()` to get a value from the Cache implemantation
- `VideoInfo::setToCache()` to set a value to the Cache implemantation

### Changed

- the web UI now uses Bootstrap 3.3.7 and has been improved
- all cache files are now saved in folder `cache`, the `playerscript` folder can be removed

### Fixed

- an issue in `Format` was fixed, that led to wrong download sizes for some formats
- an issue in `ToolKit` was fixed, that led to an error with str_replace()

### Deprecated

- method `SignatureDecipher::downloadPlayerScript()` will be removed in release 0.4, use `SignatureDecipher::downloadRawPlayerScript()` instead
- method `SignatureDecipher::decipherSignature()` will be removed in release 0.4, use `SignatureDecipher::decipherSignatureWithRawPlayerScript()` instead

### Removed

- **Breaking:** class `Stream` was removed, use `Format` instead
- **Breaking:** class `StreamMap` was removed, use `VideoInfo::getFormats()` and `VideoInfo::getAdaptiveFormats()` instead
- **Breaking:** method `VideoInfo::createFromString()` was removed, use `VideoInfo::createFromStringWithConfig()` instead
- **Breaking:** method `VideoInfo::getStreamMapString()` was removed, use `VideoInfo::getFormats()` instead
- **Breaking:** method `VideoInfo::getAdaptiveFormatsString()` was removed, use `VideoInfo::getAdaptiveFormats()` instead

## [0.2] - 2017-07-21

### Added

- new configuration 'enable_youtube_decipher_signature' for automatically decipher a YouTube signature. Default is set to false.
- `Application\ControllerAbstract::responseWithErrorMessage()` to echo an error message and exit
- `VideoInfo::createFromStringWithConfig()` to pass a configuration while creating the VideoInfo
- `VideoInfo::getFormats()` to get the formats for a video
- `VideoInfo::getAdaptiveFormats()` to get the adaptive formats for a video

### Deprecated

- class `Stream` will be removed in release 0.3, use `Format` instead
- class `StreamMap` will be removed in release 0.3, use `VideoInfo::getFormats()` and `VideoInfo::getAdaptiveFormats()` instead
- method `VideoInfo::createFromString()` will be removed in release 0.3, use `VideoInfo::createFromStringWithConfig()` instead
- method `VideoInfo::getStreamMapString()` will be removed in release 0.3, use `VideoInfo::getFormats()` instead
- method `VideoInfo::getAdaptiveFormatsString()` will be removed in release 0.3, use `VideoInfo::getAdaptiveFormats()` instead

## [0.1] - 2017-07-19

### Added
- Simple library for using the functionality in other projects
- Web interface for downloading youtube videos

[Unreleased]: https://github.com/jeckman/YouTube-Downloader/compare/0.5.1...HEAD
[0.5.1]: https://github.com/jeckman/YouTube-Downloader/compare/0.5...0.5.1
[0.5]: https://github.com/jeckman/YouTube-Downloader/compare/0.4...0.5
[0.4]: https://github.com/jeckman/YouTube-Downloader/compare/0.3...0.4
[0.3]: https://github.com/jeckman/YouTube-Downloader/compare/0.2...0.3
[0.2]: https://github.com/jeckman/YouTube-Downloader/compare/0.1...0.2
[0.1]: https://github.com/jeckman/YouTube-Downloader/compare/7397d4101a96aa3cc28d211d9f23d5da34c3d9c8...0.1
