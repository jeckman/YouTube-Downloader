<?php

namespace YoutubeDownloader\Tests\Unit;

use YoutubeDownloader\StreamMap;

class StreamMapTest extends \YoutubeDownloader\Tests\Fixture\TestCase
{
	/**
	 * @test createFromVideoInfo()
	 */
	public function createFromVideoInfo()
	{
		$stream_data = http_build_query([
			'itag' => '22',
			'quality' => 'hd720',
			'type' => 'video/mp4',
			'url' => 'https://r4---sn-xjpm-q0ns.googlevideo.com/videoplayback?mime=video%2Fmp4&dur=3567.629&id=o-ALLJQEDdJCHuwXcL9toC3Iim4AvgjxD3lzAyP_l8I3i6&pl=23&itag=22&ratebypass=yes&mt=1495797277&ms=au&requiressl=yes&mn=sn-xjpm-q0ns&mm=31&source=youtube&sparams=dur%2Cei%2Cid%2Cinitcwndbps%2Cip%2Cipbits%2Citag%2Clmt%2Cmime%2Cmm%2Cmn%2Cms%2Cmv%2Cpl%2Cratebypass%2Crequiressl%2Csource%2Cexpire&key=yt6&lmt=1471747682170867&signature=B6A1CD71B2245C40BCC26B71BF9DC15545591BE1.DB1653D145BC156D1A3C13F24B63B08BD10F86AB&ei=gQ4oWf3TNsGVceLmsagP&expire=1495818977&ip=211.12.135.54&mv=m&initcwndbps=1286250&ipbits=0',
			'expires' => '21:46:17 IRDT',
			'ipbits' => '0',
			'ip' => '211.12.135.54',

		]);

		$streams_data = implode(',', [$stream_data, $stream_data]);

		$video_info = $this->createMock('YoutubeDownloader\VideoInfo');
		$video_info->method('getStreamMapString')->willReturn($streams_data);
		$video_info->method('getAdaptiveFormatsString')->willReturn($streams_data);

		$stream_map = StreamMap::createFromVideoInfo($video_info);

		$this->assertInstanceOf('YoutubeDownloader\StreamMap', $stream_map);

		return $stream_map;
	}

	/**
	 * @test getStreams()
	 * @depends createFromVideoInfo
	 */
	public function getStreams(StreamMap $stream_map)
	{
		$this->assertCount(2, $stream_map->getStreams());
	}

	/**
	 * @test getFormats()
	 * @depends createFromVideoInfo
	 */
	public function getFormats(StreamMap $stream_map)
	{
		$this->assertCount(2, $stream_map->getFormats());
	}
}
