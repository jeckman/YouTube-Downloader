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

namespace YoutubeDownloader\Cache;

use DateTimeInterface;

/**
 * A cache instance that does nothing
 */

class NullCache implements Cache
{
	/**
	 * Fetches a value from the cache.
	 *
	 * @param string $key The unique key of this item in the cache.
	 * @param mixed  $default Default value to return if the key does not exist.
	 *
	 * @return mixed The value of the item from the cache, or $default in case of cache miss.
	 *
	 * @throws InvalidArgumentException
	 *   MUST be thrown if the $key string is not a legal value.
	 */
	public function get($key, $default = null)
	{
		$this->validateKey($key);

		return $default;
	}

	/**
	 * Persists data in the cache, uniquely referenced by a key with an optional expiration TTL time.
	 *
	 * @param string $key   The key of the item to store.
	 * @param mixed $value The value of the item to store, must be serializable.
	 * @param null|int|DateInterval $ttl Optional. The TTL value of this item. If no value is sent and
	 * the driver supports TTL then the library may set a default value
	 * for it or let the driver take care of that.
	 *
	 * @return bool True on success and false on failure.
	 *
	 * @throws InvalidArgumentException
	 *   MUST be thrown if the $key string is not a legal value.
	 */
	public function set($key, $value, $ttl = null)
	{
		$this->validateKey($key);

		return true;
	}

	/**
	 * Delete an item from the cache by its unique key.
	 *
	 * @param string $key The unique cache key of the item to delete.
	 *
	 * @return bool True if the item was successfully removed. False if there was an error.
	 *
	 * @throws InvalidArgumentException
	 *   MUST be thrown if the $key string is not a legal value.
	 */
	public function delete($key)
	{
		$this->validateKey($key);

		return true;
	}

	/**
	 * @param string $key
	 *
	 * @throws InvalidArgumentException
	 */
	private function validateKey($key)
	{
		if ( ! is_string($key) )
		{
			throw new InvalidArgumentException(
				sprintf('Cache key must be string, "%s" given', gettype($key))
			);
		}

		if ( ! isset($key[0]) )
		{
			throw new InvalidArgumentException(
				'Cache key cannot be an empty string'
			);
		}

		if ( preg_match('~[^a-zA-Z0-9_\\-$]+~', $key) !== 0 )
		{
			throw new InvalidArgumentException(sprintf(
				'Invalid key: "%s". The key contains one or more not allowed characters, allowed are only %s',
				$key,
				'`a-z`, `A-Z`, `0-9`, `_` and `-`'
			));
		}
	}
}
