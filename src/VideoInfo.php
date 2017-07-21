<?php

namespace YoutubeDownloader;

/**
 * VideoInfo
 *
 * This class parses a videoinfo string from
 * http://www.youtube.com/get_video_info?video_id=...
 *
 * Possible array keys could be:
 * - 'length_seconds',
 * - 'midroll_prefetch_size',
 * - 'ad_tag',
 * - 'storyboard_spec',
 * - 'loudness',
 * - 'status',
 * - 'ptk',
 * - 'instream_long',
 * - 'url_encoded_fmt_stream_map',
 * - 'ad_device',
 * - 'ad_slots',
 * - 'ucid',
 * - 'rvs',
 * - 'fmt_list',
 * - 'iv_endscreen_url',
 * - 'caption_audio_tracks',
 * - 'atc',
 * - 'allow_ratings',
 * - 'afv_ad_tag',
 * - 'ptchn',
 * - 'vmap',
 * - 'xhr_apiary_host',
 * - 'apiary_host_firstparty',
 * - 'view_count',
 * - 'player_error_log_fraction',
 * - 'eventid',
 * - 'fflags',
 * - 'gpt_migration',
 * - 'ppv_remarketing_url',
 * - 'fexp',
 * - 'ad3_module',
 * - 'relative_loudness',
 * - 't',
 * - 'innertube_context_client_version',
 * - 'video_id',
 * - 'allow_below_the_player_companion',
 * - 'show_content_thumbnail',
 * - 'enablecsi',
 * - 'apiary_host',
 * - 'ldpj',
 * - 'fade_in_duration_milliseconds',
 * - 'allow_html5_ads',
 * - 'caption_translation_languages',
 * - 'gut_tag',
 * - 'loeid',
 * - 'videostats_playback_base_url',
 * - 'ad_flags',
 * - 'ismb',
 * - 'itct',
 * - 'of',
 * - 'dashmpd',
 * - 'title',
 * - 'tmi',
 * - 'gapi_hint_params',
 * - 'tag_for_child_directed',
 * - 'idpj',
 * - 'default_audio_track_index',
 * - 'as_launched_in_country',
 * - 'fade_in_start_milliseconds',
 * - 'afv_ad_tag_restricted_to_instream',
 * - 'c',
 * - 'pyv_ad_channel',
 * - 'hl',
 * - 'innertube_api_key',
 * - 'cc_contribute',
 * - 'fade_out_duration_milliseconds',
 * - 'cl',
 * - 'cr',
 * - 'allow_embed',
 * - 'token',
 * - 'dbp',
 * - 'afv_instream_max',
 * - 'plid',
 * - 'cid',
 * - 'ad_preroll',
 * - 'mpvid',
 * - 'aftv',
 * - 'probe_url',
 * - 'watermark',
 * - 'ad_logging_flag',
 * - 'no_get_video_log',
 * - 'timestamp',
 * - 'trueview',
 * - 'vm',
 * - 'is_listed',
 * - 'apply_fade_on_midrolls',
 * - 'show_pyv_in_related',
 * - 'thumbnail_url',
 * - 'account_playback_token',
 * - 'host_language',
 * - 'excluded_ads',
 * - 'core_dbp',
 * - 'csi_page_type',
 * - 'keywords',
 * - 'cver',
 * - 'innertube_api_version',
 * - 'caption_tracks',
 * - 'enabled_engage_types',
 * - 'encoded_ad_safety_reason',
 * - 'sffb',
 * - 'pltype',
 * - 'dclk',
 * - 'oid',
 * - 'player_response',
 * - 'cafe_experiment_id',
 * - 'fade_out_start_milliseconds',
 * - 'midroll_freqcap',
 * - 'swf_player_response',
 * - 'author',
 * - 'avg_rating',
 *
 * Possible array keys in an error response could be:
 * - 'status',
 * - 'errorcode',
 * - 'reason',
 * - 'errordetail',
 */
class VideoInfo
{
	/**
	 * Creates a VideoInfo from string
	 *
	 * @param string $video_info
	 * @return VideoInfo
	 */
	public static function createFromStringWithConfig($string, Config $config)
	{
		parse_str($string, $video_info);

		return new self($video_info, $config);
	}

	/**
	 * Creates a VideoInfo from string
	 *
	 * @deprecated since version 0.2, to be removed in 0.3. Use VideoInfo::createFromStringWithConfig() instead
	 *
	 * @param string $video_info
	 * @return VideoInfo
	 */
	public static function createFromString($string)
	{
		@trigger_error(__METHOD__ . ' is deprecated since version 0.2, to be removed in 0.3. Use VideoInfo::createFromStringWithConfig() instead', E_USER_DEPRECATED);

		parse_str($string, $video_info);

		return new self($video_info);
	}

