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

namespace YoutubeDownloader\VideoInfo;

/**
 * Interface for a Provider instance
 */
interface Provider
{
	/**
	 * Check if this provider can create a VideoInfo from a given input
	 *
	 * This check should be done as fast as possible like run some simple
	 * regex on the input to determine a specific domain or ID pattern.
	 *
	 * There is no guarantee that after `provides()` returns true the
	 * `provide()` will return a `VideoInfo` instance. This method should only
	 * be used as a first indicator if the provider can handle the input for
	 * speed reason. So you should keep in mind that `provide()` can also
	 * throw an exception even if `provides()` returns true
	 *
	 * @param string $input The input like an url or ID
	 * @return boolean true if this provider could handle the input, else false
	 */
	public function provides($input);

	/**
	 * Provides a YoutubeDownloader\VideoInfo\VideoInfo instance for the input
	 *
	 * There is no guarantee that `provides()` will be called before this.
	 * This method should also be idempotent, so a repeated call with the same
	 * input should have the same result. This can be returning a VideoInfo or
	 * throwing an Exception.
	 *
	 * An exception can be thrown if the input can't be handled or if there are
	 * other reasons that prevents the creation of a VideoInfo like connection
	 * problems or invalid responses.
	 *
	 * @param string $input The input like an url or ID
	 * @throws YoutubeDownloader\VideoInfo\Exception if the VideoInfo could not be created
	 * @throws YoutubeDownloader\VideoInfo\InvalidInputException if the input can't be handled
	 * @return YoutubeDownloader\VideoInfo\VideoInfo
	 */
	public function provide($input);
}
