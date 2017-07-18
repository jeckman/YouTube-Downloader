<?php

namespace YoutubeDownloader\Application;

/**
 * The main controller
 */
class MainController extends ControllerAbstract
{
	/**
	 * Excute the Controller
	 *
	 * @param string $route
	 * @param YoutubeDownloader\Application\App $app
	 *
	 * @return Controller
	 */
	public function execute()
	{
		$config = $this->get('config');
		$template = $this->get('template');

		echo $template->render('index.php', [
			'is_chrome' => \YoutubeDownloader\YoutubeDownloader::is_chrome(),
			'showBrowserExtensions' => $config->get('showBrowserExtensions'),
		]);
	}
}
