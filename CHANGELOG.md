# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [0.2] - 2017-07-21

### Added

- new configuration 'enable_youtube_decipher_signature' for automatically decipher a YouTube signature. Default is set to false.
- `Application\ControllerAbstract::responseWithErrorMessage()` to echo an error message and exit
- `VideoInfo::createFromStringWithConfig()` to pass a configuration while creating the VideoInfo
- `VideoInfo::getFormats()` to get the formats for a video
- `VideoInfo::getAdaptiveFormats()` to get the adaptive formats for a video

### Deprecated

- class `StreamMap` will be removed in release 0.3, use `VideoInfo::getFormats()` and `VideoInfo::getAdaptiveFormats()` instead
- class `Stream` will be removed in release 0.3, use `Format` instead
- method `VideoInfo::createFromString()` will be removed in release 0.3, use `VideoInfo::createFromStringWithConfig()` instead
- method `VideoInfo::getStreamMapString()` will be removed in release 0.3, use `VideoInfo::getFormats()` instead
- method `VideoInfo::getAdaptiveFormatsString()` will be removed in release 0.3, use `VideoInfo::getAdaptiveFormats()` instead

## [0.1] - 2017-07-19

### Added
- Simple library for using the functionality in other projects
- Web interface for downloading youtube videos

[Unreleased]: https://github.com/jeckman/YouTube-Downloader/compare/0.2...HEAD
[0.2]: https://github.com/jeckman/YouTube-Downloader/compare/0.1...0.2
[0.1]: https://github.com/jeckman/YouTube-Downloader/compare/7397d4101a96aa3cc28d211d9f23d5da34c3d9c8...0.1
