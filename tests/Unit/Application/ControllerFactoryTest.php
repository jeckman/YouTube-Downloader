<?php

namespace YoutubeDownloader\Tests\Unit\Application;

use YoutubeDownloader\Application\ControllerFactory;
use YoutubeDownloader\Tests\Fixture\TestCase;

class ControllerFactoryTest extends TestCase
{
	/**
	 * @test make
	 */
	public function make()
	{
		$app = $this->createMock('\\YoutubeDownloader\\Application\\App');

		$factory = new ControllerFactory;

		$this->assertInstanceOf(
			'\\YoutubeDownloader\\Application\\Controller',
			$factory->make('index', $app)
		);
	}

	/**
	 * @test make throws Exception
	 */
	public function makeThrowsException()
	{
		$app = $this->createMock('\\YoutubeDownloader\\Application\\App');

		$factory = new ControllerFactory;

		$this->expectException('\\Exception');
		$this->expectExceptionMessage('No controller was found for route "fail"');

		$this->assertInstanceOf(
			'\\YoutubeDownloader\\Application\\Controller',
			$factory->make('fail', $app)
		);
	}
}
