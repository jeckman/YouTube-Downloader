<?php

/*
 * PHP script for downloading videos from youtube
 * Copyright (C) 2012-2017  John Eckman
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, see <http://www.gnu.org/licenses/>.
 */

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
		$provider = Provider::createFromOptions([]);

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
