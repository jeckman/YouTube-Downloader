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

use Exception;
use YoutubeDownloader\VideoInfo\VideoInfo;

/**
 * Toolkit
 *
 * This class contains all functionallities that must be refactored.
 */
class Toolkit
{
    /**
     * @var string random IP
     */
    private $outgoing_ip;

    /**
     * Select random IP from config
     *
     * If multipleIPs mode is enabled, select randomly one IP from
     * the config IPs array and put it in $outgoing_ip variable.
     *
     * @param Config $config
     *
     * @return string|null The IP or null
     */
    public function getRandomIp(Config $config)
    {
        if ($config->get('multipleIPs') !== true) {
            return null;
        }

        if ($this->outgoing_ip === null) {
            // randomly select an ip from the $config->get('IPs') array
            $ips = $config->get('IPs');
            $this->outgoing_ip = $ips[mt_rand(0, count($ips) - 1)];
        }

        return $this->outgoing_ip;
    }
}
