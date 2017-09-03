<?php

namespace YoutubeDownloader\Tests\Unit\Cache;

use YoutubeDownloader\Cache\NullCache;
use YoutubeDownloader\Tests\Fixture\Cache\DataProviderTrait;
use YoutubeDownloader\Tests\Fixture\TestCase;

class NullCacheTest extends TestCase
{
	use DataProviderTrait;

	/**
	 * @test get()
	 */
	public function getReturnsDefault()
	{
		$cache = new NullCache;

		$this->assertSame('default', $cache->get('key', 'default'));
	}

	/**
	 * @test get()
	 *
	 * @dataProvider InvalidKeyProvider
	 */
	public function getWithInvalidKeyThrowsException($invalid_key, $exception_name, $message)
	{
		$cache = new NullCache;

		$this->expectException($exception_name);
		$this->expectExceptionMessage($message);

		$cache->get($invalid_key);
	}

	/**
	 * @test set()
	 */
	public function setReturnsTrue()
	{
		$cache = new NullCache;

		$this->assertTrue($cache->set('key', 'foobar'));

		// Test that the setted value not returns
		$this->assertSame('default', $cache->get('key', 'default'));
	}

	/**
	 * @test set()
	 */
	public function setWithTtlReturnsTrue()
	{
		$cache = new NullCache;

		$this->assertTrue($cache->set('key', 'foobar', 3600));

		// Test that the setted value not returns
		$this->assertSame('default', $cache->get('key', 'default'));
	}

	/**
	 * @test set()
	 *
	 * @dataProvider InvalidKeyProvider
	 */
	public function setWithInvalidKeyThrowsException($invalid_key, $exception_name, $message)
	{
		$cache = new NullCache;

		$this->expectException($exception_name);
		$this->expectExceptionMessage($message);

		$cache->set($invalid_key, 'value');
	}

	/**
	 * @test delete()
	 */
	public function deleteReturnsTrue()
	{
		$cache = new NullCache;

		$this->assertTrue($cache->delete('key'));
	}

	/**
	 * @test delete()
	 *
	 * @dataProvider InvalidKeyProvider
	 */
	public function deleteWithInvalidKeyThrowsException($invalid_key, $exception_name, $message)
	{
		$cache = new NullCache;

		$this->expectException($exception_name);
		$this->expectExceptionMessage($message);

		$cache->delete($invalid_key);
	}
}
