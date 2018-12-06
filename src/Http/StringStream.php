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

namespace YoutubeDownloader\Http;

use Psr\Http\Message\StreamInterface;
use YoutubeDownloader\Http\Client;

/**
 * Describes a data stream.
 *
 * Typically, an instance will wrap a PHP stream; this interface provides
 * a wrapper around the most common operations, including serialization of
 * the entire stream to a string.
 */
class StringStream implements StreamInterface
{
    private $string = '';

    public function __construct($string)
    {
        $this->string = (string) $string;
    }

    /**
     * Reads all data from the stream into a string, from the beginning to end.
     *
     * This method MUST attempt to seek to the beginning of the stream before
     * reading data and read the stream until the end is reached.
     *
     * Warning: This could attempt to load a large amount of data into memory.
     *
     * This method MUST NOT raise an exception in order to conform with PHP's
     * string casting operations.
     *
     * @see http://php.net/manual/en/language.oop5.magic.php#object.tostring
     *
     * @return string
     */
    public function __toString()
    {
        return $this->string;
    }

    /**
     * Closes the stream and any underlying resources.
     */
    public function close()
    {
        throw new \Exception(__METHOD__ . ' is not implemented');
    }

    /**
     * Separates any underlying resources from the stream.
     *
     * After the stream has been detached, the stream is in an unusable state.
     *
     * @return resource|null Underlying PHP stream, if any
     */
    public function detach()
    {
        throw new \Exception(__METHOD__ . ' is not implemented');
    }

    /**
     * Get the size of the stream if known.
     *
     * @return int|null returns the size in bytes if known, or null if unknown
     */
    public function getSize()
    {
        throw new \Exception(__METHOD__ . ' is not implemented');
    }

    /**
     * Returns the current position of the file read/write pointer
     *
     * @throws \RuntimeException on error
     *
     * @return int Position of the file pointer
     */
    public function tell()
    {
        throw new \Exception(__METHOD__ . ' is not implemented');
    }

    /**
     * Returns true if the stream is at the end of the stream.
     *
     * @return bool
     */
    public function eof()
    {
        throw new \Exception(__METHOD__ . ' is not implemented');
    }

    /**
     * Returns whether or not the stream is seekable.
     *
     * @return bool
     */
    public function isSeekable()
    {
        throw new \Exception(__METHOD__ . ' is not implemented');
    }

    /**
     * Seek to a position in the stream.
     *
     * @see http://www.php.net/manual/en/function.fseek.php
     *
     * @param int $offset Stream offset
     * @param int $whence Specifies how the cursor position will be calculated
     *                    based on the seek offset. Valid values are identical to the built-in
     *                    PHP $whence values for `fseek()`.  SEEK_SET: Set position equal to
     *                    offset bytes SEEK_CUR: Set position to current location plus offset
     *                    SEEK_END: Set position to end-of-stream plus offset.
     *
     * @throws \RuntimeException on failure
     */
    public function seek($offset, $whence = SEEK_SET)
    {
        throw new \Exception(__METHOD__ . ' is not implemented');
    }

    /**
     * Seek to the beginning of the stream.
     *
     * If the stream is not seekable, this method will raise an exception;
     * otherwise, it will perform a seek(0).
     *
     * @see seek()
     * @see http://www.php.net/manual/en/function.fseek.php
     *
     * @throws \RuntimeException on failure
     */
    public function rewind()
    {
        throw new \Exception(__METHOD__ . ' is not implemented');
    }

    /**
     * Returns whether or not the stream is writable.
     *
     * @return bool
     */
    public function isWritable()
    {
        throw new \Exception(__METHOD__ . ' is not implemented');
    }

    /**
     * Write data to the stream.
     *
     * @param string $string the string that is to be written
     *
     * @throws \RuntimeException on failure
     *
     * @return int returns the number of bytes written to the stream
     */
    public function write($string)
    {
        throw new \Exception(__METHOD__ . ' is not implemented');
    }

    /**
     * Returns whether or not the stream is readable.
     *
     * @return bool
     */
    public function isReadable()
    {
        throw new \Exception(__METHOD__ . ' is not implemented');
    }

    /**
     * Read data from the stream.
     *
     * @param int $length Read up to $length bytes from the object and return
     *                    them. Fewer than $length bytes may be returned if underlying stream
     *                    call returns fewer bytes.
     *
     * @throws \RuntimeException if an error occurs
     *
     * @return string returns the data read from the stream, or an empty string
     *                if no bytes are available
     */
    public function read($length)
    {
        throw new \Exception(__METHOD__ . ' is not implemented');
    }

    /**
     * Returns the remaining contents in a string
     *
     * @throws \RuntimeException if unable to read or an error occurs while
     *                           reading
     *
     * @return string
     */
    public function getContents()
    {
        throw new \Exception(__METHOD__ . ' is not implemented');
    }

    /**
     * Get stream metadata as an associative array or retrieve a specific key.
     *
     * The keys returned are identical to the keys returned from PHP's
     * stream_get_meta_data() function.
     *
     * @see http://php.net/manual/en/function.stream-get-meta-data.php
     *
     * @param string $key specific metadata to retrieve
     *
     * @return array|mixed|null Returns an associative array if no key is
     *                          provided. Returns a specific key value if a key is provided and the
     *                          value is found, or null if the key is not found.
     */
    public function getMetadata($key = null)
    {
        throw new \Exception(__METHOD__ . ' is not implemented');
    }
}
