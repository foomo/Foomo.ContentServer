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
class Content
{
	// --------------------------------------------------------------------------------------------
	// ~ Variables
	// --------------------------------------------------------------------------------------------

	/**
	 * @var Env
	 */
	public $env;
	/**
	 * @var string
	 */
	public $URI;
	/**
	 * @var Content\Node[]
	 */
	public $nodes;

	// --------------------------------------------------------------------------------------------
	// ~ Constructor
	// --------------------------------------------------------------------------------------------

	/**
	 * @param string $URI
	 * @param Env    $env
	 */
	private function __construct($URI, Env $env)
	{
		$this->URI = $URI;
		$this->env = $env;
	}

	// --------------------------------------------------------------------------------------------
	// ~ Public methods
	// --------------------------------------------------------------------------------------------

	/**
	 * @param string   $name
	 * @param string   $id
	 * @param string[] $mimeTypes
	 * @param bool     $expand
	 * @param string[] $dataFields
	 * @param string $dimension
	 * @return Content
	 */
	public function addNode($name, $id, array $mimeTypes, $expand, array $dataFields = [], $dimension = "")
	{
		if (!is_array($this->nodes)) {
			$this->nodes = array();
		}
		$this->nodes[$name] = new Content\Node($id, $mimeTypes, $expand, $dataFields, $dimension);
		return $this;
	}

	// --------------------------------------------------------------------------------------------
	// ~ Public static methods
	// --------------------------------------------------------------------------------------------

	/**
	 * @param     $URI
	 * @param Env $env
	 *
	 * @return Content
	 */
	public static function create($URI, Env $env)
	{
		return new self($URI, $env);
	}
}
