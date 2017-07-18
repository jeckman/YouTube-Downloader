<?php

namespace YoutubeDownloader\Tests\Fixture\Container;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use YoutubeDownloader\Container\Container;
use YoutubeDownloader\Container\ContainerException;
use YoutubeDownloader\Container\NotFoundException;
use YoutubeDownloader\Container\SimpleContainer;

/**
 * A simple PSR-11 container as a compatibility proof for SimpleContainer
 */
class Psr11ContainerAdapter implements ContainerInterface, Container
{
	/**
	 * @var YoutubeDownloader\Container\SimpleContainer
	 */
	private $container;

	/**
	 * @param YoutubeDownloader\Container\SimpleContainer $container
	 *
	 * @return void
	 */
	public function __construct(SimpleContainer $container)
	{
		$this->container = $container;
	}

	/**
	 * Finds an entry of the container by its identifier and returns it.
	 *
	 * @param string $id Identifier of the entry to look for.
	 *
	 * @throws Psr\Container\NotFoundExceptionInterface  No entry was found for **this** identifier.
	 * @throws Psr\Container\ContainerExceptionInterface Error while retrieving the entry.
	 *
	 * @return mixed Entry.
	 */
	public function get($id)
	{
		try
		{
			return $this->container->get($id);
		}
		catch (NotFoundException $e)
		{
			throw new Psr11NotFoundException($e->getMessage());
		}
		catch (ContainerException $e)
		{
			throw new Psr11ContainerException($e->getMessage());
		}
	}

	/**
	 * Returns true if the container can return an entry for the given identifier.
	 * Returns false otherwise.
	 *
	 * `has($id)` returning true does not mean that `get($id)` will not throw an exception.
	 * It does however mean that `get($id)` will not throw a `NotFoundExceptionInterface`.
	 *
	 * @param string $id Identifier of the entry to look for.
	 *
	 * @return bool
	 */
	public function has($id)
	{
		return $this->container->has($id);
	}
}
