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

use YoutubeDownloader\Http\Message\Request as RequestInterface;
use YoutubeDownloader\Http\Message\Response as ResponseInterface;

/**
 * A curl http client instance
 */
class CurlClient implements Client
{
	/**
	 * Factory for a new Request
	 *
	 * @param string $method HTTP method
	 * @param string $target The target url for this request
	 * @param array $headers Request headers
	 * @param string|null $body Request body
	 * @param string $version Protocol version
	 * @return Request
	 */
	public function createRequest($method, $target, array $headers = [], $body = null, $version = '1.1')
	{
		return new Request($method, $target, $headers, $body, $version);
	}

	/**
	 * Sends a Request and returns a Response
	 *
	 * $options can be used to set client specific data per request, like curl options
	 *
	 * @param Request $request,
	 * @param array $options client specific options for a client instance
	 * @return Response
	 */
	public function send(RequestInterface $request, array $options = [])
	{
		$curl_options = $this->createCurlOptions($request, $options);

		$curl_handler = curl_init();

		$http_response = $this->getHttpResponseFromCurl($curl_handler, $curl_options);

		curl_close($curl_handler);

		return $this->createResponseFromHttp($http_response);
	}

	/**
	 * create an array with curl options
	 *
	 * @param Request $request,
	 * @param array $options client specific options for a client instance
	 * @return array the curl options
	 */
	private function createCurlOptions(Request $request, array $options = [])
	{
		$default_options = [
			CURLOPT_URL => $request->getRequestTarget(),
			CURLOPT_CUSTOMREQUEST => $request->getMethod(),
			CURLOPT_HEADER => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_CONNECTTIMEOUT => 3,
			CURLOPT_FOLLOWLOCATION => true,
		];

		switch ($request->getProtocolVersion())
		{
			case '1.1':
				$default_options[CURLOPT_HTTP_VERSION] = CURL_HTTP_VERSION_1_1;
				break;

			case '2.0':
				$default_options[CURLOPT_HTTP_VERSION] = CURL_HTTP_VERSION_2_0;
				break;

			default:
				$default_options[CURLOPT_HTTP_VERSION] = CURL_HTTP_VERSION_1_0;
				break;
		}

		foreach ($request->getHeaders() as $name => $values)
		{
			foreach ($values as $value)
			{
				$default_options[CURLOPT_HTTPHEADER][] = $name . ': ' . $value;
			}
		}

		$curl_options = (isset($options['curl'])) ? $options['curl'] : [];

		if ( ! is_array($curl_options) )
		{
			// Curls options must be an array
			$curl_options = [];
		}

		return array_replace($default_options, $curl_options);
	}

	/**
	 * get the raw http response from a curl handler
	 *
	 * @param resource $curl_handler The curl handler
	 * @param array $options the curl options
	 * @return string
	 */
	private function getHttpResponseFromCurl($curl_handler, array $curl_options = [])
	{
		$curl_returns_response = ((bool) $curl_options[CURLOPT_RETURNTRANSFER] === true);

		curl_setopt_array($curl_handler, $curl_options);

		// enable output buffer if needed
		if ( ! $curl_returns_response )
		{
			ob_start();
		}

		$curl_response = curl_exec($curl_handler);

		// enable output buffer if needed
		if ( ! $curl_returns_response )
		{
			$body = ob_get_contents();
			ob_end_clean();
		}
		else
		{
			$body = $curl_response;
		}

		// Handle errors
		if ( $curl_response === false )
		{
			$e = new \Exception(curl_error($curl_handler));
			curl_close($curl_handler);

			throw new \RuntimeException('A curl error occurs while execute the curl handler.', $e->getCode(), $e);
		}

		return $body;
	}

	/**
	 * create an Response from a raw http response
	 *
	 * @param string $http_response The http response
	 * @return Response
	 */
	private function createResponseFromHttp($http_response)
	{
		$response_parts = explode("\r\n\r\n", $http_response);
		$raw_headers = explode("\r\n", $response_parts[0]);

		$status_parts = $this->parseStatusCodeLine(array_shift($raw_headers));

		$response = new Response(
			$status_parts['code'],
			[],
			str_replace($response_parts[0] . "\r\n\r\n", '', $http_response),
			$status_parts['protocol'],
			$status_parts['phrase']
		);

		foreach ($raw_headers as $raw_header)
		{
			$header = explode(': ', $raw_header);
			$response = $response->withAddedHeader($header[0], $header[1]);
		}

		return $response;
	}

	/**
	 * Parse the status code line
	 *
	 * Examples:
	 * 'HTTP/1.1 200 OK'
	 * 'HTTP/1.1 404 Not Found'
	 *
	 * @param string $line
	 * @return string[] An array with code, phrase and protocol keys
	 */
	private function parseStatusCodeLine($line)
	{
		$raw_status_parts = explode(' ', $line);

		$protocol_version_parts = explode('/', array_shift($raw_status_parts));

		return [
			'code' => array_shift($raw_status_parts),
			'phrase' => implode(' ', $raw_status_parts),
			'protocol' => $protocol_version_parts[1],
		];
	}
}
