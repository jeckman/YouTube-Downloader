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
