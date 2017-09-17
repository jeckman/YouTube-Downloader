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
	 * The second argument for $value must be a Closure that expects the
	 * Container as first argument. That allows to get entries from the Container
	 * inside the Closure to build complex dependencies.
	 *
	 * Example:
	 * $value = function(Container $c) {
	 *   return new LoggerCacheBridge(
	 *     $c->get('logger'),
	 *     $c->get('cache')
	 *   );
	 * };
	 *
	 * @param string $id Identifier of the entry to look for.
	 * @param Closure|string $value A closure that returns the entry on invoke or an identifier that a reference to an existing entry
	 *
	 * @return void
	 */
	public function set($id, $value)
	{
		$id = strval($id);

		if ( $value instanceOf Closure )
		{
			$this->data[$id] = $value;

			return;
		}

		// a string can be an alias for an entry
		if ( is_string($value) and array_key_exists($value, $this->data) )
		{
			return $this->setAlias($id, $value);
		}

		throw new ContainerException(
			'Second argument ($value) must be a Closure or a string as alias '.
			'to an existing entry.'
		);
	}

	/**
	 * Set an alias to an existing entry
	 *
	 * @param string $alias The alias
	 * @param string $id the existing entry
	 *
	 * @return void
	 */
	private function setAlias($alias, $id)
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
