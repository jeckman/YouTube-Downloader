<?php

namespace YoutubeDownloader\Tests\Unit\Logger\Handler;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use YoutubeDownloader\Logger\Handler\StreamHandler;
use YoutubeDownloader\Tests\Fixture\TestCase;

class StreamHandlerTest extends TestCase
{
	/**
	 * @test StreamHandler implements Handler
	 */
	public function implementsHandler()
	{
		$root = vfsStream::setup('logs');
		vfsStream::newFile('test.log', 0600)->at($root);

		$stream = fopen('vfs://logs/test.log', 'a+');

		$handler = new StreamHandler($stream, []);

		$this->assertInstanceOf(
			'\\YoutubeDownloader\\Logger\\Handler\\Handler',
			$handler
		);
	}

	/**
	 * @test Exception if stream is not writable
	 */
	public function expectExceptionWithoutResource()
	{
		$stream = new \stdClass;

		$this->expectException('\\Exception');
		$this->expectExceptionMessage('Parameter 1 must be a resource');

		$handler = new StreamHandler($stream, []);
	}

	/**
	 * @test Exception if stream is not writable
	 */
	public function expectExceptionWithNotWritableStream()
	{
		$root = vfsStream::setup('logs');
		vfsStream::newFile('test.log', 0600)->at($root);

		$stream = fopen('vfs://logs/test.log', 'r');

		$this->expectException('\\Exception');
		$this->expectExceptionMessage('The resource must be writable.');

		$handler = new StreamHandler($stream, []);
	}

	/**
	 * @test save entry into stream
	 */
	public function handleEntry()
	{
		$root = vfsStream::setup('logs');
		vfsStream::newFile('test.log', 0600)->at($root);

		$stream = fopen('vfs://logs/test.log', 'a+');

		$handler = new StreamHandler($stream, ['debug']);

		$entry = $this->createMock('\\YoutubeDownloader\\Logger\\Handler\\Entry');
		$entry->method('getMessage')->willReturn('Log with {message}.');
		$entry->method('getContext')->willReturn(['message' => 'a debug message']);
		$entry->method('getLevel')->willReturn('debug');
		$entry->method('getCreatedAt')->willReturn(
			new \DateTimeImmutable('2017-08-22 16:20:40', new \DateTimeZone('UTC'))
		);

		$this->assertTrue($handler->handles('debug'));

		$handler->handle($entry);

		$this->assertSame(
			"[2017-08-22T16:20:40+00:00] debug: Log with a debug message.\n",
			$root->getChild('test.log')->getContent()
		);
	}
}
