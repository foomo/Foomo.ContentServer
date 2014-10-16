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

use Foomo\SimpleData\VoMapper;

/**
 * @link    www.foomo.org
 * @license www.gnu.org/licenses/lgpl.txt
 */
class Node implements \Iterator, \Countable
{
	// --------------------------------------------------------------------------------------------
	// ~ Variables
	// --------------------------------------------------------------------------------------------

	/**
	 * @var Item
	 */
	public $item;
	/**
	 * @var Node[]
	 */
	public $nodes = array();
	/**
	 * @internal
	 * @var string[]
	 */
	public $index = array();
	/**
	 * @var int
	 */
	private $cursor = 0;

	// --------------------------------------------------------------------------------------------
	// ~ Public methods
	// --------------------------------------------------------------------------------------------

	/**
	 * @param string $key
	 * @param mixed $value
	 */
	public function addToNodes($key, $value)
	{
		$this->nodes[$key] = VoMapper::map($value, new Node);
	}

	/**
	 * @return Node
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
	 *
	 */
	public function rewind()
	{
		$this->cursor = 0;
	}

	/**
	 * @return int
	 */
	public function count()
	{
		return count($this->index);
	}
}
