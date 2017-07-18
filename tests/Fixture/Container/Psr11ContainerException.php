<?php

namespace YoutubeDownloader\Container;

use Exception;
use Psr\Container\ContainerExceptionInterface;

/**
 * Base interface representing a generic exception in a container.
 */
class Psr11ContainerException extends Exception implements ContainerExceptionInterface
{
}
