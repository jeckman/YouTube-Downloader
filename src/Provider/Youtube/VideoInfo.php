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

namespace YoutubeDownloader\Provider\Youtube;

use YoutubeDownloader\Cache\Cache;
use YoutubeDownloader\Cache\CacheAware;
use YoutubeDownloader\Cache\CacheAwareTrait;
use YoutubeDownloader\Config;
use YoutubeDownloader\Http\HttpClientAware;
use YoutubeDownloader\Http\HttpClientAwareTrait;
use YoutubeDownloader\Logger\LoggerAware;
use YoutubeDownloader\Logger\LoggerAwareTrait;
use YoutubeDownloader\VideoInfo\VideoInfo as VideoInfoInterface;

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
class VideoInfo implements VideoInfoInterface, CacheAware, HttpClientAware, LoggerAware
{
	use CacheAwareTrait;
	use HttpClientAwareTrait;
	use LoggerAwareTrait;

	/**
	 * Creates a VideoInfo from string with an options array
	 *
	 * @param string $video_info
	 * @param array $options
	 * @return VideoInfo
	 */
	public static function createFromStringWithOptions($string, array $options)
	{
		$default = [
			'decipher_signature' => false,
		];

		foreach ($default as $key => $value)
		{
			if ( ! array_key_exists($key, $options))
			{
				$options[$key] = $value;
			}
		}

		parse_str($string, $video_info);

		return new self($video_info, $options);
	}

	/**
	 * Creates a VideoInfo from string
	 *
	 * @deprecated since version 0.6, to be removed in 0.7. Use YoutubeDownloader\Provider\Youtube\VideoInfo::createFromStringWithOptions() instead
	 *
	 * @param string $video_info
	 * @param Config $config
	 * @return VideoInfo
	 */
	public static function createFromStringWithConfig($string, Config $config)
	{
		@trigger_error(__METHOD__ . ' is deprecated since version 0.6, to be removed in 0.7. Use YoutubeDownloader\Provider\Youtube\VideoInfo::createFromStringWithOptions() instead', E_USER_DEPRECATED);

		$options = [];

		// Create options array
		if ($config !== null)
		{
			$options['decipher_signature'] = $config->get('enable_youtube_decipher_signature');
		}

		return static::createFromStringWithOptions($string, $options);
	}

	/**
	 * @var array
	 */
	private $options;

	/**
	 * @var Format[]
	 */
	private $formats;

	/**
	 * @var Format[]
	 */
	private $adaptive_formats;

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
	 * @param array $options
	 * @return self
	 */
	private function __construct(array $video_info, array $options)
	{
		$this->options = $options;

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

			$format = Format::createFromArray($this, $format_info, $config);

			if ( $format instanceOf CacheAware )
			{
				$format->setCache($this->getCache());
			}

			if ( $format instanceOf HttpClientAware )
			{
				$format->setHttpClient($this->getHttpClient());
			}

			if ( $format instanceOf LoggerAware )
			{
				$format->setLogger($this->getLogger());
			}

			$formats[] = $format;
		}

		return $formats;
	}

	/**
	 * Get the Provider-ID, e.g. 'youtube', 'vimeo', etc
	 *
	 * @return string
	 */
	public function getProviderId()
	{
		return 'youtube';
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
		if ( $this->formats === null )
		{
			// get the url_encoded_fmt_stream_map, and explode on comma
			$formats = explode(',', $this->data['url_encoded_fmt_stream_map']);
			$this->formats = $this->parseFormats($formats, $this->options);
		}

		return $this->formats;
	}

	/**
	 * Get the adaptive Formats
	 *
	 * @return Format[] array with Format instances
	 */
	public function getAdaptiveFormats()
	{
		if ( $this->adaptive_formats === null )
		{
			// get the adaptive_fmts, and explode on comma
			$adaptive_formats = explode(',', $this->data['adaptive_fmts']);
			$this->adaptive_formats = $this->parseFormats($adaptive_formats, $this->options);
		}

		return $this->adaptive_formats;
	}
}
