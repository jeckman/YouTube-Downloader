<?php

namespace YoutubeDownloader\Logger\Handler;

/**
 * a handler instance that handles no entries
 */
class NullHandler implements Handler
{
	/**
	 * Check if this handler handels a log level
	 *
	 * @param string $level A valid log level from LogLevel class
	 * @return boolean
	 */
	public function handles($level)
	{
		return false;
	}

	/**
	 * Handle an entry
	 *
	 * @param Entry $entry
	 * @return boolean
	 */
	public function handle(Entry $entry)
	{
		return false;
	}
}
