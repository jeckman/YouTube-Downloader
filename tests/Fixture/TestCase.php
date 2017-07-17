<?php

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
