<?php

namespace YoutubeDownloader\Logger;

/**
 * Describes a logger-aware instance
 *
 * This interface is compatible with PSR-3 Psr\Log\LoggerAwareInterface
 */
interface LoggerAware
{
	/**
	 * Sets a logger instance on the object
	 *
	 * @param LoggerInterface $logger
	 * @return null
	 */
	public function setLogger(Logger $logger);
}
