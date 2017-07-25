<?php

namespace YoutubeDownloader\Cache;

/**
 * invalid argument exception in a cache.
 *
 * This interface must be compatible with PSR-16 Psr\SimpleCache\InvalidArgumentException
 */
class InvalidArgumentException extends CacheException
{
}
