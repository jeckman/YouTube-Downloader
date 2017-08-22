<?php

namespace YoutubeDownloader\Tests\Unit\Logger\Handler;

use YoutubeDownloader\Logger\Handler\SimpleEntry;
use YoutubeDownloader\Tests\Fixture\TestCase;

class SimpleEntryTest extends TestCase
{
	private $entry;

	/**
	 * Create a entry
	 */
	public function setUp()
	{
		$this->entry = new SimpleEntry(
			new \DateTimeImmutable('now'),
			'debug',
			'Log of {description}',
			['description' => 'a debug message']
		);
	}

	/**
	 * @test SimpleEntry implements Entry
	 */
	public function implementsEntry()
	{
		$this->assertInstanceOf(
			'\\YoutubeDownloader\\Logger\\Handler\\Entry',
			$this->entry
		);
	}

	/**
	 * @test getMessage
	 */
	public function getMessage()
	{
		$this->assertSame(
			'Log of {description}',
			$this->entry->getMessage()
		);
	}

	/**
	 * @test getContext
	 */
	public function getContext()
	{
		$this->assertSame(
			['description' => 'a debug message'],
			$this->entry->getContext()
		);
	}

	/**
	 * @test getLevel
	 */
	public function getLevel()
	{
		$this->assertSame(
			'debug',
			$this->entry->getLevel()
		);
	}

	/**
	 * @test getCreatedAt
	 */
	public function getCreatedAt()
	{
		$this->assertInstanceOf(
			'DateTimeImmutable',
			$this->entry->getCreatedAt()
		);
	}
}
