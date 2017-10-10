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

namespace YoutubeDownloader\Tests\Unit\Application;

use Exception;
use YoutubeDownloader\Application\App;
use YoutubeDownloader\Application\Controller;
use YoutubeDownloader\Application\ControllerFactory;
use YoutubeDownloader\Container\Container;
use YoutubeDownloader\Logger\Logger;
use YoutubeDownloader\Tests\Fixture\TestCase;

class ControllerFactoryTest extends TestCase
{
	/**
	 * @test make
	 */
	public function make()
	{
		$logger = $this->createMock(Logger::class);

		$container = $this->createMock(Container::class);
		$container->method('get')->with('logger')->willReturn($logger);

		$app = $this->createMock(App::class);
		$app->method('getContainer')->willReturn($container);

		$factory = new ControllerFactory;

		$this->assertInstanceOf(
			Controller::class,
			$factory->make('index', $app)
		);
	}

	/**
	 * @test make throws Exception
	 */
	public function makeThrowsException()
	{
		$app = $this->createMock(App::class);

		$factory = new ControllerFactory;

		$this->expectException(Exception::class);
		$this->expectExceptionMessage('No controller was found for route "fail"');

		$this->assertInstanceOf(
			Controller::class,
			$factory->make('fail', $app)
		);
	}
}
