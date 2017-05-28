<?php

namespace YoutubeDownloader\Tests\Unit;

use YoutubeDownloader\YoutubeDownloader;

class YoutubeDownloaderTest extends \YoutubeDownloader\Tests\Fixture\TestCase
{
	/**
	 * @test validateVideoId()
	 * @dataProvider VideoIdProvider
	 */
	public function validateVideoId($str, $expected)
	{
		$this->assertSame($expected, YoutubeDownloader::validateVideoId($str));
	}

	/**
	 * dataprovider for validateVideoId()
	 */
	public function VideoIdProvider()
	{
		$id = 'dQw4w9WgXcQ';

		return [
			[$id, $id],
			['http://youtu.be/dQw4w9WgXcQ', $id],
			['http://www.youtube.com/embed/dQw4w9WgXcQ', $id],
			['http://www.youtube.com/watch?v=dQw4w9WgXcQ', $id],
			['http://www.youtube.com/?v=dQw4w9WgXcQ', $id],
			['http://www.youtube.com/v/dQw4w9WgXcQ', $id],
			['http://www.youtube.com/e/dQw4w9WgXcQ', $id],
			['http://www.youtube.com/user/username#p/u/11/dQw4w9WgXcQ', $id],
			['http://www.youtube.com/sandalsResorts#p/c/54B8C800269D7C1B/0/dQw4w9WgXcQ', $id],
			['http://www.youtube.com/watch?feature=player_embedded&v=dQw4w9WgXcQ', $id],
			['http://www.youtube.com/?feature=player_embedded&v=dQw4w9WgXcQ', $id],
			['http://www.youtube.com/v/dQw4w9WgXcQ?fs=1&hl=en_US', $id],
			['https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ', $id],
			['<iframe width="560" height="315" src="https://www.youtube.com/embed/dQw4w9WgXcQ" frameborder="0" allowfullscreen></iframe>', $id],
		];
	}

	/**
	 * @test isMobileUrl()
	 * @dataProvider MobileUrlProvider
	 */
	public function isMobileUrl($str, $expected)
	{
		$this->assertSame($expected, YoutubeDownloader::isMobileUrl($str));
	}

	/**
	 * dataprovider for isMobileUrl()
	 */
	public function MobileUrlProvider()
	{
		return [
			['http://youtu.be/dQw4w9WgXcQ', false],
			['http://www.youtube.com/embed/dQw4w9WgXcQ', false],
			['http://www.youtube.com/watch?v=dQw4w9WgXcQ', false],
			['http://www.youtube.com/?v=dQw4w9WgXcQ', false],
			['http://www.youtube.com/v/dQw4w9WgXcQ', false],
			['http://www.youtube.com/e/dQw4w9WgXcQ', false],
			['http://www.youtube.com/user/username#p/u/11/dQw4w9WgXcQ', false],
			['http://www.youtube.com/sandalsResorts#p/c/54B8C800269D7C1B/0/dQw4w9WgXcQ', false],
			['http://www.youtube.com/watch?feature=player_embedded&v=dQw4w9WgXcQ', false],
			['http://www.youtube.com/?feature=player_embedded&v=dQw4w9WgXcQ', false],
			['http://www.youtube.com/v/dQw4w9WgXcQ?fs=1&hl=en_US', false],
			['https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ', false],
			['http://m.youtube.com/embed/dQw4w9WgXcQ', true],
			['http://m.youtube.com/watch?v=dQw4w9WgXcQ', true],
			['http://m.youtube.com/?v=dQw4w9WgXcQ', true],
			['http://m.youtube.com/v/dQw4w9WgXcQ', true],
			['http://m.youtube.com/e/dQw4w9WgXcQ', true],
			['http://m.youtube.com/user/username#p/u/11/dQw4w9WgXcQ', true],
			['http://m.youtube.com/sandalsResorts#p/c/54B8C800269D7C1B/0/dQw4w9WgXcQ', true],
			['http://m.youtube.com/watch?feature=player_embedded&v=dQw4w9WgXcQ', true],
			['http://m.youtube.com/?feature=player_embedded&v=dQw4w9WgXcQ', true],
			['http://m.youtube.com/v/dQw4w9WgXcQ?fs=1&hl=en_US', true],
			['https://m.youtube-nocookie.com/embed/dQw4w9WgXcQ', true],
		];
	}
}
