<?php
include_once('common.php');

// Check download token
if (empty($_GET['mime']) OR empty($_GET['token']))
{
	echo $template->render('error.php', [
		'error_message' => 'Invalid download token 8{',
	]);
	exit();
}

// Set operation params
$mime = filter_var($_GET['mime']);
$ext = str_replace(['/', 'x-'], '', strstr($mime, '/'));
$url = base64_decode(filter_var($_GET['token']));
$name = urldecode($_GET['title']) . '.' . $ext;

// Fetch and serve
if ($url)
{
	global $config;
	// prevent unauthorized download
	if($config->get('VideoLinkMode') === "direct" and !isset($_GET['getmp3']))
	{
		echo $template->render('error.php', [
			'error_message' => 'VideoLinkMode: proxy download not enabled',
		]);
		exit;
	}
	if($config->get('VideoLinkMode') !== "direct" and !isset($_GET['getmp3']) and !preg_match('@https://[^\.]+\.googlevideo.com/@', $url))
	{
		echo $template->render('error.php', [
			'error_message' => 'unauthorized access (^_^)',
		]);
		exit;
	}

	// check if request for mp3 download
	if(isset($_GET['getmp3']))
	{
		if($config->get('MP3Enable'))
		{
			$mp3_info = array();
			$mp3_info = \YoutubeDownloader\YoutubeDownloader::getDownloadMP3($url, $config);
			if(isset($mp3_info['mp3']))
			{
				$url = $mp3_info['mp3'];
			}
			else
			{
				if($config->get('debug') && isset($mp3_info['debugMessage']))
				{
					var_dump($mp3_info['debugMessage']);
				}
				echo $template->render('error.php', [
					'error_message' => $mp3_info['message'],
				]);
				exit;
			}
		}
		else
		{
			echo $template->render('error.php', [
				'error_message' => 'Option for MP3 download is not enabled.',
			]);
			exit;
		}
	}

	if(isset($mp3_info['mp3']))
	{
		$size = filesize($mp3_info['mp3']);
	}
	else
	{
		$size = \YoutubeDownloader\YoutubeDownloader::get_size($url, $config);
	}

	// Generate the server headers
	header('Content-Type: "' . $mime . '"');
	header('Content-Disposition: attachment; filename="' . $name . '"');
	header("Content-Transfer-Encoding: binary");
	header('Expires: 0');
	header('Content-Length: '.$size);
	header('Pragma: no-cache');

	if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== FALSE)
	{
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
	}

	readfile($url);
	exit;
}

// Not found
echo $template->render('error.php', [
	'error_message' => 'File not found 8{',
]);
exit;
