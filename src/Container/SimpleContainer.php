<?php

namespace YoutubeDownloader\Container;

use Closure;

/**
 * A simple container implementation with a setter
 */
class SimpleContainer implements Container
{
	/**
	 * @var array
	 */
	private $aliases = [];

	/**
	 * @var array
	 */
	private $data = [];

	/**
	 * Finds an entry of the container by its identifier and returns it.
	 *
	 * @param string $id Identifier of the entry to look for.
	 *
	 * @throws NotFoundException  No entry was found for **this** identifier.
	 * @throws ContainerException Error while retrieving the entry.
	 *
	 * @return mixed Entry.
	 */
	public function get($id)
	{
		if ( ! $this->has($id) )
		{
			throw new NotFoundException(
				sprintf('Entry "%s" don\'t exists in the container', $id)
			);
		}

		if ( ! array_key_exists($id, $this->data) )
		{
			$id = $this->aliases[$id];
		}

		$closure = $this->data[$id];

		return $closure->__invoke($this);
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
		if ( array_key_exists($id, $this->data) )
		{
			return true;
		}

		if ( array_key_exists($id, $this->aliases) )
		{
			return true;
		}

		return false;
	}

	/**
	 * Set an entry with an identifier
	 *
	 * @deprecated SimpleContainer::set() needs an optional Closure as argument #2 ($value) since version 0.5, to be required in 0.6. Provide a Closure as argument #2 ($value) instead
	 *
	 * The second argument for $value must be a Closure that expects the
	 * Container as first argument. That allows to get entries from the Container
	 * inside the Closure to build complex dependencies.
	 *
	 * Example:
	 * $value = function(Container $c) {
	 *   return new LoggerCacheBrige(
	 *     $c->get('logger'),
	 *     $c->get('cache')
	 *   );
	 * };
	 *
	 * @param string $id Identifier of the entry to look for.
	 * @param Closure $value A closure that returns the entry on invoke
	 *
	 * @return void
	 */
	public function set($id, $value)
	{
		$id = strval($id);

		// BC: Create Closure if not provided
		if ( ! $value instanceOf Closure )
		{
			@trigger_error(__METHOD__ . ' needs an optional Closure as argument #2 ($value) since version 0.5, to be required in 0.6. Provide a Closure as argument #2 ($value) instead', E_USER_DEPRECATED);

			$val = function(Container $c) use ($value) {
				return $value;
			};
		}
		else
		{
			$val = $value;
		}

		$this->data[$id] = $val;
	}

	/**
	 * Set an alias to an existing entry
	 *
	 * @param string $alias The alias
	 * @param string $id the existing entry
	 *
	 * @return void
	 */
	public function setAlias($alias, $id)
	{
		$id = strval($id);
		$alias = strval($alias);

		if ( ! $this->has($id) )
		{
			throw new ContainerException(sprintf(
				'The alias "%s" must point to an existing entry, id "%s" was given.',
				$alias,
				$id
			));
		}

		$this->aliases[$alias] = $id;
	}
}
