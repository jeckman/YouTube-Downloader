<?php

include_once('config.php');
// Check download token
if (empty($_GET['mime']) OR empty($_GET['token'])) {
    exit('Invalid download token 8{');
}

function formatBytes($bytes, $precision = 2)
{
    $units = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);
    return round($bytes, $precision) . '' . $units[$pow];
}

// Set operation params
$mime = filter_var($_GET['mime']);
$ext = str_replace(array('/', 'x-'), '', strstr($mime, '/'));
$url = base64_decode(filter_var($_GET['token']));

// Fetch and serve
if ($url) {
    $size = formatBytes(get_size($url));
    $name = urldecode($_GET['title']) . '(' . $size . ').' . $ext;
    copy($url, './videos/' . $name);
    header('location: videos');
    exit;
}

// Not found
exit('File not found 8{');
