<?php

namespace YoutubeDownloader\Tests\Unit;

use YoutubeDownloader\Format;
use YoutubeDownloader\Tests\Fixture\TestCase;

class FormatTest extends TestCase
{
	public function setUp()
	{
		$this->tz_backup = date_default_timezone_get();
		date_default_timezone_set('UTC');
	}

	public function tearDown()
	{
		date_default_timezone_set($this->tz_backup);
	}

	/**
	 * @test createFromArray()
	 */
	public function createFromArray()
	{
		$format_data = [
			'itag' => '22',
			'quality' => 'hd720',
			'type' => 'video/mp4',
			'url' => 'https://r4---sn-xjpm-q0ns.googlevideo.com/videoplayback?mime=video%2Fmp4&dur=3567.629&id=o-ALLJQEDdJCHuwXcL9toC3Iim4AvgjxD3lzAyP_l8I3i6&pl=23&itag=22&ratebypass=yes&mt=1495797277&ms=au&requiressl=yes&mn=sn-xjpm-q0ns&mm=31&source=youtube&sparams=dur%2Cei%2Cid%2Cinitcwndbps%2Cip%2Cipbits%2Citag%2Clmt%2Cmime%2Cmm%2Cmn%2Cms%2Cmv%2Cpl%2Cratebypass%2Crequiressl%2Csource%2Cexpire&key=yt6&lmt=1471747682170867&signature=B6A1CD71B2245C40BCC26B71BF9DC15545591BE1.DB1653D145BC156D1A3C13F24B63B08BD10F86AB&ei=gQ4oWf3TNsGVceLmsagP&expire=1495818977&ip=211.12.135.54&mv=m&initcwndbps=1286250&ipbits=0',
			'expires' => '21:46:17 IRDT',
			'ipbits' => '0',
			'ip' => '211.12.135.54',
		];

		$video_info = $this->createMock('\\YoutubeDownloader\\VideoInfo');
		$video_info->method('getVideoId')->willReturn('ScNNfyq3d_w');

		$config = [];

		$format = Format::createFromArray($video_info, $format_data, $config);

		$this->assertInstanceOf('\\YoutubeDownloader\\Format', $format);

		return $format;
	}

	/**
	 * @test getVideoId()
	 */
	public function getVideoId()
	{
		// We cannot use @depends on createFromArray because of a bug in
		// PHPUnit 4 that remove the mocked methods in the mocked VideoInfo.
		// Instead we call $this->createFromArray() directly.
		$format = $this->createFromArray();

		$this->assertSame('ScNNfyq3d_w', $format->getVideoId());
	}

	/**
	 * @test getUrl()
	 * @depends createFromArray
	 */
	public function getUrl(Format $format)
	{
		$this->assertSame(
			'https://r4---sn-xjpm-q0ns.googlevideo.com/videoplayback?mime=video%2Fmp4&dur=3567.629&id=o-ALLJQEDdJCHuwXcL9toC3Iim4AvgjxD3lzAyP_l8I3i6&pl=23&itag=22&ratebypass=yes&mt=1495797277&ms=au&requiressl=yes&mn=sn-xjpm-q0ns&mm=31&source=youtube&sparams=dur%2Cei%2Cid%2Cinitcwndbps%2Cip%2Cipbits%2Citag%2Clmt%2Cmime%2Cmm%2Cmn%2Cms%2Cmv%2Cpl%2Cratebypass%2Crequiressl%2Csource%2Cexpire&key=yt6&lmt=1471747682170867&signature=B6A1CD71B2245C40BCC26B71BF9DC15545591BE1.DB1653D145BC156D1A3C13F24B63B08BD10F86AB&ei=gQ4oWf3TNsGVceLmsagP&expire=1495818977&ip=211.12.135.54&mv=m&initcwndbps=1286250&ipbits=0',
			$format->getUrl()
		);
	}

	/**
	 * @test getItag()
	 * @depends createFromArray
	 */
	public function getItag(Format $format)
	{
		$this->assertSame('22', $format->getItag());
	}

	/**
	 * @test getQuality()
	 * @depends createFromArray
	 */
	public function getQuality(Format $format)
	{
		$this->assertSame('hd720', $format->getQuality());
	}

	/**
	 * @test getType()
	 * @depends createFromArray
	 */
	public function getType(Format $format)
	{
		$this->assertSame('video/mp4', $format->getType());
	}

	/**
	 * @test getExpires()
	 * @depends createFromArray
	 */
	public function getExpires(Format $format)
	{
		$this->assertSame('17:16:17 UTC', $format->getExpires());
	}

	/**
	 * @test getIpbits()
	 * @depends createFromArray
	 */
	public function getIpbits(Format $format)
	{
		$this->assertSame('0', $format->getIpbits());
	}

	/**
	 * @test getIp()
	 * @depends createFromArray
	 */
	public function getIp(Format $format)
	{
		$this->assertSame('211.12.135.54', $format->getIp());
	}
}
