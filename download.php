<?php

include_once('config.php');
// Check download token
if (empty($_GET['mime']) OR empty($_GET['token']))
{
	exit('Invalid download token 8{');
}

// Set operation params
$mime = filter_var($_GET['mime']);
$ext  = str_replace(array('/', 'x-'), '', strstr($mime, '/'));
$url  = base64_decode(filter_var($_GET['token']));
$name = urldecode($_GET['title']). '.' .$ext; 

// Fetch and serve
if ($url)
{
	$size=get_size($url);
	// Generate the server headers
	if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== FALSE)
	{
		header('Content-Type: "' . $mime . '"');
		header('Content-Disposition: attachment; filename="' . $name . '"');
		header('Expires: 0');
		header('Content-Length: '.$size);
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header("Content-Transfer-Encoding: binary");
		header('Pragma: public');
	}
	else
	{
		header('Content-Type: "' . $mime . '"');
		header('Content-Disposition: attachment; filename="' . $name . '"');
		header("Content-Transfer-Encoding: binary");
		header('Expires: 0');
		header('Content-Length: '.$size);
		header('Pragma: no-cache');
	}

	readfile($url);
	exit;
}

// Not found
exit('File not found 8{');
