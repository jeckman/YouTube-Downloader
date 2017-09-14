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

		$this->get('logger')->debug(
			'{controller_name} created',
			['controller_name' => get_class($this)]
		);
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

	protected function getSize($url, $config, $toolkit)
	{
		$request = $this->get('httpclient')->createRequest('HEAD', $url);

		$options = ['curl' => []];
		$options['curl'][CURLOPT_NOBODY] = true;
		$options['curl'][CURLOPT_TIMEOUT] = 1;
		$options['curl'][CURLOPT_SSL_VERIFYPEER] = false;

		if ( $config->get('multipleIPs') === true)
		{
			$options['curl'][CURLOPT_INTERFACE] = $toolkit->getRandomIp($config);
		}

		$this->get('logger')->debug(sprintf(
			'Run HTTP request for "%s %s"',
			$request->getMethod(),
			$request->getRequestTarget()
		));

		$response = $this->get('httpclient')->send($request, $options);

		return intval($response->getHeaderLine('Content-Length'));
	}
}
