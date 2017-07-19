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
			'app_version' => $this->getAppVersion(),
			'showBrowserExtensions' => ($toolkit->is_chrome() and $config->get('showBrowserExtensions')),
		]);
	}
}
