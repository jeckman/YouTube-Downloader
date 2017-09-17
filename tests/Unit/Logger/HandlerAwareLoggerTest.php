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

use YoutubeDownloader\Logger\HandlerAwareLogger;
use YoutubeDownloader\Tests\Fixture\TestCase;

class HandlerAwareLoggerTest extends TestCase
{
	/**
	 * @test HandlerAwareLogger implements Logger
	 */
	public function implementsLogger()
	{
		$handler = $this->createMock(
			'\\YoutubeDownloader\\Logger\\Handler\\Handler'
		);
		$logger = new HandlerAwareLogger($handler);

		$this->assertInstanceOf('\\YoutubeDownloader\\Logger\\Logger', $logger);
	}

	/**
	 * @test all logger methods
	 *
	 * @dataProvider LoggerMethodsDataProvider
	 */
	public function loggerMethods($method, $message, array $context)
	{
		$handler = $this->createMock(
			'\\YoutubeDownloader\\Logger\\Handler\\Handler'
		);
		$handler->method('handles')->with($method)->willReturn(true);
		$handler->expects($this->once())->method('handle')->willReturn(null);

		$logger = new HandlerAwareLogger($handler);

		$this->assertNull($logger->$method($message, $context));
	}

	/**
	 * LoggerMethodsDataProvider
	 */
	public function LoggerMethodsDataProvider()
	{
		return [
			[
				'emergency',
				'Log of {description}',
				['description' => 'an emergency'],
			],
			[
				'alert',
				'Log of {description}',
				['description' => 'an alert'],
			],
			[
				'critical',
				'Log of {description}',
				['description' => 'critical'],
			],
			[
				'error',
				'Log of {description}',
				['description' => 'an error'],
			],
			[
				'warning',
				'Log of {description}',
				['description' => 'a warning'],
			],
			[
				'notice',
				'Log of {description}',
				['description' => 'a notice'],
			],
			[
				'info',
				'Log of {description}',
				['description' => 'an info message'],
			],
			[
				'debug',
				'Log of {description}',
				['description' => 'a debug message'],
			],
		];
	}
}
