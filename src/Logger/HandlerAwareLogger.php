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

use DateTime;
use Psr\Log\AbstractLogger;
use YoutubeDownloader\Logger\Handler\Entry;
use YoutubeDownloader\Logger\Handler\Handler;
use YoutubeDownloader\Logger\Handler\SimpleEntry;

/**
 * a logger instance, that works with handler
 */
class HandlerAwareLogger extends AbstractLogger
{
    /**
     * @var YoutubeDownloader\Logger\Handler\Handler[]
     */
    private $handlers = [];

    /**
     * This logger needs at least a handler
     *
     * @param YoutubeDownloader\Logger\Handler\Handler $handler
     *
     * @return self
     */
    public function __construct(Handler $handler)
    {
        $this->addHandler($handler);
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed  $level
     * @param string $message
     * @param array  $context
     */
    public function log($level, $message, array $context = [])
    {
        $entry = $this->createEntry(
            new DateTime('now'),
            $level,
            $message,
            $context
        );

        $this->handleEntry($entry);
    }

    /**
     * Adds a handler
     *
     * @param YoutubeDownloader\Logger\Handler\Handler $handler
     */
    public function addHandler(Handler $handler)
    {
        $this->handlers[] = $handler;
    }

    /**
     * Factory for a new entry
     *
     * @param DateTime $created_at
     * @param mixed    $level
     * @param string   $message
     * @param array    $context
     *
     * @return YoutubeDownloader\Logger\Handler\Entry
     */
    private function createEntry(DateTime $created_at, $level, $message, array $context = [])
    {
        return new SimpleEntry($created_at, $level, $message, $context);
    }

    /**
     * Search for all handler that handles this entry and call them
     *
     * @param YoutubeDownloader\Logger\Handler\Entry $entry
     */
    private function handleEntry(Entry $entry)
    {
        foreach ($this->handlers as $handler) {
            if ($handler->handles($entry->getLevel())) {
                $handler->handle($entry);
            }
        }
    }
}
