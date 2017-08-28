<?php

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
