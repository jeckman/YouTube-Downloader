<?php

namespace YoutubeDownloader\Tests\Unit\Cache;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use YoutubeDownloader\Cache\FileCache;
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
}
