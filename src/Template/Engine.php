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

namespace YoutubeDownloader\Template;

/**
 * Template Engine
 */
class Engine
{
	/**
	 * Creates the engine with a template directory
	 *
	 * @param VideoInfo $video_info
	 * @return Engine
	 */
	public static function createFromDirectory($directory)
	{
		$directory = rtrim($directory, DIRECTORY_SEPARATOR);

		return new self($directory);
	}

	/**
	 * template directory.
	 *
	 * @var string
	 */
	private $directory;

	/**
	 * Creates the engine
	 *
	 * @param string $directory
	 * @return self
	 */
	private function __construct($directory)
	{
		$this->directory = $directory;
	}

	/**
	 * Get the template directory
	 *
	 * @return string
	 */
	public function getTemplateDirectory()
	{
		return $this->directory;
	}

	/**
	 * Create a new template and render it
	 *
	 * @param  string $name
	 * @param  array  $data
	 * @return string
	 */
	public function render($name, array $data = array())
	{
		return $this->createTemplate($name)->render($data);
	}

	/**
	 * Create a new template
	 *
	 * @param  string   $name
	 * @return Template
	 */
	private function createTemplate($name)
	{
		return new Template($this, $name);
	}
}
