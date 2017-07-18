<?php

namespace YoutubeDownloader\Template;

use Exception;
use LogicException;

/**
 * Template representation
 */
class Template
{
	/**
	 * Instance of the template engine.
	 * @var Engine
	 */
	private $engine;

	/**
	 * The name of the template.
	 * @var string
	 */
	private $name;

	/**
	 * Data
	 * @var array
	 */
	private $data;

	/**
	 * Create new Template instance.
	 * @param Engine $engine
	 * @param string $name
	 */
	public function __construct(Engine $engine, $name)
	{
		$this->engine = $engine;
		$this->name = strval($name);
	}

	/**
	 * Create a new template.
	 * @param  array  $data
	 * @return string
	 */
	public function render(array $data = [])
	{
		$this->setData($data);

		if ( ! $this->exists() )
		{
			throw new LogicException(
				'The template "' . $this->name . '" could not be found at "' . $this->engine->getTemplateDirectory() . '".'
			);
		}

		try
		{
			$level = ob_get_level();
			ob_start();

			include $this->getPath();

			$content = ob_get_clean();

			return $content;
		}
		catch (Exception $e)
		{
			while (ob_get_level() > $level)
			{
				ob_end_clean();
			}

			throw $e;
		}
	}

	/**
	 * Set data
	 *
	 * @param array $data
	 * @return void
	 */
	public function setData(array $data)
	{
		$this->data = $data;
	}

	/**
	 * Get data with key
	 *
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	public function get($key, $default = null)
	{
		if ( array_key_exists($key, $this->data) )
		{
			return $this->data[$key];
		}

		return $default;
	}

	/**
	 * include and parse another template file
	 *
	 * @param  string $name
	 * @param  array  $data
	 * @return mixed
	 */
	public function inc($name, array $data = [])
	{
		return $this->engine->render($name, $data);
	}

	/**
	 * Check if the template exists.
	 *
	 * @return boolean
	 */
	private function exists()
	{
		return is_file($this->getPath());
	}

	/**
	 * Check if the template exists.
	 *
	 * @return boolean
	 */
	private function getPath()
	{
		return $this->engine->getTemplateDirectory() . DIRECTORY_SEPARATOR . $this->name;
	}
}
