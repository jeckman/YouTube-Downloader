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

use YoutubeDownloader\Http\MessageTrait;
use YoutubeDownloader\Tests\Fixture\Http\Message;
use YoutubeDownloader\Tests\Fixture\Http\Psr7MessageAdapter;
use YoutubeDownloader\Tests\Fixture\TestCase;

class MessageTraitTest extends TestCase
{
	/**
	 * @test Message is compatible with Psr\Http\Message\MessageInterface
	 */
	public function isPsr7Compatible()
	{
		$message = new Message();

		$adapter = new Psr7MessageAdapter($message);

		$this->assertInstanceOf('\\Psr\\Http\\Message\\MessageInterface', $adapter);
		$this->assertInstanceOf('\\YoutubeDownloader\\Http\\Message\\Message', $adapter);
	}

	/**
	 * @test getProtocolVersion()
	 */
	public function getProtocolVersion()
	{
		$message = new Message;

		$this->assertSame('1.1', $message->getProtocolVersion());
	}

	/**
	 * @test withProtocolVersion()
	 */
	public function withProtocolVersion()
	{
		$message = new Message;

		$message2 = $message->withProtocolVersion('1.0');

		$this->assertFalse($message === $message2);
		$this->assertSame('1.0', $message2->getProtocolVersion());
	}

	/**
	 * @test getHeaders()
	 */
	public function testSetAndGetHeaders()
	{
		$message1 = new Message;

		$this->assertSame([], $message1->getHeaders());
		$this->assertFalse($message1->hasHeader('Test'));
		$this->assertSame([], $message1->getHeader('test'));
		$this->assertSame('', $message1->getHeaderLine('test'));

		// Add Header
		$message2 = $message1->withHeader('test', 'foobar');
		$this->assertFalse($message1 === $message2);

		$this->assertSame(['test' => ['foobar']], $message2->getHeaders());
		$this->assertTrue($message2->hasHeader('tEst'));
		$this->assertSame(['foobar'], $message2->getHeader('test'));
		$this->assertSame('foobar', $message2->getHeaderLine('test'));

		// Add (overwrite) Header
		$message3 = $message2->withHeader('test', 'Hello');
		$this->assertFalse($message2 === $message3);

		$this->assertSame(['test' => ['Hello']], $message3->getHeaders());
		$this->assertTrue($message3->hasHeader('teSt'));
		$this->assertSame(['Hello'], $message3->getHeader('test'));
		$this->assertSame('Hello', $message3->getHeaderLine('test'));

		// Add (append) Header
		$message4 = $message3->withAddedHeader('TEST', 'World!');
		$this->assertFalse($message3 === $message4);

		$this->assertSame(['test' => ['Hello', 'World!']], $message4->getHeaders());
		$this->assertTrue($message4->hasHeader('test'));
		$this->assertSame(['Hello', 'World!'], $message4->getHeader('test'));
		$this->assertSame('Hello, World!', $message4->getHeaderLine('test'));

		// Remove Header
		$message5 = $message3->withoutHeader('tEsT');
		$this->assertFalse($message4 === $message5);

		$this->assertSame([], $message5->getHeaders());
		$this->assertFalse($message5->hasHeader('test'));
		$this->assertSame([], $message5->getHeader('test'));
		$this->assertSame('', $message5->getHeaderLine('test'));
	}

	/**
	 * @test getBodyAsString()
	 */
	public function getBodyAsString()
	{
		$message = new Message;

		$this->assertSame('', $message->getBodyAsString());
	}

	/**
	 * @test withProtocolVersion()
	 */
	public function withStringAsBody()
	{
		$message1 = new Message;

		$message2 = $message1->withStringAsBody('Body message');

		$this->assertFalse($message1 === $message2);
		$this->assertSame('Body message', $message2->getBodyAsString());
	}
}
