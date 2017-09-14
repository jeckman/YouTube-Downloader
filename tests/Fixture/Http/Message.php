<?php

namespace YoutubeDownloader\Tests\Fixture\Http;

use YoutubeDownloader\Http\MessageTrait;
use YoutubeDownloader\Http\Message\Message as MessageInterface;

/**
 * A simple message instance
 */
class Message implements MessageInterface
{
	use MessageTrait;
}
