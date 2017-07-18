<?php

namespace YoutubeDownloader\Application;

/**
 * The actract controller
 */
abstract class ControllerAbstract implements Controller
{
	private $app;

	/**
	 * Create the controller
	 *
	 * @param YoutubeDownloader\Container\App $app
	 *
	 * @return void
	 */
	public function __construct(App $app)
	{
		$this->app = $app;
	}

	/**
	 * Get an entry from the container
	 *
	 * @param string $id
	 *
	 * @return mixed
	 */
	protected function get($id)
	{
		return $this->app->getContainer()->get($id);
	}
}
