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

use YoutubeDownloader\Http\Message\Response as ResponseInterface;

/**
 * This interface must be compatible with PSR-7 Psr\Http\Message\ResponseInterface
 *
 * Representation of an outgoing, server-side response.
 *
 * Per the HTTP specification, this interface includes properties for
 * each of the following:
 *
 * - Protocol version
 * - Status code and reason phrase
 * - Headers
 * - Message body
 *
 * Responses are considered immutable; all methods that might change state MUST
 * be implemented such that they retain the internal state of the current
 * message and return an instance that contains the changed state.
 */
class Response implements ResponseInterface
{
	use MessageTrait;

	/**
	 * @var string The status code
	 */
	private $code = 200;

	/**
	 * @var string The reason phrase
	 */
	private $reason = 'OK';

	/**
	 * @param int $code Status code
	 * @param array $headers Response headers
	 * @param string|null $body Response body
	 * @param string $protocol Protocol version
	 * @param string $reason Reason phrase
	 */
	public function __construct(
		$code = 200,
		array $headers = [],
		$body = null,
		$protocol = '1.1',
		$reason = ''
	)
	{
		$this->code = intval($code);
		$this->body = strval($body);
		$this->protocol = strval($protocol);
		$this->reason = strval($reason);

		foreach ($headers as $header_name => $value)
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

			$this->headers[$header_name] = $values;
		}
	}

	/**
	 * Gets the response status code.
	 *
	 * The status code is a 3-digit integer result code of the server's attempt
	 * to understand and satisfy the request.
	 *
	 * @return int Status code.
	 */
	public function getStatusCode()
	{
		return $this->code;
	}

	/**
	 * Return an instance with the specified status code and, optionally, reason phrase.
	 *
	 * If no reason phrase is specified, implementations MAY choose to default
	 * to the RFC 7231 or IANA recommended reason phrase for the response's
	 * status code.
	 *
	 * This method MUST be implemented in such a way as to retain the
	 * immutability of the message, and MUST return an instance that has the
	 * updated status and reason phrase.
	 *
	 * @see http://tools.ietf.org/html/rfc7231#section-6
	 * @see http://www.iana.org/assignments/http-status-codes/http-status-codes.xhtml
	 * @param int $code The 3-digit integer result code to set.
	 * @param string $reasonPhrase The reason phrase to use with the
	 *     provided status code; if none is provided, implementations MAY
	 *     use the defaults as suggested in the HTTP specification.
	 * @return static
	 * @throws \InvalidArgumentException For invalid status code arguments.
	 */
	public function withStatus($code, $reasonPhrase = '')
	{
		$clone = clone $this;
		$clone->code = (int) $code;
		$clone->reason = (string) $reasonPhrase;

		return $clone;
	}

	/**
	 * Gets the response reason phrase associated with the status code.
	 *
	 * Because a reason phrase is not a required element in a response
	 * status line, the reason phrase value MAY be empty. Implementations MAY
	 * choose to return the default RFC 7231 recommended reason phrase (or those
	 * listed in the IANA HTTP Status Code Registry) for the response's
	 * status code.
	 *
	 * @see http://tools.ietf.org/html/rfc7231#section-6
	 * @see http://www.iana.org/assignments/http-status-codes/http-status-codes.xhtml
	 * @return string Reason phrase; must return an empty string if none present.
	 */
	public function getReasonPhrase()
	{
		return $this->reason;
	}
}
