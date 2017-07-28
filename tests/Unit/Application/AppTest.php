<?php

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
		$container = $this->createMock('\\YoutubeDownloader\\Container\\Container');

		$app = new App($container);

		$this->assertSame($container, $app->getContainer());
	}

	/**
	 * @test getVersion
	 */
	public function getVersion()
	{
		$container = $this->createMock('\\YoutubeDownloader\\Container\\Container');

		$app = new App($container);

		$this->assertSame('0.4-dev', $app->getVersion());
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

		$container = $this->createMock('\\YoutubeDownloader\\Container\\Container');
		$container->expects($this->once())
			->method('get')
			->with('controller_factory')
			->willReturn($factory);

		$app = new App($container);

		$app->runWithRoute('test');
	}
}
