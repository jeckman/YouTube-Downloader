<?php

namespace YoutubeDownloader\Tests\Unit\Cache;

use YoutubeDownloader\Http\Response;
use YoutubeDownloader\Tests\Fixture\Http\Psr7ResponseAdapter;
use YoutubeDownloader\Tests\Fixture\TestCase;

class ResponseTest extends TestCase
{
	/**
	 * @test Response is compatible with Psr\Http\Message\ResponseInterface
	 */
	public function isPsr7Compatible()
	{
		$response = new Response();

		$adapter = new Psr7ResponseAdapter($response);

		$this->assertInstanceOf('\\Psr\\Http\\Message\\ResponseInterface', $adapter);
		$this->assertInstanceOf('\\YoutubeDownloader\\Http\\Message\\Response', $adapter);
	}

	/**
	 * @test getStatusCode()
	 */
	public function getStatusCode()
	{
		$response = new Response();

		$this->assertSame(200, $response->getStatusCode());
	}

	/**
	* @test getReasonPhrase()
	*/
	public function getReasonPhrase()
	{
		$response = new Response();

		$this->assertSame('', $response->getReasonPhrase());
	}

	/**
	 * @test withStatus()
	 */
	public function withStatus()
	{
		$response1 = new Response();

		$response2 = $response1->withStatus(404, 'Not Found');

		$this->assertFalse($response1 === $response2);
		$this->assertSame(404, $response2->getStatusCode());
		$this->assertSame('Not Found', $response2->getReasonPhrase());
	}
}
