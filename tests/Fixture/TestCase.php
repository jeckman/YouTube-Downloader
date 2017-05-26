<?php

namespace YoutubeDownloader\Tests\Fixture;

class TestCase extends \PHPUnit\Framework\TestCase
{
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
}
