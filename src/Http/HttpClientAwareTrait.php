<?php

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
