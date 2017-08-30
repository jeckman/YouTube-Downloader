<?php

namespace YoutubeDownloader\Cache;

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
	 * @param Cache $cache
	 * @return null
	 */
	public function setCache(Cache $cache)
	{
		$this->cache = $cache;
	}

	/**
	 * Gets a cache instance
	 *
	 * @return Cache
	 */
	public function getCache()
	{
		if ( $this->cache === null )
		{
			$this->cache = new NullCache;
		}

		return $this->cache;
	}
}
