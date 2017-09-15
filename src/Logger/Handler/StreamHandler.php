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

/**
 * A handler that saves an entries per line in a stream
 */
class StreamHandler implements Handler
{
	private $template = "[%datetime%] %level_name%: %message%\n";

	/**
	 * Check if this handler handels a log level
	 *
	 * @param resource $stream A writable stream
	 * @param array $levels An array with levels handles by this handler
	 * @return self
	 */
	public function __construct($stream, array $levels)
	{
		if ( ! is_resource($stream) )
		{
			throw new \Exception('Parameter 1 must be a resource');
		}

		$meta = stream_get_meta_data($stream);

		$writable_modes = [
			'w' => true,
			'w+' => true,
			'rw' => true,
			'r+' => true,
			'x+' => true,
			'c+' => true,
			'wb' => true,
			'w+b' => true,
			'r+b' => true,
			'x+b' => true,
			'c+b' => true,
			'w+t' => true,
			'r+t' => true,
			'x+t' => true,
			'c+t' => true,
			'a' => true,
			'a+' => true,
		];

		if ( ! array_key_exists($meta['mode'], $writable_modes) )
		{
			throw new \Exception('The resource must be writable.');
		}

		$this->stream = $stream;

		foreach ($levels as $level)
		{
			$this->levels[] = strval($level);
		}
	}

	/**
	 * Check if this handler handels a log level
	 *
	 * @param string $level A valid log level from LogLevel class
	 * @return boolean
	 */
	public function handles($level)
	{
		return array_key_exists(strval($level), array_flip($this->levels));
	}

	/**
	 * Handle an entry
	 *
	 * @param Entry $entry
	 * @return boolean
	 */
	public function handle(Entry $entry)
	{
		if ( ! $this->handles($entry->getLevel()) )
		{
			return false;
		}

		fwrite($this->stream, $this->formatEntry($entry));

		return true;
	}

	/**
	 * Format an entry to a single line
	 *
	 * @param Entry $entry
	 * @return string
	 */
	private function formatEntry(Entry $entry)
	{
		$message = $this->interpolate($entry->getMessage(), $entry->getContext());

		$replace = [
			'%datetime%' => $entry->getCreatedAt()->format('Y-m-d\TH:i:sP'),
			'%level_name%' => $entry->getLevel(),
			'%message%' => $this->removeLinebreaks($message),
		];

		return strtr($this->template, $replace);
	}

	/**
	 * Interpolates context values into the message placeholders.
	 *
	 * @see http://www.php-fig.org/psr/psr-3/
	 *
	 * @param string $message
	 * @param array $context
	 * @return string
	 */
	private function interpolate($message, array $context = [])
	{
		// build a replacement array with braces around the context keys
		$replace = [];

		foreach ($context as $key => $val)
		{
			// check that the value can be casted to string
			if ( ! is_array($val) && ( ! is_object($val) || method_exists($val, '__toString') ) )
			{
				$replace['{' . $key . '}'] = strval($val);
			}
		}

		// interpolate replacement values into the message and return
		return strtr($message, $replace);
	}

	/**
	 * Remove all linebreaks in a string
	 *
	 * @param string $message
	 * @return string
	 */
	private function removeLinebreaks($message)
	{
		return str_replace(["\r\n", "\r", "\n"], ' ', $message);
	}
}
