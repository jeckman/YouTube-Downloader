<?php

namespace YoutubeDownloader\Cache;

use Psr\SimpleCache\InvalidArgumentException;

/**
 * No entry was found in the container.
 */
class Psr16InvalidArgumentException extends Psr16CacheException implements InvalidArgumentException
{
}
