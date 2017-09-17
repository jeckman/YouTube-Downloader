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

namespace YoutubeDownloader\Tests\Unit\Logger;

use YoutubeDownloader\Logger\NullLogger;
use YoutubeDownloader\Tests\Fixture\TestCase;
use YoutubeDownloader\Tests\Fixture\Logger\Psr3LoggerAdapter;

class NullLoggerTest extends TestCase
{
	/**
	 * @test NullLogger implements Logger
	 */
	public function implementsLogger()
	{
		$logger = new NullLogger();

		$this->assertInstanceOf('\\YoutubeDownloader\\Logger\\Logger', $logger);
	}

	/**
	 * @test NullLogger is compatible with Psr\Log\LoggerInterface
	 */
	public function isPsr3Compatible()
	{
		$logger = new NullLogger();

		$adapter = new Psr3LoggerAdapter($logger);

		$this->assertInstanceOf('\\Psr\\Log\\LoggerInterface', $adapter);
		$this->assertInstanceOf('\\YoutubeDownloader\\Logger\\Logger', $adapter);
	}
}
