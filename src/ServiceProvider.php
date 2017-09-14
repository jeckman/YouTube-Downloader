<?php

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
					$filepath . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR . $now->format('Y-m-d') . '.log',
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
