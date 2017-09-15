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

namespace YoutubeDownloader\Http;

use InvalidArgumentException;
use YoutubeDownloader\Http\Message\Stream;

/**
 * Trait implementing functionality common to requests and responses.
 *
 * HTTP messages consist of requests from a client to a server and responses
 * from a server to a client. This interface defines the methods common to
 * each.
 *
 * Messages are considered immutable; all methods that might change state MUST
 * be implemented such that they retain the internal state of the current
 * message and return an instance that contains the changed state.
 *
 * @see http://www.ietf.org/rfc/rfc7230.txt
 * @see http://www.ietf.org/rfc/rfc7231.txt
 */
trait MessageTrait
{
	/**
	 * @var array of all registered header
	 */
	private $headers = [];

	/**
	 * @var string
	 */
	private $protocol = '1.1';

	/**
	 * @var string
	 */
	private $body = '';

	/**
	 * Retrieves the HTTP protocol version as a string.
	 *
	 * The string MUST contain only the HTTP version number (e.g., "1.1", "1.0").
	 *
	 * @return string HTTP protocol version.
	 */
	public function getProtocolVersion()
	{
		return $this->protocol;
	}

	/**
	 * Return an instance with the specified HTTP protocol version.
	 *
	 * The version string MUST contain only the HTTP version number (e.g.,
	 * "1.1", "1.0").
	 *
	 * This method MUST be implemented in such a way as to retain the
	 * immutability of the message, and MUST return an instance that has the
	 * new protocol version.
	 *
	 * @param string $version HTTP protocol version
	 * @return static
	 */
	public function withProtocolVersion($version)
	{
		$clone = clone $this;
		$clone->protocol = $version;

		return $clone;
	}

	/**
	 * Retrieves all message header values.
	 *
	 * The keys represent the header name as it will be sent over the wire, and
	 * each value is an array of strings associated with the header.
	 *
	 *     // Represent the headers as a string
	 *     foreach ($message->getHeaders() as $name => $values) {
	 *         echo $name . ': ' . implode(', ', $values);
	 *     }
	 *
	 *     // Emit headers iteratively:
	 *     foreach ($message->getHeaders() as $name => $values) {
	 *         foreach ($values as $value) {
	 *             header(sprintf('%s: %s', $name, $value), false);
	 *         }
	 *     }
	 *
	 * While header names are not case-sensitive, getHeaders() will preserve the
	 * exact case in which headers were originally specified.
	 *
	 * @return string[][] Returns an associative array of the message's headers.
	 *     Each key MUST be a header name, and each value MUST be an array of
	 *     strings for that header.
	 */
	public function getHeaders()
	{
		return $this->headers;
	}

	/**
	 * Checks if a header exists by the given case-insensitive name.
	 *
	 * @param string $name Case-insensitive header field name.
	 * @return bool Returns true if any header names match the given header
	 *     name using a case-insensitive string comparison. Returns false if
	 *     no matching header name is found in the message.
	 */
	public function hasHeader($name)
	{
		$name = strtolower($name);

		$header_names = $this->getHeaderNames();

		return isset($header_names[$name]);
	}

	/**
	 * Retrieves a message header value by the given case-insensitive name.
	 *
	 * This method returns an array of all the header values of the given
	 * case-insensitive header name.
	 *
	 * If the header does not appear in the message, this method MUST return an
	 * empty array.
	 *
	 * @param string $name Case-insensitive header field name.
	 * @return string[] An array of string values as provided for the given
	 *    header. If the header does not appear in the message, this method MUST
	 *    return an empty array.
	 */
	public function getHeader($name)
	{
		$name = strtolower($name);

		$header_names = $this->getHeaderNames();

		if ( ! isset($header_names[$name]) )
		{
			return [];
		}

		$header_name = $header_names[$name];

		return $this->headers[$header_name];
	}

	/**
	 * Retrieves a comma-separated string of the values for a single header.
	 *
	 * This method returns all of the header values of the given
	 * case-insensitive header name as a string concatenated together using
	 * a comma.
	 *
	 * NOTE: Not all header values may be appropriately represented using
	 * comma concatenation. For such headers, use getHeader() instead
	 * and supply your own delimiter when concatenating.
	 *
	 * If the header does not appear in the message, this method MUST return
	 * an empty string.
	 *
	 * @param string $name Case-insensitive header field name.
	 * @return string A string of values as provided for the given header
	 *    concatenated together using a comma. If the header does not appear in
	 *    the message, this method MUST return an empty string.
	 */
	public function getHeaderLine($name)
	{
		return implode(', ', $this->getHeader($name));
	}

