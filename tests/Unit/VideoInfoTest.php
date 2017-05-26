<?php

namespace YoutubeDownloader\Tests\Unit;

use YoutubeDownloader\VideoInfo;

class VideoInfoTest extends \YoutubeDownloader\Tests\Fixture\TestCase
{
	/**
	 * @test createFromString()
	 */
	public function createFromString()
	{
		$this->assertInstanceOf('YoutubeDownloader\VideoInfo', VideoInfo::createFromString(''));
	}

	/**
	 * @test getStatus()
	 */
	public function getStatus()
	{
		$video_info = VideoInfo::createFromString('status=ok');

		$this->assertSame('ok', $video_info->getStatus());
	}

	/**
	 * @test getErrorReason()
	 */
	public function getErrorReason()
	{
		$video_info = VideoInfo::createFromString('reason=This video is unavailable.');

		$this->assertSame('This video is unavailable.', $video_info->getErrorReason());
	}

	/**
	 * @test getThumbnailUrl()
	 */
	public function getThumbnailUrl()
	{
		$video_info = VideoInfo::createFromString('thumbnail_url=http://example.com/image.jpg');

		$this->assertSame('http://example.com/image.jpg', $video_info->getThumbnailUrl());
	}

	/**
	 * @test getTitle()
	 */
	public function getTitle()
	{
		$video_info = VideoInfo::createFromString('title=Foo bar');

		$this->assertSame('Foo bar', $video_info->getTitle());
	}

	/**
	 * @test getCleanedTitle()
	 * @dataProvider CleanedTitleProvider
	 */
	public function getCleanedTitle($title, $expected)
	{
		$video_info = VideoInfo::createFromString('title=' . $title);

		$this->assertSame($expected, $video_info->getCleanedTitle());
	}

	/**
	 * dataprovider for clean()
	 */
	public function CleanedTitleProvider()
	{
		return [
			['Replaces all spaces with hyphens.', 'Replaces-all-spaces-with-hyphens'],
			['Как делать бэкапы. Cobian Backup.', '---Cobian-Backup'], // Removes special chars.
		];
	}

	/**
	 * @test getStreamMapString()
	 */
	public function getStreamMapString()
	{
		$video_info = VideoInfo::createFromString('url_encoded_fmt_stream_map=stream_map');

		$this->assertSame('stream_map', $video_info->getStreamMapString());
	}

	/**
	 * @test getAdaptiveFormatsString()
	 */
	public function getAdaptiveFormatsString()
	{
		$video_info = VideoInfo::createFromString('adaptive_fmts=formats');

		$this->assertSame('formats', $video_info->getAdaptiveFormatsString());
	}
}
