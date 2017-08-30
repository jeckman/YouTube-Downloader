<?php

namespace YoutubeDownloader\Tests\Fixture\Cache;

/**
 * Trait that provides data for tests
 */
trait DataProviderTrait
{
	/**
	 * InvalidKeyProvider
	 */
	public function InvalidKeyProvider()
	{
		$exception_name = '\\YoutubeDownloader\\Cache\\InvalidArgumentException';

		return [
			[null, $exception_name, 'Cache key must be string, "NULL" given'],
			[true, $exception_name, 'Cache key must be string, "boolean" given'],
			[123, $exception_name, 'Cache key must be string, "integer" given'],
			[12.345, $exception_name, 'Cache key must be string, "double" given'],
			[['key'], $exception_name, 'Cache key must be string, "array" given'],
			[new \stdClass, $exception_name, 'Cache key must be string, "object" given'],
		];
	}
}
