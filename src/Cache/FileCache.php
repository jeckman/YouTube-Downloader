<?php

namespace YoutubeDownloader\Cache;

use DateTimeInterface;

/**
 * Describes the interface of a container that exposes methods to read its entries.
 *
 * This interface is compatible with PSR-16 Psr\SimpleCache\CacheInterface
 */

class FileCache implements Cache
{
	/**
	 * Create the cache with a directory
	 *
	 * @param string $directory The cache root directory
	 *
	 * @param CacheException If $directory not exists
	 * @param CacheException If $directory is not writable
	 *
	 * @return FileCache
	 */
	public static function createFromDirectory($directory)
	{
		$directory = rtrim(strval($directory), '/\\');

		if ( ! file_exists($directory) )
		{
			throw new CacheException(
				sprintf('cache directory "%s" does not exist.', $directory)
			);
		}

		if ( ! is_dir($directory) )
		{
			throw new CacheException(
				sprintf('cache directory "%s" is not a directory.', $directory)
			);
		}

		if ( ! is_readable($directory) )
		{
			throw new CacheException(
				sprintf('cache directory "%s" is not readable.', $directory)
			);
		}

		if ( ! is_writable($directory) )
		{
			throw new CacheException(
				sprintf('cache directory "%s" is not writable.', $directory)
			);
		}

		return new self($directory);
	}

	/**
	 * @var string
	 */
	private $root;

	/**
	 * Fetches a value from the cache.
	 *
	 * @param string $directory The cache root directory
	 *
	 * @return void
	 */
	public function __construct($directory)
	{
		$this->root = rtrim(strval($directory), '/\\');
	}

	/**
	 * Fetches a value from the cache.
	 *
	 * @param string $key The unique key of this item in the cache.
	 * @param mixed  $default Default value to return if the key does not exist.
	 *
	 * @return mixed The value of the item from the cache, or $default in case of cache miss.
	 *
	 * @throws \Psr\SimpleCache\InvalidArgumentException
	 *   MUST be thrown if the $key string is not a legal value.
	 */
	public function get($key, $default = null)
	{
		throw new \Exception('Not implemented');
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
	 * @throws \Psr\SimpleCache\InvalidArgumentException
	 *   MUST be thrown if the $key string is not a legal value.
	 */
	public function set($key, $value, $ttl = null)
	{
		throw new \Exception('Not implemented');
	}

	/**
	 * Delete an item from the cache by its unique key.
	 *
	 * @param string $key The unique cache key of the item to delete.
	 *
	 * @return bool True if the item was successfully removed. False if there was an error.
	 *
	 * @throws \Psr\SimpleCache\InvalidArgumentException
	 *   MUST be thrown if the $key string is not a legal value.
	 */
	public function delete($key)
	{
		throw new \Exception('Not implemented');
	}

	/**
	 * Determines whether an item is present in the cache.
	 *
	 * NOTE: It is recommended that has() is only to be used for cache warming type purposes
	 * and not to be used within your live applications operations for get/set, as this method
	 * is subject to a race condition where your has() will return true and immediately after,
	 * another script can remove it making the state of your app out of date.
	 *
	 * @param string $key The cache item key.
	 *
	 * @return bool
	 *
	 * @throws \Psr\SimpleCache\InvalidArgumentException
	 *   MUST be thrown if the $key string is not a legal value.
	 */
	public function has($key)
	{
		throw new \Exception('Not implemented');
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

		if ( preg_match('~^[a-zA-Z0-9_-]*$~', $key) !== 0 )
		{
			throw new InvalidArgumentException(sprintf(
				'Invalid key: "%s". The key contains one or more not allowed characters, allowed are only %s',
				$key,
				'`a-z`, `A-Z`, `0-9`, `_` and `-`'
			));
		}
	}
}
