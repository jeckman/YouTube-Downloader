<?php

namespace YoutubeDownloader\Logger;

use DateTime;
use YoutubeDownloader\Logger\Handler\Entry;
use YoutubeDownloader\Logger\Handler\Handler;
use YoutubeDownloader\Logger\Handler\SimpleEntry;

/**
 * a logger instance, that works with handler
 */
class HandlerAwareLogger implements Logger
{
	/**
	 * @var YoutubeDownloader\Logger\Handler\Handler[]
	 */
	private $handlers = [];

	/**
	 * This logger needs at least a handler
	 *
	 * @param YoutubeDownloader\Logger\Handler\Handler $handler
	 * @return self
	 */
	public function __construct(Handler $handler)
	{
		$this->addHandler($handler);
	}

	/**
	 * System is unusable.
	 *
	 * @param string $message
	 * @param array $context
	 * @return null
	 */
	public function emergency($message, array $context = array())
	{
		$this->log(LogLevel::EMERGENCY, $message, $context);
	}

	/**
	 * Action must be taken immediately.
	 *
	 * Example: Entire website down, database unavailable, etc. This should
	 * trigger the SMS alerts and wake you up.
	 *
	 * @param string $message
	 * @param array $context
	 * @return null
	 */
	public function alert($message, array $context = array())
	{
		return $this->log(LogLevel::ALERT, $message, $context);
	}

	/**
	 * Critical conditions.
	 *
	 * Example: Application component unavailable, unexpected exception.
	 *
	 * @param string $message
	 * @param array $context
	 * @return null
	 */
	public function critical($message, array $context = array())
	{
		return $this->log(LogLevel::CRITICAL, $message, $context);
	}

	/**
	 * Runtime errors that do not require immediate action but should typically
	 * be logged and monitored.
	 *
	 * @param string $message
	 * @param array $context
	 * @return null
	 */
	public function error($message, array $context = array())
	{
		return $this->log(LogLevel::ERROR, $message, $context);
	}

	/**
	 * Exceptional occurrences that are not errors.
	 *
	 * Example: Use of deprecated APIs, poor use of an API, undesirable things
	 * that are not necessarily wrong.
	 *
	 * @param string $message
	 * @param array $context
	 * @return null
	 */
	public function warning($message, array $context = array())
	{
		return $this->log(LogLevel::WARNING, $message, $context);
	}

	/**
	 * Normal but significant events.
	 *
	 * @param string $message
	 * @param array $context
	 * @return null
	 */
	public function notice($message, array $context = array())
	{
		return $this->log(LogLevel::NOTICE, $message, $context);
	}

	/**
	 * Interesting events.
	 *
	 * Example: User logs in, SQL logs.
	 *
	 * @param string $message
	 * @param array $context
	 * @return null
	 */
	public function info($message, array $context = array())
	{
		return $this->log(LogLevel::INFO, $message, $context);
	}

	/**
	 * Detailed debug information.
	 *
	 * @param string $message
	 * @param array $context
	 * @return null
	 */
	public function debug($message, array $context = array())
	{
		return $this->log(LogLevel::DEBUG, $message, $context);
	}

	/**
	 * Logs with an arbitrary level.
	 *
	 * @param mixed $level
	 * @param string $message
	 * @param array $context
	 * @return null
	 */
	public function log($level, $message, array $context = array())
	{
		$entry = $this->createEntry(
			new DateTime('now'),
			$level,
			$message,
			$context
		);

		$this->handleEntry($entry);
	}

	/**
	 * Adds a handler
	 *
	 * @param YoutubeDownloader\Logger\Handler\Handler $handler
	 * @return void
	 */
	public function addHandler(Handler $handler)
	{
		$this->handlers[] = $handler;
	}

	/**
	 * Factory for a new entry
	 *
	 * @param DateTime $created_at
	 * @param mixed $level
	 * @param string $message
	 * @param array $context
	 * @return YoutubeDownloader\Logger\Handler\Entry
	 */
	private function createEntry(DateTime $created_at, $level, $message, array $context = array())
	{
		return new SimpleEntry($created_at, $level, $message, $context);
	}

	/**
	 * Search for all handler that handles this entry and call them
	 *
	 * @param YoutubeDownloader\Logger\Handler\Entry $entry
	 * @return void
	 */
	private function handleEntry(Entry $entry)
	{
		foreach ($this->handlers as $handler)
		{
			if ($handler->handles($entry->getLevel()))
			{
				$handler->handle($entry);
			}
		}
	}
}
