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
			new \DateTime('now'),
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
			'DateTime',
			$this->entry->getCreatedAt()
		);
	}
}
