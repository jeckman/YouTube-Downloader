<?php

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
