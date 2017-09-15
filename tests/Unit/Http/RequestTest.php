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

use YoutubeDownloader\Http\Request;
use YoutubeDownloader\Tests\Fixture\Http\Psr7RequestAdapter;
use YoutubeDownloader\Tests\Fixture\TestCase;

class RequestTest extends TestCase
{
	/**
	 * @test Request is compatible with Psr\Http\Message\RequstInterface
	 */
	public function isPsr7Compatible()
	{
		$request = new Request('GET', 'https://example.org');

		$adapter = new Psr7RequestAdapter($request);

		$this->assertInstanceOf('\\Psr\\Http\\Message\\RequestInterface', $adapter);
		$this->assertInstanceOf('\\YoutubeDownloader\\Http\\Message\\Request', $adapter);
	}

	/**
	 * @test getRequestTarget()
	 */
	public function getRequestTarget()
	{
		$request = new Request('GET', 'https://example.org');

		$this->assertSame('https://example.org', $request->getRequestTarget());
	}

	/**
	 * @test withRequestTarget()
	 */
	public function withRequestTarget()
	{
		$request1 = new Request('GET', 'https://example.org');

		$request2 = $request1->withRequestTarget('https://example.com');

		$this->assertFalse($request1 === $request2);
		$this->assertSame('https://example.com', $request2->getRequestTarget());
	}

	/**
	 * @test getMethod()
	 */
	public function getMethod()
	{
		$request = new Request('get', 'https://example.org');

		$this->assertSame('GET', $request->getMethod());
	}

	/**
	 * @test withMethod()
	 */
	public function withMethod()
	{
		$request1 = new Request('GET', 'https://example.org');

		$request2 = $request1->withMethod('post');

		$this->assertFalse($request1 === $request2);
		$this->assertSame('POST', $request2->getMethod());
	}
}
