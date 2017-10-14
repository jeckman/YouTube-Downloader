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

namespace YoutubeDownloader;

use YoutubeDownloader\Container\SimpleContainer;

/**
 * ServiceProvider class
 */
class ServiceProvider
{
	private $config;

	/**
	 * @param Config $config
	 * @return self
	 */
	public function __construct(Config $config)
	{
		$this->config = $config;
	}

	/**
	 * Register the Services on a Container
	 *
	 * @param SimpleContainer $container
	 * @return void
	 */
	public function register(SimpleContainer $container)
	{
		$ds = \DIRECTORY_SEPARATOR;

		$container->set('YoutubeDownloader\Config', function($c) {
			return $this->config;
		});

		// Create Template\Engine
		$container->set('YoutubeDownloader\Template\Engine', function($c) {
			return \YoutubeDownloader\Template\Engine::createFromDirectory(
				__DIR__ . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR . 'templates'
			);
		});

		// Create Application\ControllerFactory
		$container->set('YoutubeDownloader\Application\ControllerFactory', function($c) {
			return new \YoutubeDownloader\Application\ControllerFactory;
		});

		// Create Toolkit
		$container->set('YoutubeDownloader\Toolkit', function($c) {
			return new \YoutubeDownloader\Toolkit;
		});

		// Create Cache
		$container->set('YoutubeDownloader\Cache\Cache', function($c) {
			return \YoutubeDownloader\Cache\FileCache::createFromDirectory(
				__DIR__ . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR . 'cache'
			);
		});

		// Create Logger
		$container->set('YoutubeDownloader\Logger\Logger', function($c) {
			$logger = new \YoutubeDownloader\Logger\HandlerAwareLogger(
				new \YoutubeDownloader\Logger\Handler\NullHandler()
			);

			if ( $c->get('YoutubeDownloader\Config')->get('debug') === true )
			{
				# code...
				$now = new \DateTime('now', new \DateTimeZone($c->get('YoutubeDownloader\Config')->get('default_timezone')));

				$filepath = sprintf(
					'%s' . \DIRECTORY_SEPARATOR . '%s',
					__DIR__ . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR . 'logs',
					$now->format('Y')
				);

				if ( ! file_exists($filepath) )
				{
					mkdir($filepath);
				}

				$stream = fopen(
					$filepath . \DIRECTORY_SEPARATOR . $now->format('Y-m-d') . '.log',
					'a+'
				);

				if ( is_resource($stream) )
				{
					$handler = new \YoutubeDownloader\Logger\Handler\StreamHandler($stream, [
						\YoutubeDownloader\Logger\LogLevel::EMERGENCY,
						\YoutubeDownloader\Logger\LogLevel::ALERT,
						\YoutubeDownloader\Logger\LogLevel::CRITICAL,
						\YoutubeDownloader\Logger\LogLevel::ERROR,
						\YoutubeDownloader\Logger\LogLevel::WARNING,
						\YoutubeDownloader\Logger\LogLevel::NOTICE,
						\YoutubeDownloader\Logger\LogLevel::INFO,
						\YoutubeDownloader\Logger\LogLevel::DEBUG,
					]);

					$logger->addHandler($handler);
				}
			}

			return $logger;
		});

		// Create HttpClient
		$container->set('YoutubeDownloader\Http\Client', function($c) {
			return new \YoutubeDownloader\Http\CurlClient;
		});

		// Create Youtube Provider
		$container->set('YoutubeDownloader\Provider\Youtube\Provider', function($c) {
			$config = $c->get('YoutubeDownloader\Config');
			$toolkit = $c->get('YoutubeDownloader\Toolkit');

			$options = [
				'decipher_signature' => $config->get('enable_youtube_decipher_signature')
			];

			if ( $config->get('multipleIPs') === true)
			{
				$options['use_ip'] = $toolkit->getRandomIp($config);
			}

			$youtube_provider = \YoutubeDownloader\Provider\Youtube\Provider::createFromOptions($options);

			if ( $youtube_provider instanceOf \YoutubeDownloader\Cache\CacheAware )
			{
				$youtube_provider->setCache($c->get('cache'));
			}

			if ( $youtube_provider instanceOf \YoutubeDownloader\Http\HttpClientAware )
			{
				$youtube_provider->setHttpClient($c->get('httpclient'));
			}

			if ( $youtube_provider instanceOf \YoutubeDownloader\Logger\LoggerAware )
			{
				$youtube_provider->setLogger($c->get('logger'));
			}

			return $youtube_provider;
		});

		// Set aliases for BC
		$container->set('config', 'YoutubeDownloader\Config');
		$container->set('template', 'YoutubeDownloader\Template\Engine');
		$container->set('controller_factory', 'YoutubeDownloader\Application\ControllerFactory');
		$container->set('toolkit', 'YoutubeDownloader\Toolkit');
		$container->set('cache', 'YoutubeDownloader\Cache\Cache');
		$container->set('logger', 'YoutubeDownloader\Logger\Logger');
		$container->set('httpclient', 'YoutubeDownloader\Http\Client');
	}
}
