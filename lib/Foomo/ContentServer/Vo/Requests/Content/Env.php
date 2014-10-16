<?php

/*
 * This file is part of the foomo Opensource Framework.
 *
 * The foomo Opensource Framework is free software: you can redistribute it
 * and/or modify it under the terms of the GNU Lesser General Public License as
 * published  by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * The foomo Opensource Framework is distributed in the hope that it will
 * be useful, but WITHOUT ANY WARRANTY; without even the implied warranty
 * of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License along with
 * the foomo Opensource Framework. If not, see <http://www.gnu.org/licenses/>.
 */

namespace Foomo\ContentServer\Vo\Requests\Content;

/**
 * @link    www.foomo.org
 * @license www.gnu.org/licenses/lgpl.txt
 */
class Env
{
	// --------------------------------------------------------------------------------------------
	// ~ Variables
	// --------------------------------------------------------------------------------------------

	/**
	 * if you can not resolve a content, offer you dimension lookup chain
	 *
	 * @var string[]
	 */
	public $dimensions;
	/**
	 * @var string[]
	 */
	public $groups;
	/**
	 * @var array
	 */
	public $data;

	// --------------------------------------------------------------------------------------------
	// ~ Public static methods
	// --------------------------------------------------------------------------------------------

	/**
	 * @param string[] $dimensions
	 * @param string[] $groups
	 * @param array    $data
	 * @return Env
	 */
	public static function create(array $dimensions, array $groups = array(), array $data = array())
	{
		$env = new self();
		$env->groups = $groups;
		$env->dimensions = $dimensions;
		$env->data = $data;
		return $env;
	}
}
