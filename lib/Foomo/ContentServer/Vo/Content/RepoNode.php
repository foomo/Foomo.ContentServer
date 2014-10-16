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

namespace Foomo\ContentServer\Vo\Content;

/**
 * @link    www.foomo.org
 * @license www.gnu.org/licenses/lgpl.txt
 */
class RepoNode implements \Iterator, \Countable
{
	// --------------------------------------------------------------------------------------------
	// ~ Variables
	// --------------------------------------------------------------------------------------------

	/**
	 * @var string
	 */
	public $id;
	/**
	 * @var string
	 */
	public $name;
	/**
	 * @var string
	 */
	public $mimeType;
	/**
	 * @var string[]
	 */
	public $groups;
	/**
	 * @var string
	 */
	public $URI;
	/**
	 * @var string
	 */
	public $destinationId;
	/**
	 * @var bool
	 */
	public $hidden;
	/**
	 * @var string[]
	 */
	public $index;
	/**
	 * @var RepoNode[]
	 */
	public $nodes;
	/**
	 * @var array
	 */
	public $data;
	/**
	 * @var string
	 */
	public $linkId;
	/**
	 * @var int
	 */
	private $cursor = 0;

	// --------------------------------------------------------------------------------------------
	// ~ Public methods
	// --------------------------------------------------------------------------------------------

	/**
	 * @param string $group
	 * @return $this
	 */
	public function addGroup($group)
	{
		if (!is_array($this->groups)) {
			$this->groups = array();
		}
		if (!in_array($group, $this->groups)) {
			$this->groups[] = $group;
		}
		return $this;
	}

	/**
	 * @param mixed $data
	 * @return $this
	 */
	public function setData($data)
	{
		if (!isset($this->data)) {
			$this->data = array();
		}
		foreach ($data as $prop => $value) {
			$this->addData($prop, $value);
		}
		return $this;
	}

	/**
	 * @param string $key
	 * @param mixed  $value
	 * @return $this
	 */
	public function addData($key, $value)
	{
		if (!is_array($this->data)) {
			$this->data = array();
		}
		if (is_object($this->data)) {
			$this->data->{$key} = $value;
		} else {
			$this->data[$key] = $value;
		}
		return $this;
	}


	/**
	 * @param RepoNode $node
	 * @return $this
	 */
	public function addNode(RepoNode $node)
	{
		if (!is_array($this->nodes)) {
			$this->nodes = array();
			$this->index = array();
		}
		if (!in_array($node->id, $this->index)) {
			$this->nodes[$node->id] = $node;
			$this->index[] = $node->id;
		}
		return $this;
	}

	/**
	 * @param RepoNode $node
	 * @return $this
	 */
	public function removeNode(RepoNode $node)
	{
		array_splice($this->nodes, array_search($node, $this->nodes), 1);
		array_splice($this->index, array_search($node->id, $this->index), 1);
		$this->rewind();
		return $this;
	}

	//------------------------------------------------------------------------------------------------------------------
	// ~ Iterator and Countable implementation
	//------------------------------------------------------------------------------------------------------------------

	/**
	 *
	 */
	public function rewind()
	{
		$this->cursor = 0;
	}

	/**
	 * @return RepoNode
	 */
	public function current()
	{
		return $this->nodes[$this->key()];
	}

	/**
	 * @return string
	 */
	public function key()
	{
		return $this->index[$this->cursor];
	}

	/**
	 *
	 */
	public function next()
	{
		$this->cursor++;
	}

	/**
	 * @return bool
	 */
	public function valid()
	{
		return count($this->index) > $this->cursor;
	}

	/**
	 * @return int
	 */
	public function count()
	{
		return count($this->index);
	}
}
