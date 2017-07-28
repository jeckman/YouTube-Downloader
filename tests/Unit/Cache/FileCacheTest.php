<?php

namespace YoutubeDownloader\Tests\Unit\Cache;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use YoutubeDownloader\Cache\FileCache;
use YoutubeDownloader\Tests\Fixture\Cache\Psr16CacheAdapter;
use YoutubeDownloader\Tests\Fixture\TestCase;

class FileCacheTest extends TestCase
{
	/**
	 * @test createFromDirectory()
	 */
	public function createFromDirectory()
	{
		$root = vfsStream::setup('cache');

		$this->assertInstanceOf(
			'\\YoutubeDownloader\\Cache\\FileCache',
			FileCache::createFromDirectory($root->url())
		);
	}

	/**
	 * @test FileCache is is compatible with Psr\SimpleCache\CacheInterface
	 */
	public function isPsr16Compatible()
	{
		$root = vfsStream::setup('cache');

		$cache = FileCache::createFromDirectory($root->url());

		$adapter = new Psr16CacheAdapter($cache);

		$this->assertInstanceOf('\\Psr\\SimpleCache\\CacheInterface', $adapter);
		$this->assertInstanceOf('\\YoutubeDownloader\\Cache\\Cache', $adapter);
	}

	/**
	 * @test createFromDirectory()
	 */
	public function createFromDirectoryThrowsExceptionIfFolderNotExists()
	{
		$root = vfsStream::setup('cache');

		$this->expectException('\\YoutubeDownloader\\Cache\\CacheException');
		$this->expectExceptionMessage('cache directory "vfs://not_existing" does not exist.');

		FileCache::createFromDirectory('vfs://not_existing');
	}

	/**
	 * @test createFromDirectory()
	 */
	public function createFromDirectoryThrowsExceptionIfFolderIsNotDirectory()
	{
		$root = vfsStream::setup('cache');
		vfsStream::newFile('file', 0000)->at($root);

		$this->expectException('\\YoutubeDownloader\\Cache\\CacheException');
		$this->expectExceptionMessage('cache directory "vfs://cache/file" is not a directory.');

		FileCache::createFromDirectory('vfs://cache/file');
	}

	/**
	 * @test createFromDirectory()
	 */
	public function createFromDirectoryThrowsExceptionIfFolderNotReadable()
	{
		$root = vfsStream::setup('cache', 0000);

		$this->expectException('\\YoutubeDownloader\\Cache\\CacheException');
		$this->expectExceptionMessage('cache directory "vfs://cache" is not readable.');

		FileCache::createFromDirectory($root->url());
	}

	/**
	 * @test createFromDirectory()
	 */
	public function createFromDirectoryThrowsExceptionIfFolderNotWritable()
	{
		$root = vfsStream::setup('cache', 0400);

		$this->expectException('\\YoutubeDownloader\\Cache\\CacheException');
		$this->expectExceptionMessage('cache directory "vfs://cache" is not writable.');

		FileCache::createFromDirectory($root->url());
	}

	/**
	 * @test get()
	 */
	public function getReturnsValue()
	{
		$root = vfsStream::setup('cache', 0600);
		vfsStream::newFile('key', 0600)
			->withContent(serialize([
				'foobar',
				null,
			]))
			->at($root);

		$cache = FileCache::createFromDirectory($root->url());

		$this->assertSame('foobar', $cache->get('key'));
	}

	/**
	 * @test get()
	 */
	public function getNotExistingReturnsDefault()
	{
		$root = vfsStream::setup('cache', 0600);

		$cache = FileCache::createFromDirectory($root->url());

		$this->assertSame('default', $cache->get('key', 'default'));
	}

	/**
	 * @test get()
	 */
	public function getNotUnserializableReturnsDefault()
	{
		$root = vfsStream::setup('cache', 0600);
		vfsStream::newFile('key', 0600)
			->withContent('foobar')
			->at($root);

		$cache = FileCache::createFromDirectory($root->url());

		$this->assertSame('default', $cache->get('key', 'default'));
	}

	/**
	 * @test get()
	 */
	public function getExpiredReturnsDefault()
	{
		$root = vfsStream::setup('cache', 0600);
		vfsStream::newFile('key', 0600)
			->withContent(serialize([
				'foobar',
				1,
			]))
			->at($root);

		$cache = FileCache::createFromDirectory($root->url());

		$this->assertSame('default', $cache->get('key', 'default'));

		// The expired cache should be deleted
		$this->assertFalse($root->hasChildren());
	}

	/**
	 * @test set()
	 */
	public function setReturnsTrue()
	{
		$root = vfsStream::setup('cache', 0600);

		$cache = FileCache::createFromDirectory(
			$root->url(),
			['writeFlags' => 0]
		);

		$this->assertTrue($cache->set('key', 'foobar'));

		$this->assertTrue($root->hasChild('key'));
		$this->assertSame(
			'a:2:{i:0;s:6:"foobar";i:1;N;}',
			$root->getChild('key')->getContent()
		);
	}

	/**
	 * @test set()
	 */
	public function setWithTtlReturnsTrue()
	{
		$root = vfsStream::setup('cache', 0600);

		$cache = FileCache::createFromDirectory(
			$root->url(),
			['writeFlags' => 0]
		);

		$this->assertTrue($cache->set('key', 'foobar', 3600));

		$this->assertTrue($root->hasChild('key'));
		$this->assertSame(
			sprintf('a:2:{i:0;s:6:"foobar";i:1;i:%s;}', time()+3600),
			$root->getChild('key')->getContent()
		);
	}

	/**
	 * @test delete()
	 */
	public function deleteReturnsTrue()
	{
		$root = vfsStream::setup('cache', 0600);
		vfsStream::newFile('key', 0600)
			->withContent(serialize([
				'foobar',
				null,
			]))
			->at($root);

		$cache = FileCache::createFromDirectory($root->url());

		$this->assertTrue($cache->delete('key'));
		$this->assertFalse($root->hasChildren());
	}
}
