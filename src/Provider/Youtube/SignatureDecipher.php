<?php

/*
 * PHP script for downloading videos from youtube
 * Copyright (C) 2012-2017  John Eckman
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, see <http://www.gnu.org/licenses/>.
 */
// Because we will processing 1MB of file I will not use RegExp for processing strings

namespace YoutubeDownloader\Provider\Youtube;

use YoutubeDownloader\Logger\Logger;
use YoutubeDownloader\Logger\NullLogger;

/**
 * a youtube signatur decipher
 */
class SignatureDecipher
{
	/**
	 * returns the player id and url for a video id
	 *
	 * @param string $videoID
	 *
	 * @return array the player id and url
	 *
	 * @throws \Exception if the player id couldn't be get
	 */
	public static function getPlayerInfoByVideoId($videoID)
	{
		$playerID = self::loadURL('https://www.youtube.com/watch?v=' . $videoID);
		$playerID = explode("\/yts\/jsbin\/player-", $playerID);

		if(count($playerID) === 0)
		{
			throw new \Exception(sprintf(
				'Failed to retrieve player script for video id: %s',
				$videoID
			));
		}

		$playerID = $playerID[1];
		$playerURL = str_replace('\/', '/', explode('"', $playerID)[0]);
		$playerID = explode('/', $playerURL)[0];

		return [
			$playerID,
			$playerURL,
		];
	}

	/**
	 * download the raw player script
	 *
	 * @param string $videoURL
	 *
	 * @return string the raw player script
	 *
	 * @throws \Exception if the script couldn't be downloaded
	 */
	public static function downloadRawPlayerScript($playerURL)
	{
		return self::loadURL(
			'https://youtube.com/yts/jsbin/player-' . $playerURL
		);
	}

	/**
	 * decipher a signature with a raw player script
	 *
	 * @param string $decipherScript
	 * @param string $signature
	 * @param Logger $logger
	 * @return string returns the decipherd signature
	 */
	public static function decipherSignatureWithRawPlayerScript($decipherScript, $signature, Logger $logger = null)
	{
		// BC: Use NullLogger if no Logger was set
		if ( $logger === null )
		{
			$logger = new NullLogger;
		}

		$logger->debug(
			'{method}: Load player script and execute patterns from player script',
			['method' => __METHOD__]
		);

		if ( ! $decipherScript )
		{
			$logger->debug(
				'{method}: No decipher script was provided. Abort.',
				['method' => __METHOD__]
			);
			return '';
		}

		// Some preparation
		$signatureCall = explode('("signature",', $decipherScript);
		$callCount = count($signatureCall);

		// Search for function call for example: e.set("signature",PE(f.s));
		// We need to get "PE"
		$signatureFunction = "";
		for ($i=$callCount-1; $i > 0; $i--){
			$signatureCall[$i] = explode(');', $signatureCall[$i])[0];

			if(strpos($signatureCall[$i], '('))
			{
				$signatureFunction = explode('(', $signatureCall[$i])[0];
				break;
			}
			elseif($i==0)
			{
				die("\n==== Failed to get signature function ====");
			}
		}

		$logger->debug(
			'{method}: signatureFunction = {signatureFunction}',
			['method' => __METHOD__, 'signatureFunction' => $signatureFunction]
		);

		$decipherPatterns = explode($signatureFunction."=function(", $decipherScript)[1];
		$decipherPatterns = explode('};', $decipherPatterns)[0];

		$logger->debug(
			'{method}: decipherPatterns = {decipherPatterns}',
			['method' => __METHOD__, 'decipherPatterns' => $decipherPatterns]
		);

		$deciphers = explode("(a", $decipherPatterns);
		for ($i=0; $i < count($deciphers); $i++)
		{
			$deciphers[$i] = explode('.', explode(';', $deciphers[$i])[1])[0];

			if(count(explode($deciphers[$i], $decipherPatterns))>=2)
			{
				// This object was most called, that's mean this is the deciphers
				$deciphers = $deciphers[$i];
				break;
			}
			else if($i==count($deciphers)-1)
			{
				die("\n==== Failed to get deciphers function ====");
			}
		}

		$deciphersObjectVar = $deciphers;
		$decipher = explode($deciphers.'={', $decipherScript)[1];
		$decipher = str_replace(["\n", "\r"], "", $decipher);
		$decipher = explode('}};', $decipher)[0];
		$decipher = explode("},", $decipher);

		$logger->debug(
			'{method}: decipher = {decipher}',
			['method' => __METHOD__, 'decipher' => print_r($decipher, true)]
		);

		// Convert deciphers to object
		$deciphers = [];

		foreach ($decipher as &$function)
		{
			$deciphers[explode(':function', $function)[0]] = explode('){', $function)[1];
		}

		// Convert pattern to array
		$decipherPatterns = str_replace($deciphersObjectVar.'.', '', $decipherPatterns);
		$decipherPatterns = str_replace('(a,', '->(', $decipherPatterns);
		$decipherPatterns = explode(';', explode('){', $decipherPatterns)[1]);

		$decipheredSignature = self::executeSignaturePattern(
			$decipherPatterns,
			$deciphers,
			$signature,
			$logger
		);

		// For debugging
		$logger->debug(
			'{method}: Results:',
			['method' => __METHOD__]
		);

		$logger->debug(
			'{method}: Signature = {signature}',
			['method' => __METHOD__, 'signature' => $signature]
		);

		$logger->debug(
			'{method}: Deciphered = {decipheredSignature}',
			['method' => __METHOD__, 'decipheredSignature' => $decipheredSignature]
		);

		//file_put_contents("Deciphers".rand(1, 100000).".log", ob_get_contents()); // If you need to debug all video

		//Return signature
		return $decipheredSignature;
	}

