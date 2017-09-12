<?php

namespace YoutubeDownloader\Http;

use YoutubeDownloader\Http\Message\Request as RequestInterface;
use YoutubeDownloader\Http\Message\Response as ResponseInterface;

/**
 * Describes a http client instance
 */
interface Client
{
	/**
	 * Factory for a new Request
	 *
	 * @param string $method HTTP method
	 * @param string $target The target url for this request
	 * @param array $headers Request headers
	 * @param string|null $body Request body
	 * @param string $version Protocol version
	 * @return RequestInterface
	 */
	public function createRequest($method, $target, array $headers = [], $body = null, $version = '1.1');

	/**
	 * Sends a Request and returns a Response
	 *
	 * $options can be used to set client specific data per request, like curl options
	 *
	 * @param RequestInterface $request,
	 * @param array $options client specific options for a client instance
	 * @return ResponseInterface
	 */
	public function send(RequestInterface $request, array $options = []);
}
