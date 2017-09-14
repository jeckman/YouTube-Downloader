<?php

namespace YoutubeDownloader\Http;

/**
 * Describes a http client-aware instance
 */
interface HttpClientAware
{
	/**
	 * Sets a http client instance on the object
	 *
	 * @param Client $client
	 * @return null
	 */
	public function setHttpClient(Client $client);
}
