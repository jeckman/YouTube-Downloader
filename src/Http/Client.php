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