	/**
	 * @var Format[]
	 */
	private $formats = [];

	/**
	 * @var Format[]
	 */
	private $adaptive_formats = [];

	/**
	 * @var array
	 */
	private $data = [];

	/**
	 * Set the necessary keys
	 */
	private $allowed_keys = [
		'video_id',
		'status',
		'reason',
		'thumbnail_url',
		'title',
		'url_encoded_fmt_stream_map',
		'adaptive_fmts',
	];

	/**
	 * Creates a VideoInfo from an array
	 *
	 * @param array $video_info
	 * @param Config $config
	 * @return self
	 */
	private function __construct(array $video_info, Config $config = null)
	{
		// BC: Create config array
		if ($config === null)
		{
			$config = [
				'decipher_signature' => false,
			];
		}
		else {
			$config = [
				'decipher_signature' => $config->get('enable_youtube_decipher_signature'),
			];
		}

		foreach ($this->allowed_keys as $key)
		{
			if ( isset($video_info[$key]) )
			{
				$this->data[$key] = $video_info[$key];
			}
			else
			{
				$this->data[$key] = null;
			}
		}

		// get the url_encoded_fmt_stream_map, and explode on comma
		$formats = explode(',', $this->data['url_encoded_fmt_stream_map']);
		$adaptive_formats = explode(',', $this->data['adaptive_fmts']);

		$this->formats = $this->parseFormats($formats, $config);
		$this->adaptive_formats = $this->parseFormats($adaptive_formats, $config);
	}

	/**
	 * Parses an array of formats
	 *
	 * @param array $format_array
	 * @param array $config
	 * @return array
	 */
	private function parseFormats(array $format_array, array $config)
	{
		$formats = [];

		if (count($format_array) === 1 and $format_array[0] === '' )
		{
			return $formats;
		}

		foreach ($format_array as $format)
		{
			parse_str($format, $format_info);

			if ( count($format_info) <= 1 )
			{
				continue;
			}

			$formats[] = Format::createFromArray($this, $format_info, $config);
		}

		return $formats;
	}

	/**
	 * Get the video_id
	 *
	 * @return string
	 */
	public function getVideoId()
	{
		return $this->data['video_id'];
	}

	/**
	 * Get the status
	 *
	 * @return string
	 */
	public function getStatus()
	{
		return $this->data['status'];
	}

	/**
	 * Get the error reason
	 *
	 * @return string
	 */
	public function getErrorReason()
	{
		return $this->data['reason'];
	}

	/**
	 * Get the thumbnail_url
	 *
	 * @return string
	 */
	public function getThumbnailUrl()
	{
		return $this->data['thumbnail_url'];
	}

	/**
	 * Get the title
	 *
	 * @return string
	 */
	public function getTitle()
	{
		return $this->data['title'];
	}

	/**
	 * Get the cleaned title
	 *
	 * @return string
	 */
	public function getCleanedTitle()
	{
		// Removes non-alphanumeric and unicode character.
	    $title = preg_replace('/[^A-Za-z0-9]+/', '-', $this->getTitle());
		return trim($title, "-");
	}

	/**
	 * Get the Formats
	 *
	 * @return Format[] array with Format instances
	 */
	public function getFormats()
	{
		return $this->formats;
	}

	/**
	 * Get the adaptive Formats
	 *
	 * @return Format[] array with Format instances
	 */
	public function getAdaptiveFormats()
	{
		return $this->adaptive_formats;
	}

	/**
	 * Get the url_encoded_fmt_stream_map
	 *
	 * @deprecated since version 0.2, to be removed in 0.3. Use VideoInfo::getFormats() instead
	 *
	 * @return string
	 */
	public function getStreamMapString()
	{
		@trigger_error(__METHOD__ . ' is deprecated since version 0.2, to be removed in 0.3. Use VideoInfo::getFormats() instead', E_USER_DEPRECATED);

		return $this->data['url_encoded_fmt_stream_map'];
	}

	/**
	 * Get the adaptive_fmts
	 *
	 * @deprecated since version 0.2, to be removed in 0.3. Use VideoInfo::getAdaptiveFormats() instead
	 *
	 * @return string
	 */
	public function getAdaptiveFormatsString()
	{
		@trigger_error(__METHOD__ . ' is deprecated since version 0.2, to be removed in 0.3. Use VideoInfo::getAdaptiveFormats() instead', E_USER_DEPRECATED);

		return $this->data['adaptive_fmts'];
	}
}
