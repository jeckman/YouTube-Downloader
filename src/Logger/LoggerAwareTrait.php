<?php

namespace YoutubeDownloader\Logger;

/**
 * Trait for logger-aware instances
 */
trait LoggerAwareTrait
{
	/**
	 * @var YoutubeDownloader\Logger\Logger
	 */
	protected $logger;

	/**
	 * Sets a logger instance on the object
	 *
	 * @param Logger $logger
	 * @return null
	 */
	public function setLogger(Logger $logger)
	{
		$this->logger = $logger;
	}

	/**
	 * Gets a logger instance
	 *
	 * @return Logger
	 */
	public function getLogger()
	{
		if ( $this->logger === null )
		{
			$this->logger = new NullLogger;
		}

		return $this->logger;
	}
}
