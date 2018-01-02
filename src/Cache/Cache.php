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

/**
 * Describes the interface of a container that exposes methods to read its entries.
 *
 * This interface is compatible with PSR-16 Psr\SimpleCache\CacheInterface
 */

interface Cache
{
    /**
     * Fetches a value from the cache.
     *
     * @param string $key     the unique key of this item in the cache
     * @param mixed  $default default value to return if the key does not exist
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *                                                   MUST be thrown if the $key string is not a legal value
     *
     * @return mixed the value of the item from the cache, or $default in case of cache miss
     */
    public function get($key, $default = null);

    /**
     * Persists data in the cache, uniquely referenced by a key with an optional expiration TTL time.
     *
     * @param string                $key   the key of the item to store
     * @param mixed                 $value the value of the item to store, must be serializable
     * @param null|int|DateInterval $ttl   Optional. The TTL value of this item. If no value is sent and
     *                                     the driver supports TTL then the library may set a default value
     *                                     for it or let the driver take care of that.
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *                                                   MUST be thrown if the $key string is not a legal value
     *
     * @return bool true on success and false on failure
     */
    public function set($key, $value, $ttl = null);

    /**
     * Delete an item from the cache by its unique key.
     *
     * @param string $key the unique cache key of the item to delete
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *                                                   MUST be thrown if the $key string is not a legal value
     *
     * @return bool True if the item was successfully removed. False if there was an error.
     */
    public function delete($key);
}
