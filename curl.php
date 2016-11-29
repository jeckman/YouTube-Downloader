<?php
/*
 * function to get via cUrl 
 * From lastRSS 0.9.1 by Vojtech Semecky, webmaster @ webdot . cz
 * See      http://lastrss.webdot.cz/
 */
 
// function curlGet($URL) {
// 	global $config; // get global $config to know if $config['multipleIPs'] is true
//     $ch = curl_init();
//     $timeout = 3;
//     if ($config['multipleIPs'] === true) {
// 	    // if $config['multipleIPs'] is true set outgoing ip to $outgoing_ip
// 	    global $outgoing_ip;
// 	    curl_setopt($ch, CURLOPT_INTERFACE, $outgoing_ip);
// 	}
//     curl_setopt( $ch , CURLOPT_URL , $URL );
//     curl_setopt( $ch , CURLOPT_RETURNTRANSFER , 1 );
//     curl_setopt( $ch , CURLOPT_CONNECTTIMEOUT , $timeout );
// 	/* if you want to force to ipv6, uncomment the following line */ 
// 	//curl_setopt( $ch , CURLOPT_IPRESOLVE , 'CURLOPT_IPRESOLVE_V6');
//     $tmp = curl_exec( $ch );
//     curl_close( $ch );
//     return $tmp;
// }  

if ($config['multipleIPs'] === true) {
	// randomly select an ip from the $config['IPs'] array
	$outgoing_ip = $config['IPs'][mt_rand(0, count($config['IPs']) - 1)];
}

function curlGet($url, $cookies = "", $header = 0){
	global $config; // get global $config to know if $config['multipleIPs'] is true
	$ch = @curl_init();
	$timeout = 3;
	curl_setopt($ch, CURLOPT_URL, $url);
	
	$head[] = "Connection: keep-alive";
	$head[] = "Keep-Alive: 300";
	$head[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
	$head[] = "Accept-Language: en-us,en;q=0.5";
	if ($cookies) curl_setopt($ch, CURLOPT_COOKIE, $cookies);
	
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:44.0) Gecko/20100101 Firefox/44.0");
	if($header == -1){ 
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_NOBODY, 1);
	}
	else curl_setopt($ch, CURLOPT_HEADER, $header);
	if ($config['multipleIPs'] === true) {
	    // if $config['multipleIPs'] is true set outgoing ip to $outgoing_ip
	    global $outgoing_ip;
	    curl_setopt($ch, CURLOPT_INTERFACE, $outgoing_ip);
	}
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
	/* if you want to force to ipv6, uncomment the following line */ 
	//curl_setopt( $ch , CURLOPT_IPRESOLVE , 'CURLOPT_IPRESOLVE_V6');
	$page = curl_exec($ch);
	curl_close($ch);
	return $page;
}

/* 
 * function to use cUrl to get the headers of the file 
 */ 
function get_location($url) {
	$my_ch = curl_init();
	curl_setopt($my_ch, CURLOPT_URL,$url);
	curl_setopt($my_ch, CURLOPT_HEADER,         true);
	curl_setopt($my_ch, CURLOPT_NOBODY,         true);
	curl_setopt($my_ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($my_ch, CURLOPT_TIMEOUT,        10);
	$r = curl_exec($my_ch);
	 foreach(explode("\n", $r) as $header) {
		if(strpos($header, 'Location: ') === 0) {
			return trim(substr($header,10)); 
		}
	 }
	return '';
}

function get_size($url) {
	$my_ch = curl_init();
	curl_setopt($my_ch, CURLOPT_URL,$url);
	curl_setopt($my_ch, CURLOPT_HEADER,         true);
	curl_setopt($my_ch, CURLOPT_NOBODY,         true);
	curl_setopt($my_ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($my_ch, CURLOPT_TIMEOUT,        10);
	$r = curl_exec($my_ch);
	 foreach(explode("\n", $r) as $header) {
		if(strpos($header, 'Content-Length:') === 0) {
			return trim(substr($header,16)); 
		}
	 }
	return '';
}

function get_description($url) {
	$fullpage = curlGet($url);
	$dom = new DOMDocument();
	@$dom->loadHTML($fullpage);
	$xpath = new DOMXPath($dom); 
	$tags = $xpath->query('//div[@class="info-description-body"]');
	foreach ($tags as $tag) {
		$my_description .= (trim($tag->nodeValue));
	}	
	
	return utf8_decode($my_description);
}
?>
