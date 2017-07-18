<?php

namespace YoutubeDownloader\Container;

/**
 * A simple container implementation with a setter
 */
class SimpleContainer implements Container
{
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
		if ($this->has($id))
		{
			return $this->data[$id];
		}

		throw new NotFoundException(
			sprintf('Entry "%s" don\'t exists in the container', $id)
		);
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
		return array_key_exists($id, $this->data);
	}

	/**
	 * Set a entry with an identifier
	 *
	 * @param string $id Identifier of the entry to look for.
	 * @param mixed $value the entry
	 *
	 * @return void
	 */
	public function set($id, $value)
	{
		$id = strval($id);

		$this->data[$id] = $value;
	}
}
