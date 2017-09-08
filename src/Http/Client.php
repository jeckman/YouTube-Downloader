<?php

namespace YoutubeDownloader\Http;

use YoutubeDownloader\Http\Message\Request;
use YoutubeDownloader\Http\Message\Response;

/**
 * Describes a http client instance
 */
interface Client
{
	/**
	 * Factory for a new Request
	 *
	 * @return Request
	 */
	public function buildRequest();

	/**
	 * Sends a Request and returns a Response
	 *
	 * $params can be used to set client specific data per request, like curl options
	 *
	 * @param Request $request,
	 * @param array $params client specific params for a client instance
	 * @return Response
	 */
	public function send(Request $request, array $params = []);
}
