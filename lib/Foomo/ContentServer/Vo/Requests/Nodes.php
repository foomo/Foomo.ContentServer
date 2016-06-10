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

namespace Foomo\ContentServer\Vo\Requests;

use Foomo\ContentServer\Vo\Requests\Content\Env;

/**
 * @link    www.foomo.org
 * @license www.gnu.org/licenses/lgpl.txt
 */
class Nodes
{
	// --------------------------------------------------------------------------------------------
	// ~ Variables
	// --------------------------------------------------------------------------------------------

	/**
	 * @var Content\Node[]
	 */
	public $nodes = array();
	/**
	 * @var Env
	 */
	public $env;

	// --------------------------------------------------------------------------------------------
	// ~ Constructor
	// --------------------------------------------------------------------------------------------

	/**
	 * @param Env $env
	 */
	private function __construct(Env $env)
	{
		$this->env = $env;
	}

	// --------------------------------------------------------------------------------------------
	// ~ Public methods
	// --------------------------------------------------------------------------------------------

	/**
	 * @param string   $name
	 * @param string   $id
	 * @param string[] array $mimeTypes
	 * @param bool     $expand
	 * @param string[] array $dataFields
	 * @param string   $dimension
	 *
	 * @return Content
	 */
	public function addNode($name, $id, array $mimeTypes, $expand, array $dataFields, $dimension = "")
	{
		$this->nodes[$name] = new Content\Node($id, $mimeTypes, $expand, $dataFields, $dimension);
		return $this;
	}

	// --------------------------------------------------------------------------------------------
	// ~ Public static methods
	// --------------------------------------------------------------------------------------------

	/**
	 * @param Env $env
	 *
	 * @return Content
	 */
	public static function create(Env $env)
	{
		return new self($env);
	}
}
