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

namespace YoutubeDownloader\Tests\Unit;

use YoutubeDownloader\Toolkit;

class ToolkitTest extends \YoutubeDownloader\Tests\Fixture\TestCase
{
	/**
	 * @test validateVideoId()
	 * @dataProvider VideoIdProvider
	 */
	public function validateVideoId($str, $expected)
	{
		$toolkit = new Toolkit;

		$this->assertSame($expected, $toolkit->validateVideoId($str));
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
}
