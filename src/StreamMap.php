<?php

namespace YoutubeDownloader;

/**
 * StreamMap
 */
class StreamMap
{
	

	/**
	 * Creates a StreamMap from VideoInfo
	 *
	 * @param VideoInfo $video_info
	 * @return StreamMap
	 */
	public static function createFromVideoInfo(VideoInfo $video_info, $algo = "")
	{
		// get the url_encoded_fmt_stream_map, and explode on comma
		$streams = explode(',', $video_info->getStreamMapString());
		$formats = explode(',', $video_info->getAdaptiveFormatsString());
		$algo = $algo;
		return new self($streams, $formats, $algo);
	}

	private $streams = [];

	private $formats = [];

	public $algos = '';

	/**
	 * Creates a StreamMap from streams and formats arrays
	 *
	 * @param array $streams
	 * @param array $formats
	 * @return self
	 */
	private function __construct(array $streams, array $formats, $algos = "")
	{
		$this->algos = $algos;
		if ($this->algos !== "") {
			$this->streams = $this->parseStreams($streams,$this->algos);
			$this->formats = $this->parseStreams($formats,$this->algos);
		}else{
			$this->streams = $this->parseStreams($streams,'');
			$this->formats = $this->parseStreams($formats,'');
		}
	}

	/**
	 * Parses an array of streams
	 *
	 * @param array $streams
	 * @return array
	 */
	private function parseStreams(array $streams,$algos)
	{
		$formats = [];

		if (count($streams) === 1 and $streams[0] === '' )
		{
			return $formats;
		}

		foreach ($streams as $format)
		{
			parse_str($format, $format_info);
			parse_str(urldecode($format_info['url']), $url_info);

			if (isset($format_info['bitrate']))
			{
				$quality = isset($format_info['quality_label']) ? $format_info['quality_label'] : round($format_info['bitrate']/1000).'k';
			}
			else
			{
				$quality =  isset($format_info['quality']) ? $format_info['quality'] : '';
			}

			$type = explode(';', $format_info['type']);
			if ($algos !== "") {
				$url = $format_info['url'] . '&signature=' . $this->decryptSignature($format_info['s'],$algos);
			}else{
				$url = $format_info['url'];
			}
			
			$formats[] = [
				'itag' => $format_info['itag'],
				'quality' => $quality,
				'type' => $type[0],
				'url' => $url,
				'expires' => isset($url_info['expire']) ? date("G:i:s T", $url_info['expire']) : '',
				'ipbits' => isset($url_info['ipbits']) ? $url_info['ipbits'] : '',
				'ip' => isset($url_info['ip']) ? $url_info['ip'] : '',
			];
		}

		return $formats;
	}

	/**
	 * Get the streams
	 *
	 * @return string
	 */
	public function getStreams()
	{
		return $this->streams;
	}

	/**
	 * Get the formats
	 *
	 * @return string
	 */
	public function getFormats()
	{
		return $this->formats;
	}

	/**
	 * Decrypt Signature
	 *
	 * @return string
	 */
	public function decryptSignature($encryptedSig, $algorithm)
    {
        $output = false;
        // Validate pattern of SDA rule
        if (is_string($encryptedSig) && is_string($algorithm) &&
            preg_match_all('/([R|S|W]{1})(\d+)/', $algorithm, $matches)
        ) {
            // Apply each SDA rule on encrypted signature
            foreach ($matches[1] as $pos => $cond) {
                $size = $matches[2][$pos];
                switch ($cond) {
                    case 'R':
                        // Reverse EncSig (Encrypted Signature)
                        $encryptedSig = strrev($encryptedSig);
                        break;
                    case 'S':
                        // Splice EncSig
                        $encryptedSig = substr($encryptedSig, $size);
                        break;
                    case 'W':
                        // Swap first char and nth char on EncSig
                        $sigArray = str_split($encryptedSig);
                        $zeroChar = $sigArray[0];
                        // Replace positions
                        $sigArray[0] = @$sigArray[$size];
                        $sigArray[$size] = $zeroChar;
                        // Join signature
                        $encryptedSig = implode('', $sigArray);
                        break;
                }
            }
            // Finally dump decrypted signature :)
            $output = $encryptedSig;
        }

        return $output;
    }
}