	private static function executeSignaturePattern($patterns, $deciphers, $signature, Logger $logger)
	{
		$logger->debug(
			'{method}: Patterns = {patterns}',
			['method' => __METHOD__, 'patterns' => print_r($patterns, true)]
		);
		$logger->debug(
			'{method}: Deciphers = {deciphers}',
			['method' => __METHOD__, 'deciphers' => print_r($deciphers, true)]
		);
		$logger->debug(
			'{method}: ==== Processing ====',
			['method' => __METHOD__]
		);

		// Execute every $patterns with $deciphers dictionary
		$processSignature = $signature;
		for ($i=0; $i < count($patterns); $i++) {
			// This is the deciphers dictionary, and should be updated if there are different pattern
			// as PHP can't execute javascript

			//Handle non deciphers pattern
			if(strpos($patterns[$i], '->')===false){
				if(strpos($patterns[$i], '.split("")')!==false)
				{
					$processSignature = str_split($processSignature);
					$logger->debug(
						'{method}: String splitted',
						['method' => __METHOD__]
					);
				}
				else if(strpos($patterns[$i], '.join("")')!==false)
				{
					$processSignature = implode('', $processSignature);
					$logger->debug(
						'{method}: String combined',
						['method' => __METHOD__]
					);
				}
				else
				{
					die("\n==== Decipher dictionary was not found ====");
				}
			}
			else
			{
				//Separate commands
				$executes = explode('->', $patterns[$i]);

				// This is parameter b value for 'function(a,b){}'
				$number = intval(str_replace(['(', ')'], '', $executes[1]));
				// Parameter a = $processSignature

				$execute = $deciphers[$executes[0]];

				//Find matched command dictionary
				$logger->debug(
					"{method}: Executing $executes[0] -> $number",
					['method' => __METHOD__]
				);
				switch($execute){
					case "a.reverse()":
						$processSignature = array_reverse($processSignature);
						$logger->debug(
							'{method}: (Reversing array)',
							['method' => __METHOD__]
						);
					break;
					case "var c=a[0];a[0]=a[b%a.length];a[b]=c":
						$c = $processSignature[0];
						$processSignature[0] = $processSignature[$number%count($processSignature)];
						$processSignature[$number] = $c;
						$logger->debug(
							'{method}: (Swapping array)',
							['method' => __METHOD__]
						);
					break;
					case "a.splice(0,b)":
						$processSignature = array_slice($processSignature, $number);
						$logger->debug(
							'{method}: Removing array',
							['method' => __METHOD__]
						);
					break;
					default:
						die("\n==== Decipher dictionary was not found ====");
					break;
				}
			}
		}
		return $processSignature;
	}

	private static function loadURL($url)
	{
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_TIMEOUT, 3);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
}
