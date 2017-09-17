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

namespace YoutubeDownloader\Tests\Fixture;

class TestCase extends \PHPUnit\Framework\TestCase
{
	private $lastExpectedException;

	/**
	 * Returns a test double for the specified class.
	 *
	 * Shim for PHPUnit 4
	 *
	 * @param string $originalClassName
	 * @return PHPUnit_Framework_MockObject_MockObject
	 * @throws Exception
	 */
	protected function createMock($originalClassName)
	{
		if (is_callable('parent::createMock'))
		{
			return parent::createMock($originalClassName);
		}

		return $this->getMockBuilder($originalClassName)
			->disableOriginalConstructor()
			->disableOriginalClone()
			->disableArgumentCloning()
			->getMock();
	}

	/**
	 *
	 * Shim for PHPUnit 4
	 * @param string $exception
	 */
	public function expectException($exception)
	{
		if (is_callable('parent::expectException'))
		{
			return parent::expectException($exception);
		}

		// Cache the exception name
		$this->lastExpectedException = $exception;
	}

	/**
	 * Shim for PHPUnit 4
	 *
	 * @param string $message
	 *
	 * @throws Exception
	 */
	public function expectExceptionMessage($message)
	{
		if (is_callable('parent::expectExceptionMessage'))
		{
			return parent::expectExceptionMessage($message);
		}

		if ( $this->lastExpectedException === null )
		{
			$this->lastExpectedException = '\\Exception';
		}

		$this->setExpectedException(
			$this->lastExpectedException,
			$message
		);

		$this->expectedExceptionMessage = null;
	}
}
