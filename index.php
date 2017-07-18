<?php

include_once('common.php');

echo $template->render('index.php', [
	'is_chrome' => \YoutubeDownloader\YoutubeDownloader::is_chrome(),
	'showBrowserExtensions' => $config->get('showBrowserExtensions'),
]);
