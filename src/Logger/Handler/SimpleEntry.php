<?php

/*
 * PHP script for downloading videos from youtube
 * Copyright (C) 2012-2017  John Eckman
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, see <http://www.gnu.org/licenses/>.
 */

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
