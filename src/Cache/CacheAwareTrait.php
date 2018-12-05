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

use Psr\SimpleCache\CacheInterface;

/**
 * Trait for Cache-aware instances
 */
trait CacheAwareTrait
{
    /**
     * @var YoutubeDownloader\Cache\Cache
     */
    private $cache;

    /**
     * Sets a cache instance on the object
     *
     * @deprecated since version 0.8, setCache() will require a Psr\SimpleCache\CacheInterface instance in 0.9
     *
     * param Psr\SimpleCache\CacheInterface $cache
     *
     * @param Cache $cache
     */
    public function setCache($cache)
    {
        if (! $cache instanceof Cache and ! $cache instanceof CacheInterface) {
            throw new \Exception('Argument 1 passed to ' . __METHOD__ . ' must be an instance of Psr\SimpleCache\CacheInterface or YoutubeDownloader\Cache\Cache');
        }
        $this->cache = $cache;
    }

    /**
     * Gets a cache instance
     *
     * @return Cache
     */
    public function getCache()
    {
        if ($this->cache === null) {
            $this->cache = new NullCache;
        }

        return $this->cache;
    }
}
