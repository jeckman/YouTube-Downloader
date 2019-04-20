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

namespace YoutubeDownloader\Logger;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Trait for logger-aware instances
 */
trait LoggerAwareTrait
{
    /**
     * @var Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * Sets a logger instance on the object
     *
     * @param Psr\Log\LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Gets a Psr\Log\LoggerInterface instance
     *
     * @return Psr\Log\LoggerInterface
     */
    public function getLogger()
    {
        if ($this->logger === null) {
            $this->logger = new NullLogger;
        }

        return $this->logger;
    }
}
