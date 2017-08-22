<?php

namespace YoutubeDownloader\Logger\Handler;

/**
 * An entry interface for a handler instance
 */
interface Entry
{
	/**
	 * Returns the message
	 *
	 * @return string
	 */
	public function getMessage();

	/**
	 * Returns the context
	 *
	 * @return array
	 */
	public function getContext();

	/**
	 * Returns the level
	 *
	 * @return string
	 */
	public function getLevel();

	/**
	 * Returns the created DateTime
	 *
	 * @return DateTimeImmutable
	 */
	public function getCreatedAt();
}
