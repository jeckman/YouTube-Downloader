<?php

namespace YoutubeDownloader\Logger\Handler;

use DateTime;

/**
 * An simple entry instance
 */
class SimpleEntry implements Entry
{
	private $message;
	private $context;
	private $level;
	private $created_at;

	/**
	 * Create an entry
	 *
	 * @param DateTime $created_at
	 * @param mixed $level
	 * @param string $message
	 * @param array $context
	 * @return self
	 */
	public function __construct(DateTime $created_at, $level, $message, array $context = array())
	{
		$this->created_at = $created_at;
		$this->level = $level;
		$this->message = $message;
		$this->context = $context;
	}

	/**
	 * Returns the message
	 *
	 * @return string
	 */
	public function getMessage()
	{
		return $this->message;
	}

	/**
	 * Returns the context
	 *
	 * @return array
	 */
	public function getContext()
	{
		return $this->context;
	}

	/**
	 * Returns the level
	 *
	 * @return string
	 */
	public function getLevel()
	{
		return $this->level;
	}

	/**
	 * Returns the created DateTime
	 *
	 * @return DateTime
	 */
	public function getCreatedAt()
	{
		return $this->created_at;
	}
}
