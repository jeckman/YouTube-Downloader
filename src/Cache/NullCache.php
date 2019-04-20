<?php

/*
 * PHP script for downloading videos from youtube
 * Copyright (C) 2012-2018  John Eckman
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
use Psr\SimpleCache\CacheInterface;

/**
 * A cache instance that does nothing
 */

class NullCache implements CacheInterface
{
    /**
     * Fetches a value from the cache.
     *
     * @param string $key     the unique key of this item in the cache
     * @param mixed  $default default value to return if the key does not exist
     *
     * @throws invalidArgumentException
     *                                  MUST be thrown if the $key string is not a legal value
     *
     * @return mixed the value of the item from the cache, or $default in case of cache miss
     */
    public function get($key, $default = null)
    {
        $this->validateKey($key);

        return $default;
    }

    /**
     * Persists data in the cache, uniquely referenced by a key with an optional expiration TTL time.
     *
     * @param string                $key   the key of the item to store
     * @param mixed                 $value the value of the item to store, must be serializable
     * @param null|int|DateInterval $ttl   Optional. The TTL value of this item. If no value is sent and
     *                                     the driver supports TTL then the library may set a default value
     *                                     for it or let the driver take care of that.
     *
     * @throws invalidArgumentException
     *                                  MUST be thrown if the $key string is not a legal value
     *
     * @return bool true on success and false on failure
     */
    public function set($key, $value, $ttl = null)
    {
        $this->validateKey($key);

        return true;
    }

    /**
     * Delete an item from the cache by its unique key.
     *
     * @param string $key the unique cache key of the item to delete
     *
     * @throws invalidArgumentException
     *                                  MUST be thrown if the $key string is not a legal value
     *
     * @return bool True if the item was successfully removed. False if there was an error.
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
        if (! is_string($key)) {
            throw new InvalidArgumentException(
                sprintf('Cache key must be string, "%s" given', gettype($key))
            );
        }

        if (! isset($key[0])) {
            throw new InvalidArgumentException(
                'Cache key cannot be an empty string'
            );
        }

        if (preg_match('~[^a-zA-Z0-9_\\-$]+~', $key) !== 0) {
            throw new InvalidArgumentException(sprintf(
                'Invalid key: "%s". The key contains one or more not allowed characters, allowed are only %s',
                $key,
                '`a-z`, `A-Z`, `0-9`, `_` and `-`'
            ));
        }
    }

    /**
     * Wipes clean the entire cache's keys.
     *
     * @return bool true on success and false on failure
     */
    public function clear()
    {
        throw new \Exception(__METHOD__ . ' is not implemented');
    }

    /**
     * Obtains multiple cache items by their unique keys.
     *
     * @param iterable $keys    a list of keys that can obtained in a single operation
     * @param mixed    $default default value to return for keys that do not exist
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *                                                   MUST be thrown if $keys is neither an array nor a Traversable,
     *                                                   or if any of the $keys are not a legal value
     *
     * @return iterable A list of key => value pairs. Cache keys that do not exist or are stale will have $default as value.
     */
    public function getMultiple($keys, $default = null)
    {
        throw new \Exception(__METHOD__ . ' is not implemented');
    }

    /**
     * Persists a set of key => value pairs in the cache, with an optional TTL.
     *
     * @param iterable               $values a list of key => value pairs for a multiple-set operation
     * @param null|int|\DateInterval $ttl    Optional. The TTL value of this item. If no value is sent and
     *                                       the driver supports TTL then the library may set a default value
     *                                       for it or let the driver take care of that.
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *                                                   MUST be thrown if $values is neither an array nor a Traversable,
     *                                                   or if any of the $values are not a legal value
     *
     * @return bool true on success and false on failure
     */
    public function setMultiple($values, $ttl = null)
    {
        throw new \Exception(__METHOD__ . ' is not implemented');
    }

    /**
     * Deletes multiple cache items in a single operation.
     *
     * @param iterable $keys a list of string-based keys to be deleted
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *                                                   MUST be thrown if $keys is neither an array nor a Traversable,
     *                                                   or if any of the $keys are not a legal value
     *
     * @return bool True if the items were successfully removed. False if there was an error.
     */
    public function deleteMultiple($keys)
    {
        throw new \Exception(__METHOD__ . ' is not implemented');
    }

    /**
     * Determines whether an item is present in the cache.
     *
     * NOTE: It is recommended that has() is only to be used for cache warming type purposes
     * and not to be used within your live applications operations for get/set, as this method
     * is subject to a race condition where your has() will return true and immediately after,
     * another script can remove it making the state of your app out of date.
     *
     * @param string $key the cache item key
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *                                                   MUST be thrown if the $key string is not a legal value
     *
     * @return bool
     */
    public function has($key)
    {
        throw new \Exception(__METHOD__ . ' is not implemented');
    }
}
