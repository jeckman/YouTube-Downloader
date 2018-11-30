<?php

/*
 * PHP script for downloading videos from youtube
 * Copyright (C) 2012-2018  John Eckman
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

@trigger_error('The ' . __NAMESPACE__ . '\Container class is deprecated since version 0.8 and will be removed in 0.9. Use Psr\Container\ContainerInterface instead.', E_USER_DEPRECATED);

use Psr\Container\ContainerInterface;

/**
 * Describes the interface of a container that exposes methods to read its entries.
 *
 * @deprecated since version 0.8, to be removed in 0.9. Use `Psr\Container\ContainerInterface` instead
 *
 * This interface is compatible with PSR-11 Psr\Container\ContainerInterface
 */
interface Container extends ContainerInterface
{
}
