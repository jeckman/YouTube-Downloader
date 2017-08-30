<?php

namespace YoutubeDownloader\Cache;

/**
 * Describes a cache-aware instance
 */
interface CacheAware
{
	/**
	 * Sets a cache instance on the object
	 *
	 * @param Cache $cache
	 * @return null
	 */
	public function setCache(Cache $cache);
}
