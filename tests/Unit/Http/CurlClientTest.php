<?php

/*
 * PHP script for downloading videos from youtube
 * Copyright (C) 2012-2018  John Eckman
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

namespace YoutubeDownloader\Tests\Unit\Http;

use Psr\Http\Message\RequestInterface;
use YoutubeDownloader\Http\Client;
use YoutubeDownloader\Http\CurlClient;
use YoutubeDownloader\Tests\Fixture\TestCase;

class CurlClientTest extends TestCase
{
    /**
     * @test CurlClient implements interfaces
     */
    public function implementsInterfaces()
    {
        $client = new CurlClient();

        $this->assertInstanceOf(Client::class, $client);
    }

    /**
     * @test createRequest()
     */
    public function createRequest()
    {
        $client = new CurlClient();
        $request = $client->createRequest('GET', 'https://example.org');

        $this->assertInstanceOf(RequestInterface::class, $request);
    }

    /**
     * @test createFullRequest()
     */
    public function createFullRequest()
    {
        $client = new CurlClient();
        $request = $client->createFullRequest('GET', 'https://example.org');

        $this->assertInstanceOf(RequestInterface::class, $request);
    }
}
