# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [0.10] - 2018-12-20

### Fixed

- CurlClient respects curl options again

### Removed

- **Breaking:** `YoutubeDownloader\Http\CurlClient::createRequest()` was removed, use `YoutubeDownloader\Http\CurlClient::createFullRequest()` instead

## [0.9] - 2018-12-10

### Added

- make preparations for implementing PSR-17 and PSR-18
- new method `YoutubeDownloader\Http\CurlClient::createFullRequest()` to create a PSR-7 request instance with headers and body

### Fixed

- Set correct `Psr\Log\LoggerAwareInterface` into `YoutubeDownloader\Provider\Youtube\VideoInfo` in `YoutubeDownloader\Provider\Youtube\Provider`
- Set correct `Psr\Log\LoggerAwareInterface` into `YoutubeDownloader\Provider\Youtube\Format` in `YoutubeDownloader\Provider\Youtube\VideoInfo`

### Deprecated

- `YoutubeDownloader\Http\CurlClient::createRequest()` will be removed in 0.10, use `YoutubeDownloader\Http\CurlClient::createFullRequest()` instead

## [0.8] - 2018-12-06

### Added

- Add support for PSR-3 Logger Interface
- Add support for PSR-7 HTTP Message Interface
- Add support for PSR-11 Container Interface
- Add support for PSR-16 Simple Cache

### Changed

- **Breaking:** Argument 1 in `YoutubeDownloader\Application\App::__construct()` must be a `Psr\Container\ContainerInterface` instance instead of `YoutubeDownloader\Cache\Cache`
- **Breaking:** Argument 1 in `YoutubeDownloader\Cache\CacheAware::setCache()` must be a `Psr\SimpleCache\CacheInterface` instance instead of `YoutubeDownloader\Cache\Cache`
- **Breaking:** Argument 1 in `YoutubeDownloader\Cache\CacheAwareTrait::setCache()` must be a `Psr\SimpleCache\CacheInterface` instance instead of `YoutubeDownloader\Cache\Cache`
- **Breaking:** `YoutubeDownloader\Cache\CacheAwareTrait::getCache()` returns a `Psr\SimpleCache\CacheInterface` instance instead of `YoutubeDownloader\Cache\Cache`
- `YoutubeDownloader\Cache\CacheException` implements `Psr\SimpleCache\CacheException`
- `YoutubeDownloader\Cache\InvalidArgumentException` implements `Psr\SimpleCache\InvalidArgumentException`
- **Breaking:** `YoutubeDownloader\Cache\FileCache` implements `Psr\SimpleCache\CacheInterface` instead of `YoutubeDownloader\Cache\Cache`
- **Breaking:** `YoutubeDownloader\Cache\NullCache` implements `Psr\SimpleCache\CacheInterface` instead of `YoutubeDownloader\Cache\Cache`
- `YoutubeDownloader\Container\ContainerException` implements `Psr\Container\ContainerExceptionInterface`
- `YoutubeDownloader\Container\NotFoundException` implements `Psr\Container\NotFoundExceptionInterface`
- **Breaking:** `YoutubeDownloader\Container\SimpleContainer` implements `Psr\Container\ContainerInterface` instead of `YoutubeDownloader\Container\Container`
- **Breaking:** `YoutubeDownloader\Http\Client::createRequest()` returns `Psr\Http\Message\RequestInterface` instead of `YoutubeDownloader\Http\Message\Request`
- **Breaking:** Argument 1 in `YoutubeDownloader\Http\Client::send()` must be a `Psr\Http\Message\RequestInterface` instance instead of `YoutubeDownloader\Http\Message\Request`
- **Breaking:** `YoutubeDownloader\Http\Request` implements `Psr\Http\Message\RequestInterface` instead of `YoutubeDownloader\Http\Message\Request`
- **Breaking:** `YoutubeDownloader\Http\Response` implements `Psr\Http\Message\ResponseInterface` instead of `YoutubeDownloader\Http\Message\Response`
- **Breaking:** `YoutubeDownloader\Logger\HandlerAwareLogger` implements `Psr\Log\LoggerInterface` instead of `YoutubeDownloader\Logger\Logger`
- **Breaking:** `YoutubeDownloader\Logger\LoggerAwareTrait::getLogger()` returns a `Psr\Log\LoggerInterface` instance instead of `YoutubeDownloader\Logger\Logger`
- **Breaking:** Argument 1 in `YoutubeDownloader\Logger\LoggerAwareTrait::setLogger()` must be a `Psr\Log\LoggerInterface` instance instead of `YoutubeDownloader\Logger\Logger`
- **Breaking:** `YoutubeDownloader\Provider\Youtube\Format` implements `Psr\Log\LoggerAwareInterface` instance instead of `YoutubeDownloader\Logger\LoggerAware`
- **Breaking:** `YoutubeDownloader\Provider\Youtube\Provider` implements `Psr\Log\LoggerAwareInterface` instance instead of `YoutubeDownloader\Logger\LoggerAware`
- **Breaking:** Argument 2 in `YoutubeDownloader\Provider\Youtube\SignatureDecipher::extractDecipherOpcode()` must be a `Psr\Log\LoggerInterface` instance instead of `YoutubeDownloader\Logger\Logger`
- **Breaking:** Argument 4 in `YoutubeDownloader\Provider\Youtube\SignatureDecipher::executeSignaturePattern()` must be a `Psr\Log\LoggerInterface` instance instead of `YoutubeDownloader\Logger\Logger`
- **Breaking:** `YoutubeDownloader\Provider\Youtube\VideoInfo` implements `Psr\Log\LoggerAwareInterface` instance instead of `YoutubeDownloader\Logger\LoggerAware`

