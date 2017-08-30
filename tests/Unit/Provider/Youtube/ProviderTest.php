<?php

namespace YoutubeDownloader\Tests\Unit\Provider\Youtube;

use YoutubeDownloader\Provider\Youtube\Provider;

class ProviderTest extends \YoutubeDownloader\Tests\Fixture\TestCase
{
	/**
	 * @test provides()
	 * @dataProvider providesDataProvider
	 */
	public function isMobileUrl($str, $expected)
	{
		$config = $this->createMock('\\YoutubeDownloader\\Config');
		$toolkit = $this->createMock('\\YoutubeDownloader\\Toolkit');

		$provider = Provider::createFromConfigAndToolkit($config, $toolkit);

		$this->assertSame($expected, $provider->provides($expected));
	}

	/**
	 * dataprovider for provides()
	 */
	public function providesDataProvider()
	{
		return [
			['http://youtu.be/dQw4w9WgXcQ', true],
			['http://www.youtube.com/embed/dQw4w9WgXcQ', true],
			['http://www.youtube.com/watch?v=dQw4w9WgXcQ', true],
			['http://www.youtube.com/?v=dQw4w9WgXcQ', true],
			['http://www.youtube.com/v/dQw4w9WgXcQ', true],
			['http://www.youtube.com/e/dQw4w9WgXcQ', true],
			['http://www.youtube.com/user/username#p/u/11/dQw4w9WgXcQ', true],
			['http://www.youtube.com/sandalsResorts#p/c/54B8C800269D7C1B/0/dQw4w9WgXcQ', true],
			['http://www.youtube.com/watch?feature=player_embedded&v=dQw4w9WgXcQ', true],
			['http://www.youtube.com/?feature=player_embedded&v=dQw4w9WgXcQ', true],
			['http://www.youtube.com/v/dQw4w9WgXcQ?fs=1&hl=en_US', true],
			['https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ', true],
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
