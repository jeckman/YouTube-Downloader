<?php
$container = include_once('common.php');

$config = $container->get('config');
$template = $container->get('template');

echo $template->render('index.php', [
	'is_chrome' => \YoutubeDownloader\YoutubeDownloader::is_chrome(),
	'showBrowserExtensions' => $config->get('showBrowserExtensions'),
]);
