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
		$options['curl'][CURLOPT_TIMEOUT] = 3;
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

	/**
	 * Check if a user-agent is chrome
	 *
	 * @param string $agent
	 * @return bool
	 */
	public function isUseragentChrome($agent)
	{
		// if user agent is google chrome
		if (preg_match("/like\sGecko\)\sChrome\//", $agent))
		{
			// but not Iron
			if (!strstr($agent, 'Iron'))
			{
				return true;
			}
		}

		// if isn't chrome return false
		return false;
	}
}
