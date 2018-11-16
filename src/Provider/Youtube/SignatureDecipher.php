<?php

/*
 * PHP script for downloading videos from youtube
 * Copyright (C) 2012-2018  John Eckman
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

namespace YoutubeDownloader\Provider\Youtube;

use YoutubeDownloader\Logger\Logger;
use YoutubeDownloader\Logger\NullLogger;

/**
 * a youtube signatur decipher
 *
 * @author StefansArya <arya.cipta@yahoo.com>
 *
 * Because it will processing 1MB of file it will not use RegExp for processing strings
 */
class SignatureDecipher
{
    /**
     * returns the player id and url for a video id
     *
     * @param string $videoID
     *
     * @throws \Exception if the player id couldn't be get
     *
     * @return array the player id and url
     */
    public static function getPlayerInfoByVideoId($videoID)
    {
        $data = self::loadURL('https://www.youtube.com/watch?v=' . $videoID);
        $data = explode('/yts/jsbin/player', $data)[1];
        $data = explode('"', $data)[0];
        $playerURL = 'https://www.youtube.com/yts/jsbin/player' . $data;

        try {
            $playerID = explode('-', explode('/', $data)[0]);
            $playerID = $playerID[count($playerID)-1];
        } catch (\Exception $e) {
            throw new \Exception(sprintf(
                'Failed to retrieve player script for video id: %s',
                $videoID
            ));

            return false;
        }

        return [
            $playerID,
            $playerURL,
        ];
    }

    /**
     * download the raw player script
     *
     * @param string $videoURL
     * @param mixed  $playerURL
     *
     * @throws \Exception if the script couldn't be downloaded
     *
     * @return string the raw player script
     */
    public static function downloadRawPlayerScript($playerURL)
    {
        return self::loadURL($playerURL);
    }

    /**
     * decipher a signature with a raw player script
     *
     * @deprecated since version 0.7, to be removed in 0.8.
     *
     * @param string $decipherScript
     * @param string $signature
     * @param Logger $logger
     *
     * @return string returns the deciphered signature
     */
    public static function decipherSignatureWithRawPlayerScript($decipherScript, $signature, Logger $logger = null)
    {
        @trigger_error(__METHOD__ . ' is deprecated since version 0.7, to be removed in 0.8.', E_USER_DEPRECATED);

        // BC: Use NullLogger if no Logger was set
        if ($logger === null) {
            $logger = new NullLogger;
        }

        $opcode = self::extractDecipherOpcode($decipherScript, $logger);

        $decipheredSignature = self::executeSignaturePattern(
            $opcode['decipherPatterns'],
            $opcode['deciphers'],
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

    /**
     * extract decipher opcode from raw player script
     *
     * @param string $decipherScript
     * @param Logger $logger
     *
     * @return array return operation codes
     */
    public static function extractDecipherOpcode($decipherScript, Logger $logger)
    {
        $logger->debug(
            '{method}: Load player script and execute patterns from player script',
            ['method' => __METHOD__]
        );

        if (! $decipherScript) {
            $logger->debug(
                '{method}: No decipher script was provided. Abort.',
                ['method' => __METHOD__]
            );

            return '';
        }
        
        $decipherPatterns = explode('.split("")', $decipherScript);
        unset($decipherPatterns[0]);
        foreach ($decipherPatterns as $value) {

            // Make sure it's inside a function and also have join
            $value = explode('.join("")', explode('}', $value)[0]);
            if(count($value) === 2){
                $value = explode(';', $value[0]);

                // Remove first and last index
                array_pop($value);
                unset($value[0]);

                $decipherPatterns = implode(';', $value);
                break;
            }
        }
        
        $logger->debug(
            '{method}: decipherPatterns = {decipherPatterns}',
            ['method' => __METHOD__, 'decipherPatterns' => $decipherPatterns]
        );

        preg_match_all('/(?<=;).*?(?=\[|\.)/', $decipherPatterns, $deciphers);
        if($deciphers && count($deciphers[0]) >= 2){
            $deciphers = $deciphers[0][0];
        }
        else{
            throw new \Exception("Failed to get deciphers function");
            return false;
        }

        $deciphersObjectVar = $deciphers;
        $decipher = explode($deciphers . '={', $decipherScript)[1];
        $decipher = str_replace(["\n", "\r"], '', $decipher);
        $decipher = explode('}};', $decipher)[0];
        $decipher = explode('},', $decipher);

        $logger->debug(
            '{method}: decipher = {decipher}',
            ['method' => __METHOD__, 'decipher' => print_r($decipher, true)]
        );

        // Convert deciphers to object
        $deciphers = [];

        foreach ($decipher as &$function) {
            $deciphers[explode(':function', $function)[0]] = explode('){', $function)[1];
        }

        // Convert pattern to array
        $decipherPatterns = str_replace($deciphersObjectVar . '.', '', $decipherPatterns);
        $decipherPatterns = str_replace($deciphersObjectVar . '[', '', $decipherPatterns);
        $decipherPatterns = str_replace(['](a,', '(a,'], '->(', $decipherPatterns);
        $decipherPatterns = explode(';', $decipherPatterns);

        return [
            'decipherPatterns' => $decipherPatterns,
            'deciphers' => $deciphers,
        ];
    }

    /**
     * decipher a signature with opcodes
     *
     * @internal
     *
     * @param string $patterns
     * @param string $deciphers
     * @param string $signature
     * @param Logger $logger
     *
     * @return string return deciphered signature
     */
    public static function executeSignaturePattern($patterns, $deciphers, $signature, Logger $logger)
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
        $processSignature = str_split($signature);
        for ($i=0; $i < count($patterns); $i++) {
            // This is the deciphers dictionary, and should be updated if there are different pattern
            // as PHP can't execute javascript

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
            switch ($execute) {
                case 'a.reverse()':
                    $processSignature = array_reverse($processSignature);
                    $logger->debug(
                        '{method}: (Reversing array)',
                        ['method' => __METHOD__]
                    );

                break;
                case 'var c=a[0];a[0]=a[b%a.length];a[b]=c':
                    $c = $processSignature[0];
                    $processSignature[0] = $processSignature[$number%count($processSignature)];
                    $processSignature[$number] = $c;
                    $logger->debug(
                        '{method}: (Swapping array)',
                        ['method' => __METHOD__]
                    );

                break;
                case 'var c=a[0];a[0]=a[b%a.length];a[b%a.length]=c':
                    $c = $processSignature[0];
                    $processSignature[0] = $processSignature[$number%count($processSignature)];
                    $processSignature[$number%count($processSignature)] = $c;
                    $logger->debug(
                        '{method}: (Swapping array)',
                        ['method' => __METHOD__]
                    );

                break;
                case 'a.splice(0,b)':
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

        return implode('', $processSignature);
    }

    private static function loadURL($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }
}
