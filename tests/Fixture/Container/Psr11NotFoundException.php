<?php

namespace YoutubeDownloader\Container;

use Psr\Container\NotFoundExceptionInterface;

/**
 * No entry was found in the container.
 */
class NotFoundException extends Psr11ContainerException implements NotFoundExceptionInterface
{
}
