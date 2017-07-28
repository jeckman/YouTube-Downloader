# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Removed

- **Breaking** method `SignatureDecipher::downloadPlayerScript()` was removed, use `SignatureDecipher::downloadRawPlayerScript()` instead
- **Breaking** method `SignatureDecipher::decipherSignature()` was removed, use `SignatureDecipher::decipherSignatureWithRawPlayerScript()` instead

## [0.3] - 2017-07-28

### Added

- new PSR-16 compatible cache implemantation `Cache\FileCache` to store data in the filesystem
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

- **Breaking** class `Stream` was removed, use `Format` instead
- **Breaking** class `StreamMap` was removed, use `VideoInfo::getFormats()` and `VideoInfo::getAdaptiveFormats()` instead
- **Breaking** method `VideoInfo::createFromString()` was removed, use `VideoInfo::createFromStringWithConfig()` instead
- **Breaking** method `VideoInfo::getStreamMapString()` was removed, use `VideoInfo::getFormats()` instead
- **Breaking** method `VideoInfo::getAdaptiveFormatsString()` was removed, use `VideoInfo::getAdaptiveFormats()` instead

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

[Unreleased]: https://github.com/jeckman/YouTube-Downloader/compare/0.3...HEAD
[0.3]: https://github.com/jeckman/YouTube-Downloader/compare/0.2...0.3
[0.2]: https://github.com/jeckman/YouTube-Downloader/compare/0.1...0.2
[0.1]: https://github.com/jeckman/YouTube-Downloader/compare/7397d4101a96aa3cc28d211d9f23d5da34c3d9c8...0.1
