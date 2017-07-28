<?php
// Because we will processing 1MB of file I will not use RegExp for processing strings

namespace YoutubeDownloader;

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
	 * @return string returns the decipherd signature
	 */
	public static function decipherSignatureWithRawPlayerScript($decipherScript, $signature)
	{
		ob_start(); //For debugging
		echo("==== Load player script and execute patterns from player script ====\n\n");

		if ( ! $decipherScript )
		{
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
			if(strpos($signatureCall[$i], '(')){
				$signatureFunction = explode('(', $signatureCall[$i])[0];
				break;
			}
			else if($i==0) die("\n==== Failed to get signature function ====");
		}
		echo('signatureFunction = '.$signatureFunction."\n");

		$decipherPatterns = explode($signatureFunction."=function(", $decipherScript)[1];
		$decipherPatterns = explode('};', $decipherPatterns)[0];
		echo('decipherPatterns = '.$decipherPatterns."\n");

		$deciphers = explode("(a", $decipherPatterns);
		for ($i=0; $i < count($deciphers); $i++) {
			$deciphers[$i] = explode('.', explode(';', $deciphers[$i])[1])[0];
			if(count(explode($deciphers[$i], $decipherPatterns))>=2){
				// This object was most called, that's mean this is the deciphers
				$deciphers = $deciphers[$i];
				break;
			}
			else if($i==count($deciphers)-1) die("\n==== Failed to get deciphers function ====");
		}

		$deciphersObjectVar = $deciphers;
		$decipher = explode($deciphers.'={', $decipherScript)[1];
		$decipher = str_replace(["\n", "\r"], "", $decipher);
		$decipher = explode('}};', $decipher)[0];
		$decipher = explode("},", $decipher);
		print_r($decipher);

		// Convert deciphers to object
		$deciphers = [];
		foreach ($decipher as &$function) {
			$deciphers[explode(':function', $function)[0]] = explode('){', $function)[1];
		}

		// Convert pattern to array
		$decipherPatterns = str_replace($deciphersObjectVar.'.', '', $decipherPatterns);
		$decipherPatterns = str_replace('(a,', '->(', $decipherPatterns);
		$decipherPatterns = explode(';', explode('){', $decipherPatterns)[1]);

		$decipheredSignature = self::executeSignaturePattern($decipherPatterns, $deciphers, $signature);

		// For debugging
		echo("\n\n\n==== Result ====\n");
		echo("Signature  : ".$signature."\n");
		echo("Deciphered : ".$decipheredSignature);
		//file_put_contents("Deciphers".rand(1, 100000).".log", ob_get_contents()); // If you need to debug all video
		file_put_contents("Deciphers.log", ob_get_contents());
		ob_end_clean();

		//Return signature
		return $decipheredSignature;
	}

	private static function executeSignaturePattern($patterns, $deciphers, $signature)
	{
		echo("\n\n\n==== Retrieved deciphers ====\n\n");
		print_r($patterns);
		print_r($deciphers);

		echo("\n\n\n==== Processing ====\n\n");

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
					echo("String splitted\n");
				}
				else if(strpos($patterns[$i], '.join("")')!==false)
				{
					$processSignature = implode('', $processSignature);
					echo("String combined\n");
				}
				else die("\n==== Decipher dictionary was not found ====");
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
				echo("Executing $executes[0] -> $number");
				switch($execute){
					case "a.reverse()":
						$processSignature = array_reverse($processSignature);
						echo(" (Reversing array)\n");
					break;
					case "var c=a[0];a[0]=a[b%a.length];a[b]=c":
						$c = $processSignature[0];
						$processSignature[0] = $processSignature[$number%count($processSignature)];
						$processSignature[$number] = $c;
						echo(" (Swapping array)\n");
					break;
					case "a.splice(0,b)":
						$processSignature = array_slice($processSignature, $number);
						echo(" (Removing array)\n");
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
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
}