### Removed

- **Breaking:** `YoutubeDownloader\Cache\Cache` interface was removed, use `Psr\SimpleCache\CacheInterface` instead
- **Breaking:** `YoutubeDownloader\Container\Container` interface was removed, use `Psr\Container\ContainerInterface` instead
- **Breaking:** `YoutubeDownloader\Http\Message\Message` interface was removed, use `Psr\Http\Message\MessageInterface` instead
- **Breaking:** `YoutubeDownloader\Http\Message\Request` interface was removed, use `Psr\Http\Message\RequestInterface` instead
- **Breaking:** `YoutubeDownloader\Http\Message\Response` interface was removed, use `Psr\Http\Message\ResponseInterface` instead
- **Breaking:** `YoutubeDownloader\Http\Message\ServerRequest` interface was removed, use `Psr\Http\Message\ServerRequestInterface` instead
- **Breaking:** `YoutubeDownloader\Http\MessageTrait::getBodyAsString()` was removed
- **Breaking:** `YoutubeDownloader\Http\MessageTrait::withStringAsBody()` was removed
- **Breaking:** `YoutubeDownloader\Logger\Logger` interface was removed, use `Psr\Log\LoggerInterface` instead
- **Breaking:** `YoutubeDownloader\Logger\LoggerAware` interface was removed, use `Psr\Log\LoggerAwareInterface` instead
- **Breaking:** `YoutubeDownloader\Logger\LogLevel` was removed , use `Psr\Log\LogLevel` instead
- **Breaking:** `YoutubeDownloader\Logger\NullLogger` was removed , use `Psr\Log\NullLogger` instead
- **Breaking:** `YoutubeDownloader\Provider\Youtube\SignatureDecipher::decipherSignatureWithRawPlayerScript()` isn't used anymore and was removed

## [0.7] - 2018-11-30

### Added

- new dictionary for `YoutubeDownloader\Provider\Youtube\SignatureDecipher` to handle the YouTube signature change
- `composer.lock` as it is needed by some services
- new method `YoutubeDownloader\Provider\Youtube\SignatureDecipher::extractDecipherOpcode()` for extracting the decipher operation codes
- new method `YoutubeDownloader\Provider\Youtube\VideoInfo::getDuration()` to get the video duration
- Autofocus on video search input

