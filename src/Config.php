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

namespace YoutubeDownloader;

@trigger_error('The ' . __NAMESPACE__ . '\Config class is deprecated since version 0.7 and will be removed in 0.8. Use the YoutubeDownloader\Config\TransformationConfig class instead.', E_USER_DEPRECATED);

use YoutubeDownloader\Config\Config as ConfigInterface;
use YoutubeDownloader\Config\FileLoader;
use YoutubeDownloader\Config\TransformationConfig;

/**
 * Config class
 *
 * @deprecated since version 0.7, to be removed in 0.8. Use `YoutubeDownloader\Config\TransformationConfig` instead
 */
class Config implements ConfigInterface
{
    /**
     * Creates the config from files
     *
     * @param string $default full path to default config php file
     * @param string $custom  full path to custom config php file
     *
     * @return Config
     */
    public static function createFromFiles($default, $custom = null)
    {
        $args = [new FileLoader($default)];

        if ($custom !== null) {
            $args[] = new FileLoader($custom);
        }

        $config = call_user_func_array(
            [TransformationConfig::class, 'createFromLoaders'],
            $args
        );

        return new self($config);
    }

    private $config;

    /**
     * Creates a Config from another config
     *
     * @param array $config
     *
     * @return self
     */
    private function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * Get a config value
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get($key)
    {
        return $this->config->get($key);
    }
}
