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

/**
 * @link www.foomo.org
 * @license www.gnu.org/licenses/lgpl.txt
 */
class Content
{
	/**
	 * @var string
	 */
	public $URI;
	/**
	 * @var Content\Node[]
	 */
	public $nodes = array();
	/**
	 * @var Content\Env
	 */
	public $env;

	/**
	 * @param $URI
	 * @param $groups
	 * @param array $data
	 *
	 * @return Content
	 */
	public static function create($URI, $groups, $data = array())
	{
		return new self($URI, $groups, $data);
	}
	private function __construct($URI, $groups, $data = array())
	{
		$this->URI = $URI;
		$env = new Content\Env();
		$env->data = $data;
		$env->groups = $groups;
		$this->env = $env;
	}

	/**
	 * @param string $name
	 * @param string $id
	 * @param string[] array $mimeTypes
	 * @param bool $expand
	 * @return Content
	 */
	public function addNode($name, $id, array $mimeTypes, $expand)
	{
		$node = new Content\Node;
		$node->id = $id;
		$node->mimeTypes = $mimeTypes;
		$node->expand = $expand;
		$this->nodes[$name] = $node;
		return $this;
	}
}