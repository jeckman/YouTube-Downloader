<?php

namespace YoutubeDownloader\Tests\Unit;

use YoutubeDownloader\VideoInfo;

class VideoInfoTest extends \YoutubeDownloader\Tests\Fixture\TestCase
{
	/**
	 * @test createFromStringWithConfig()
	 */
	public function createFromStringWithConfig()
	{
		$config = $this->createMock('\\YoutubeDownloader\\Config');

		$this->assertInstanceOf(
			'\\YoutubeDownloader\\VideoInfo',
			VideoInfo::createFromStringWithConfig('', $config)
		);
	}

	/**
	 * @test getVideoId()
	 */
	public function getVideoId()
	{
		$config = $this->createMock('\\YoutubeDownloader\\Config');

		$video_info = VideoInfo::createFromStringWithConfig('video_id=123abc', $config);

		$this->assertSame('123abc', $video_info->getVideoId());
	}

	/**
	 * @test getStatus()
	 */
	public function getStatus()
	{
		$config = $this->createMock('\\YoutubeDownloader\\Config');

		$video_info = VideoInfo::createFromStringWithConfig('status=ok', $config);

		$this->assertSame('ok', $video_info->getStatus());
	}

	/**
	 * @test getErrorReason()
	 */
	public function getErrorReason()
	{
		$config = $this->createMock('\\YoutubeDownloader\\Config');

		$video_info = VideoInfo::createFromStringWithConfig('reason=This video is unavailable.', $config);

		$this->assertSame('This video is unavailable.', $video_info->getErrorReason());
	}

	/**
	 * @test getThumbnailUrl()
	 */
	public function getThumbnailUrl()
	{
		$config = $this->createMock('\\YoutubeDownloader\\Config');

		$video_info = VideoInfo::createFromStringWithConfig('thumbnail_url=http://example.com/image.jpg', $config);

		$this->assertSame('http://example.com/image.jpg', $video_info->getThumbnailUrl());
	}

	/**
	 * @test getTitle()
	 */
	public function getTitle()
	{
		$config = $this->createMock('\\YoutubeDownloader\\Config');

		$video_info = VideoInfo::createFromStringWithConfig('title=Foo bar', $config);

		$this->assertSame('Foo bar', $video_info->getTitle());
	}

	/**
	 * @test getCleanedTitle()
	 * @dataProvider CleanedTitleProvider
	 */
	public function getCleanedTitle($title, $expected)
	{
		$config = $this->createMock('\\YoutubeDownloader\\Config');

		$video_info = VideoInfo::createFromStringWithConfig('title=' . $title, $config);

		$this->assertSame($expected, $video_info->getCleanedTitle());
	}

	/**
	 * dataprovider for clean()
	 */
	public function CleanedTitleProvider()
	{
		return [
			['Replaces all spaces with hyphens.', 'Replaces-all-spaces-with-hyphens'],
			['Как делать бэкапы. Cobian Backup.', 'Cobian-Backup'], // Removes special chars.
		];
	}

	/**
	 * @test getFormats()
	 */
	public function getFormatsIsEmptyArray()
	{
		$config = $this->createMock('\\YoutubeDownloader\\Config');

		$video_info = VideoInfo::createFromStringWithConfig('url_encoded_fmt_stream_map=formats', $config);

		$this->assertSame([], $video_info->getFormats());
	}

	/**
	 * @test getAdaptiveFormats()
	 */
	public function getAdaptiveFormatsIsEmptyArray()
	{
		$config = $this->createMock('\\YoutubeDownloader\\Config');

		$video_info = VideoInfo::createFromStringWithConfig('adaptive_fmts=adaptive_formats', $config);

		$this->assertSame([], $video_info->getAdaptiveFormats());
	}
}
