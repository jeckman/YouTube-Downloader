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
