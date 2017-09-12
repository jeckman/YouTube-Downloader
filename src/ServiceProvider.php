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

		$container->set('YoutubeDownloader\Config', $this->config);

		// Create Template\Engine
		$template = \YoutubeDownloader\Template\Engine::createFromDirectory(
			__DIR__ . $ds . '..' . $ds . 'templates'
		);

		$container->set('YoutubeDownloader\Template\Engine', $template);

		// Create Application\ControllerFactory
		$factory = new \YoutubeDownloader\Application\ControllerFactory;

		$container->set('YoutubeDownloader\Application\ControllerFactory', $factory);

		// Create Toolkit
		$container->set('YoutubeDownloader\Toolkit', new \YoutubeDownloader\Toolkit);

		// Create Cache
		$cache = \YoutubeDownloader\Cache\FileCache::createFromDirectory(
			__DIR__ . $ds . '..' . $ds . 'cache'
		);

		$container->set('YoutubeDownloader\Cache\Cache', $cache);

		// Create Logger
		$logger = new \YoutubeDownloader\Logger\HandlerAwareLogger(
			new \YoutubeDownloader\Logger\Handler\NullHandler()
		);

		if ( $this->config->get('debug') === true )
		{
			# code...
			$now = new \DateTime('now', new \DateTimeZone($this->config->get('default_timezone')));

			$filepath = sprintf(
				'%s' . $ds . '%s',
				__DIR__ . $ds . '..' . $ds . 'logs',
				$now->format('Y')
			);

			if ( ! file_exists($filepath) )
			{
				mkdir($filepath);
			}

			$stream = fopen(
				$filepath . $ds . '..' . $ds . $now->format('Y-m-d') . '.log',
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

		$container->set('YoutubeDownloader\Logger\Logger', $logger);

		// Set aliases for BC
		$container->setAlias('config', 'YoutubeDownloader\Config');
		$container->setAlias('template', 'YoutubeDownloader\Template\Engine');
		$container->setAlias('controller_factory', 'YoutubeDownloader\Application\ControllerFactory');
		$container->setAlias('toolkit', 'YoutubeDownloader\Toolkit');
		$container->setAlias('cache', 'YoutubeDownloader\Cache\Cache');
		$container->setAlias('logger', 'YoutubeDownloader\Logger\Logger');
	}
}
