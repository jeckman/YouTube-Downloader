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

	/**
	 * Returns the app version
	 *
	 * @return string
	 */
	protected function getAppVersion()
	{
		return $this->app->getVersion();
	}

	/**
	 * Echos an error and exit
	 *
	 * @param string $message
	 * @return void
	 */
	protected function responseWithErrorMessage($message)
	{
		$template = $this->get('template');

		echo $template->render('error.php', [
			'app_version' => $this->getAppVersion(),
			'error_message' => strval($message),
		]);

		exit;
	}
}
