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

use YoutubeDownloader\Application\App;
use YoutubeDownloader\Container\Container;
use YoutubeDownloader\Tests\Fixture\TestCase;

class AppTest extends TestCase
{
	/**
	 * @test getContainer
	 */
	public function getContainer()
	{
		$logger = $this->createMock('\\YoutubeDownloader\\Logger\\Logger');

		$container = $this->createMock('\\YoutubeDownloader\\Container\\Container');
		$container->method('get')->with('logger')->willReturn($logger);

		$app = new App($container);

		$this->assertSame($container, $app->getContainer());
	}

	/**
	 * @test getVersion
	 */
	public function getVersion()
	{
		$logger = $this->createMock('\\YoutubeDownloader\\Logger\\Logger');

		$container = $this->createMock('\\YoutubeDownloader\\Container\\Container');
		$container->method('get')->with('logger')->willReturn($logger);

		$app = new App($container);

		$this->assertSame('0.6-dev', $app->getVersion());
	}

	/**
	 * @test runWithRoute
	 */
	public function runWithRoute()
	{
		$controller = $this->createMock(
			'\\YoutubeDownloader\\Application\\Controller'
		);
		$controller->expects($this->once())->method('execute');

		$factory = $this->createMock(
			'\\YoutubeDownloader\\Application\\ControllerFactory'
		);
		$factory->expects($this->once())
			->method('make')
			->willReturn($controller);

		$logger = $this->createMock('\\YoutubeDownloader\\Logger\\Logger');

		$container = $this->createMock('\\YoutubeDownloader\\Container\\Container');
		$container->method('get')->will($this->returnValueMap([
			['controller_factory', $factory],
			['logger', $logger],
		]));

		$app = new App($container);

		$app->runWithRoute('test');
	}
}
