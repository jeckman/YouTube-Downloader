<?php

include_once('common.php');

$template = \YoutubeDownloader\Template\Engine::createFromDirectory(__DIR__ . DIRECTORY_SEPARATOR . 'templates');

echo $template->render('index.php', [
	'is_chrome' => \YoutubeDownloader\YoutubeDownloader::is_chrome(),
	'showBrowserExtensions' => $config->get('showBrowserExtensions'),
]);