	/**
	 * Return an instance with the provided value replacing the specified header.
	 *
	 * While header names are case-insensitive, the casing of the header will
	 * be preserved by this function, and returned from getHeaders().
	 *
	 * This method MUST be implemented in such a way as to retain the
	 * immutability of the message, and MUST return an instance that has the
	 * new and/or updated header and value.
	 *
	 * @param string $name Case-insensitive header field name.
	 * @param string|string[] $value Header value(s).
	 * @return static
	 * @throws \InvalidArgumentException for invalid header names or values.
	 */
	public function withHeader($name, $value)
	{
		if ( ! is_array($value) )
		{
			$value = [$value];
		}

		$values = [];

		foreach ($value as $val)
		{
			$values[] = trim($val);
		}

		$clone = clone $this;
		$clone->headers[$name] = $values;

		return $clone;
	}

	/**
	 * Return an instance with the specified header appended with the given value.
	 *
	 * Existing values for the specified header will be maintained. The new
	 * value(s) will be appended to the existing list. If the header did not
	 * exist previously, it will be added.
	 *
	 * This method MUST be implemented in such a way as to retain the
	 * immutability of the message, and MUST return an instance that has the
	 * new header and/or value.
	 *
	 * @param string $name Case-insensitive header field name to add.
	 * @param string|string[] $value Header value(s).
	 * @return static
	 * @throws \InvalidArgumentException for invalid header names.
	 * @throws \InvalidArgumentException for invalid header values.
	 */
	public function withAddedHeader($name, $value)
	{
		if ( ! is_array($value) )
		{
			$value = [$value];
		}

		$values = [];

		foreach ($value as $val)
		{
			$values[] = trim($val);
		}

		$header_names = $this->getHeaderNames();

		if ( isset($header_names[strtolower($name)]) )
		{
			$header_name = $header_names[strtolower($name)];
			$existing_value = $this->headers[$header_name];
		}
		else
		{
			$header_name = $name;
			$existing_value = [];
		}

		$clone = clone $this;
		$clone->headers[$header_name] = array_merge($existing_value, $values);

		return $clone;
	}

	/**
	 * Return an instance without the specified header.
	 *
	 * Header resolution MUST be done without case-sensitivity.
	 *
	 * This method MUST be implemented in such a way as to retain the
	 * immutability of the message, and MUST return an instance that removes
	 * the named header.
	 *
	 * @param string $name Case-insensitive header field name to remove.
	 * @return static
	 */
	public function withoutHeader($name)
	{
		$name = strtolower($name);

		$header_names = $this->getHeaderNames();

		if ( ! isset($header_names[$name]) )
		{
			return $this;
		}

		$header = $header_names[$name];

		$clone = clone $this;
		unset($clone->headers[$header]);

		return $clone;
	}

	/**
	 * Gets the raw body of the message.
	 *
	 * @return string Returns the body as a string.
	 */
	public function getBodyAsString()
	{
		return $this->body;
	}

	/**
	 * Return an instance with the specified message body.
	 *
	 * The body MUST be a string.
	 *
	 * This method MUST be implemented in such a way as to retain the
	 * immutability of the message, and MUST return a new instance that has the
	 * new body string.
	 *
	 * @param string $body Body.
	 * @return static
	 * @throws \InvalidArgumentException When the body is not valid.
	 */
	public function withStringAsBody($body)
	{
		if ( ! is_string($body) )
		{
			throw new InvalidArgumentException(sprintf(
				'Argument #1 $body must be of type string, but "%s" was given',
				gettype($body)
			));
		}

		if ( $this->body === $body )
		{
			return $this;
		}

		$clone = clone $this;
		$clone->body = $body;

		return $clone;
	}

	/**
	 * Returns all header names in lower case
	 *
	 * @return static
	 */
	public function getHeaderNames()
	{
		$header_names = [];

		foreach ($this->headers as $name => $value)
		{
			$header_name = strtolower($name);
			$header_names[$header_name] = $name;
		}

		return $header_names;
	}
}
