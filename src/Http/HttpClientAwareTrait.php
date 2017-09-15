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

/**
 * Trait for http client-aware instances
 */
trait HttpClientAwareTrait
{
	/**
	 * @var YoutubeDownloader\Http\Client
	 */
	private $http_client;

	/**
	 * Sets a http client instance on the object
	 *
	 * @param Client $client
	 * @return null
	 */
	public function setHttpClient(Client $client)
	{
		$this->http_client = $client;
	}

	/**
	 * Gets a http client instance
	 *
	 * @return Client
	 */
	public function getHttpClient()
	{
		if ( $this->http_client === null )
		{
			$this->http_client = new CurlClient;
		}

		return $this->http_client;
	}
}
