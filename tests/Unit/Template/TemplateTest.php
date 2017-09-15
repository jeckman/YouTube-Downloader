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
use YoutubeDownloader\Template\Template;
use YoutubeDownloader\Tests\Fixture\TestCase;

class TemplateTest extends TestCase
{
	public function setUp()
	{
		vfsStream::setup('templates');
	}

	/**
	 * @test __construct()
	 */
	public function construct()
	{
		$engine = $this->createMock('\\YoutubeDownloader\\Template\\Engine');

		$this->assertInstanceOf('YoutubeDownloader\Template\Template', new Template($engine, 'template.php'));
	}

	/**
	 * @test get()
	 * @dataProvider GetDataProvider
	 */
	public function get($data, $key, $default, $expected)
	{
		$engine = $this->createMock('\\YoutubeDownloader\\Template\\Engine');

		$template = new Template($engine, 'template.php');
		$template->setData($data);

		$this->assertSame($expected, $template->get($key, $default));
	}

	/**
	 * GetDataProvider
	 */
	public function GetDataProvider()
	{
		return [
			[['foo' => 'bar'], 'foo', null, 'bar'],
			[['fuz' => 'baz'], 'foo', 'bar', 'bar'],
			[[], 'foo', null, null],
			[[], 'foo', 'bar', 'bar'],
		];
	}

	/**
	* @test render()
	* @dataProvider RenderDataProvider
	*/
	public function render($filename, $file_content, $data, $expected)
	{
		$engine = $this->createMock('\\YoutubeDownloader\\Template\\Engine');
		$engine->method('getTemplateDirectory')
			->willReturn(vfsStream::url('templates'));

		vfsStream::create([$filename => $file_content]);

		$template = new Template($engine, $filename);

		$this->assertSame($expected, $template->render($data));
	}

	/**
	* RenderDataProvider
	*/
	public function RenderDataProvider()
	{
		return [
			['template1.php', '<html></html>', [], '<html></html>'],
			[
				'template2.php',
				'<html><?php echo $this->get(\'foo\'); ?></html>',
				['foo' => 'bar'],
				'<html>bar</html>'
			],
			[
				'template3.php',
				'<html><?php echo $this->get(\'foo\', \'default\'); ?></html>',
				['fuz' => 'baz'],
				'<html>default</html>'
			],
		];
	}

	/**
	* @test inc()
	*/
	public function testInc()
	{
		$engine = $this->createMock('\\YoutubeDownloader\\Template\\Engine');
		$engine->method('getTemplateDirectory')
			->willReturn(vfsStream::url('templates'));
		$engine->method('render')
			->with('header.php', ['title' => 'Hello world!'])
			->willReturn('<html><head><title>Hello world!</title></head>');

		vfsStream::create([
			'index.php' => '<?php echo $this->inc(\'header.php\', $this->get(\'header_data\')); ?><body></body></html>',
			'header.php' => '<html><head><title><?php echo $this->get(\'title\'); ?></title></head>',
		]);

		$template = new Template($engine, 'index.php');

		$expected = '<html><head><title>Hello world!</title></head><body></body></html>';

		$this->assertSame($expected, $template->render([
			'header_data' => [
				'title' => 'Hello world!',
			],
		]));
	}

	/**
	* @test render()
	*/
	public function renderCannotFindFile()
	{
		$engine = $this->createMock('\\YoutubeDownloader\\Template\\Engine');
		$engine->method('getTemplateDirectory')
			->willReturn(vfsStream::url('templates'));

		// template.php don't exist
		//vfsStream::create(['template.php' => '']);

		$this->expectException('LogicException');
		$this->expectExceptionMessage('The template "template.php" could not be found at "vfs://templates".');

		$template = new Template($engine, 'template.php');

		$template->render();
	}

	/**
	* @test render()
	*/
	public function renderRethrowExceptionFromFile()
	{
		$engine = $this->createMock('\\YoutubeDownloader\\Template\\Engine');
		$engine->method('getTemplateDirectory')
			->willReturn(vfsStream::url('templates'));

		vfsStream::create([
			'template.php' => '<?php throw new \Exception(\'exception message\'); ?>',
		]);

		$this->expectException('\\Exception');
		$this->expectExceptionMessage('exception message');

		$template = new Template($engine, 'template.php');

		$template->render();
	}
}