### Fixed

- Non-latin letters in the title of downloaded files won't be remove anymore
- Some bugs fixed in decipher dictionary

### Deprecated

- `YoutubeDownloader\Provider\Youtube\SignatureDecipher::decipherSignatureWithRawPlayerScript()` isn't used anymore and will be removed in 0.8

### Removed

- **Breaking:** `YoutubeDownloader\Provider\Youtube\Provider::createFromConfigAndToolkit()` was removed, use `YoutubeDownloader\Provider\Youtube\Provider::createFromOptions()` instead
- **Breaking:** `YoutubeDownloader\Provider\Youtube\VideoInfo::createFromStringWithConfig()` was removed, use `YoutubeDownloader\Provider\Youtube\VideoInfo::createFromStringWithOptions()` instead
- **Breaking:** `YoutubeDownloader\Toolkit::validateVideoId()` isn't used anymore and was removed
- **Breaking:** `YoutubeDownloader\Toolkit::formatBytes()` isn't used anymore and was removed
- **Breaking:** `YoutubeDownloader\Toolkit::is_chrome()` isn't used anymore and was removed
- **Breaking:** `YoutubeDownloader\Toolkit::getDownloadMP3()` isn't used anymore and was removed

## [0.6] - 2018-01-02

### Added

- New support for creation of RSS feeds from YouTube channels and user pages
- `YoutubeDownloader\Provider\Youtube\Provider::createFromOptions()` to create the Youtube Provider with an options array
- `YoutubeDownloader\Provider\Youtube\VideoInfo::createFromStringWithOptions()` to create the Youtube VideoInfo with an options array

### Changed

- Support for PHP 5.4 and 5.5 was dropped
- The mp3 downloader was improved and has no dependendy to aria2 anymore
- Code Style was changed to PSR-2

### Fixed

- A bug in the downloader with adaptive format was fixed
- The path to the yearly logs folder was fixed

### Deprecated

- `YoutubeDownloader\Provider\Youtube\Provider::createFromConfigAndToolkit()` will be removed in 0.7, use `YoutubeDownloader\Provider\Youtube\Provider::createFromOptions()` instead
- `YoutubeDownloader\Provider\Youtube\VideoInfo::createFromStringWithConfig()` will be removed in 0.7, use `YoutubeDownloader\Provider\Youtube\VideoInfo::createFromStringWithOptions()` instead
- `YoutubeDownloader\Toolkit::validateVideoId()` isn't used anymore and will be removed in 0.7
- `YoutubeDownloader\Toolkit::formatBytes()` isn't used anymore and will be removed in 0.7
- `YoutubeDownloader\Toolkit::is_chrome()` isn't used anymore and will be removed in 0.7
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

[Unreleased]: https://github.com/jeckman/YouTube-Downloader/compare/0.10...HEAD
[0.10]: https://github.com/jeckman/YouTube-Downloader/compare/0.9...0.10
[0.9]: https://github.com/jeckman/YouTube-Downloader/compare/0.8...0.9
[0.8]: https://github.com/jeckman/YouTube-Downloader/compare/0.7...0.8
[0.7]: https://github.com/jeckman/YouTube-Downloader/compare/0.6...0.7
[0.6]: https://github.com/jeckman/YouTube-Downloader/compare/0.5.1...0.6
[0.5.1]: https://github.com/jeckman/YouTube-Downloader/compare/0.5...0.5.1
[0.5]: https://github.com/jeckman/YouTube-Downloader/compare/0.4...0.5
[0.4]: https://github.com/jeckman/YouTube-Downloader/compare/0.3...0.4
[0.3]: https://github.com/jeckman/YouTube-Downloader/compare/0.2...0.3
[0.2]: https://github.com/jeckman/YouTube-Downloader/compare/0.1...0.2
[0.1]: https://github.com/jeckman/YouTube-Downloader/compare/7397d4101a96aa3cc28d211d9f23d5da34c3d9c8...0.1
