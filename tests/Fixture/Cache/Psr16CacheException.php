<?php

namespace YoutubeDownloader\Cache;

use Exception;
use Psr\SimpleCache\CacheException;

/**
 * Base interface representing a generic exception in a cache.
 */
class Psr16CacheException extends Exception implements CacheException
{
}
