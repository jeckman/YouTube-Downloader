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
	 * @return void
	 */
	public function execute()
	{
		$config = $this->get('config');
		$template = $this->get('template');
		$toolkit = $this->get('toolkit');

		echo $template->render('index.php', [
			'is_chrome' => $toolkit->is_chrome(),
			'showBrowserExtensions' => $config->get('showBrowserExtensions'),
		]);
	}
}
