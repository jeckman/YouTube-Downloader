<?php

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
