<?php

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
