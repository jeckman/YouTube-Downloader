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

namespace YoutubeDownloader\Application;

use YoutubeDownloader\VideoInfo\VideoInfo;

/**
 * The actract controller
 */
abstract class ControllerAbstract implements Controller
{
    private $app;

    /**
     * Create the controller
     *
     * @param YoutubeDownloader\Container\App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;

        $this->get('logger')->debug(
            '{controller_name} created',
            ['controller_name' => get_class($this)]
        );
    }

    /**
     * Get an entry from the container
     *
     * @param string $id
     *
     * @return mixed
     */
    protected function get($id)
    {
        return $this->app->getContainer()->get($id);
    }

    /**
     * Returns the app version
     *
     * @return string
     */
    protected function getAppVersion()
    {
        return $this->app->getVersion();
    }

    /**
     * Echos an error and exit
     *
     * @param string $message
     */
    protected function responseWithErrorMessage($message)
    {
        $template = $this->get('template');

        echo $template->render('error.php', [
            'app_version' => $this->getAppVersion(),
            'error_message' => strval($message),
        ]);

        exit;
    }

    protected function getSize($url, $config, $toolkit)
    {
        $request = $this->get('httpclient')->createFullRequest('HEAD', $url);

        $options = ['curl' => []];
        $options['curl'][CURLOPT_NOBODY] = true;
        $options['curl'][CURLOPT_TIMEOUT] = 3;
        $options['curl'][CURLOPT_SSL_VERIFYPEER] = false;

        if ($config->get('multipleIPs') === true) {
            $options['curl'][CURLOPT_INTERFACE] = $toolkit->getRandomIp($config);
        }

        $this->get('logger')->debug(sprintf(
            'Run HTTP request for "%s %s"',
            $request->getMethod(),
            $request->getRequestTarget()
        ));

        $response = $this->get('httpclient')->send($request, $options);

        return intval($response->getHeaderLine('Content-Length'));
    }

    /**
     * Check if a user-agent is chrome
     *
     * @param string $agent
     *
     * @return bool
     */
    public function isUseragentChrome($agent)
    {
        // if user agent is google chrome
        if (preg_match("/like\sGecko\)\sChrome\//", $agent)) {
            // but not Iron
            if (!strstr($agent, 'Iron')) {
                return true;
            }
        }

        // if isn't chrome return false
        return false;
    }

    /**
     * Get the full info for a specific format
     *
     * @param array  $avail_formats
     * @param string $format
     *
     * @return string|null
     */
    protected function getFullInfoByFormat(VideoInfo $video_info, $format)
    {
        $target_formats = [];

        switch ($format) {
            case 'best':
                /* largest formats first */
                $target_formats = ['38', '37', '46', '22', '45', '35', '44', '34', '18', '43', '6', '5', '17', '13'];

                break;
            case 'free':
                /* Here we include WebM but prefer it over FLV */
                $target_formats = ['38', '46', '37', '45', '22', '44', '35', '43', '34', '18', '6', '5', '17', '13'];

                break;
            case 'ipad':
                /* here we leave out WebM video and FLV - looking for MP4 */
                $target_formats = ['37', '22', '18', '17'];

                break;
            default:
                /* If they passed in a number use it */
                if (is_numeric($format)) {
                    $target_formats[] = $format;
                } else {
                    $target_formats = ['38', '37', '46', '22', '45', '35', '44', '34', '18', '43', '6', '5', '17', '13'];
                }

                break;
        }

        /* Now we need to find our best format in the list of available formats */
        $best_format = null;

        $avail_formats = $video_info->getFormats() + $video_info->getAdaptiveFormats();

        foreach ($target_formats as $target_format) {
            foreach ($avail_formats as $format) {
                if ($target_format == $format->getItag()) {
                    $best_format = $format;

                    break 2;
                }
            }
        }

        if ($best_format === '') {
            return null;
        }

        return $best_format;
    }

    /**
     * Get the download url for a specific format
     *
     * @param array  $avail_formats
     * @param string $format
     *
     * @return string|null
     */
    protected function getDownloadUrlByFormat(VideoInfo $video_info, $format)
    {
        $target_formats = [];

        switch ($format) {
            case 'best':
                /* largest formats first */
                $target_formats = ['38', '37', '46', '22', '45', '35', '44', '34', '18', '43', '6', '5', '17', '13'];

                break;
            case 'free':
                /* Here we include WebM but prefer it over FLV */
                $target_formats = ['38', '46', '37', '45', '22', '44', '35', '43', '34', '18', '6', '5', '17', '13'];

                break;
            case 'ipad':
                /* here we leave out WebM video and FLV - looking for MP4 */
                $target_formats = ['37', '22', '18', '17'];

                break;
            default:
                /* If they passed in a number use it */
                if (is_numeric($format)) {
                    $target_formats[] = $format;
                } else {
                    $target_formats = ['38', '37', '46', '22', '45', '35', '44', '34', '18', '43', '6', '5', '17', '13'];
                }

                break;
        }

        /* Now we need to find our best format in the list of available formats */
        $best_format = null;

        $avail_formats = $video_info->getFormats() + $video_info->getAdaptiveFormats();

        foreach ($target_formats as $target_format) {
            foreach ($avail_formats as $format) {
                if ($target_format == $format->getItag()) {
                    $best_format = $format;

                    break 2;
                }
            }
        }

        $redirect_url = null;

        if ($best_format === '') {
            return null;
        }

        $redirect_url = $best_format->getUrl();

        if (! empty($redirect_url)) {
            $redirect_url .= '&title=' . $video_info->getCleanedTitle();
        }

        return $redirect_url;
    }

    /**
     * Get the type for a specific format
     *
     * @param array  $avail_formats
     * @param string $format
     *
     * @return string|null
     */
    protected function getTypeByFormat(VideoInfo $video_info, $format)
    {
        $target_formats = [];

        switch ($format) {
            case 'best':
                /* largest formats first */
                $target_formats = ['38', '37', '46', '22', '45', '35', '44', '34', '18', '43', '6', '5', '17', '13'];

                break;
            case 'free':
                /* Here we include WebM but prefer it over FLV */
                $target_formats = ['38', '46', '37', '45', '22', '44', '35', '43', '34', '18', '6', '5', '17', '13'];

                break;
            case 'ipad':
                /* here we leave out WebM video and FLV - looking for MP4 */
                $target_formats = ['37', '22', '18', '17'];

                break;
            default:
                /* If they passed in a number use it */
                if (is_numeric($format)) {
                    $target_formats[] = $format;
                } else {
                    $target_formats = ['38', '37', '46', '22', '45', '35', '44', '34', '18', '43', '6', '5', '17', '13'];
                }

                break;
        }

        /* Now we need to find our best format in the list of available formats */
        $best_format = null;

        $avail_formats = $video_info->getFormats() + $video_info->getAdaptiveFormats();

        foreach ($target_formats as $target_format) {
            foreach ($avail_formats as $format) {
                if ($target_format == $format->getItag()) {
                    $best_format = $format;

                    break 2;
                }
            }
        }

        $format = null;

        if ($best_format === '') {
            return null;
        }

        $format = $best_format->getType();

        if (! empty($redirect_url)) {
            $format .= '&title=' . $video_info->getCleanedTitle();
        }

        return $format;
    }

    /**
     * Format a byte integer into a human readable string
     *
     * e.g. 1024 => 1kB
     *
     * @param int $bytes
     * @param int $precision
     *
     * @return string
     */
    protected function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . '' . $units[$pow];
    }
}
