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

namespace YoutubeDownloader\Tests\Unit\Template;

use org\bovigo\vfs\vfsStream;
use YoutubeDownloader\Template\Engine;
use YoutubeDownloader\Tests\Fixture\TestCase;

class EngineTest extends TestCase
{
	public function setUp()
	{
		vfsStream::setup('templates');
	}

	/**
	 * @test createFromString()
	 */
	public function createFromDirectory()
	{
		$this->assertInstanceOf(
			'\\YoutubeDownloader\\Template\\Engine',
			Engine::createFromDirectory('')
		);
	}

	/**
	 * @test getTemplateDirectory()
	 * @dataProvider TemplateDirectoryProvider
	 */
	public function getTemplateDirectory($directory, $expected)
	{
		$engine = Engine::createFromDirectory($directory);

		$this->assertSame($expected, $engine->getTemplateDirectory());
	}

	/**
	 * TemplateDirectoryProvider
	 */
	public function TemplateDirectoryProvider()
	{
		return [
			['foo', 'foo'],
			['foo' . DIRECTORY_SEPARATOR, 'foo'],
			['foo/bar', 'foo/bar'],
			['foo\bar', 'foo\bar'],
		];
	}

	/**
	* @test render()
	*/
	public function render()
	{
		$engine = Engine::createFromDirectory(vfsStream::url('templates'));

		vfsStream::create(['template.php' => '<html></html>']);

		$this->assertSame('<html></html>', $engine->render('template.php'));
	}

	/**
	* @test render()
	*/
	public function renderWithData()
	{
		$engine = Engine::createFromDirectory(vfsStream::url('templates'));

		vfsStream::create([
			'template.php' => '<html><?php echo $this->get(\'foo\'); ?></html>'
		]);

		$this->assertSame(
			'<html>bar</html>',
			$engine->render('template.php', ['foo' => 'bar'])
		);
	}
}
