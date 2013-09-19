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
 * @link www.foomo.org
 * @license www.gnu.org/licenses/lgpl.txt
 */
class RepoNode implements \Iterator, \Countable
{
	/**
	 * @var string
	 */
	public $id;
	/**
	 * @var string[]
	 */
	public $regions;
	/**
	 * @var string[] hashmap
	 */
	public $names = array();
	/**
	 * @var string
	 */
	public $handler;
	/**
	 * @var string[]
	 */
	public $groups;
	/**
	 * @var string
	 */
	public $mimeType;
	/**
	 * @var string[] hashmap
	 */
	public $URIs;
	/**
	 * @var string[]
	 */
	public $destinationIds;
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
	 * @var hash
	 */
	public $data;
	/**
	 * @var string[]
	 */
	public $linkIds;

	private $cursor = 0;

	public function setData($data)
	{
		if(!isset($this->data)) {
			$this->data = array();
		}
		foreach($data as $prop => $value) {
			if(is_object($this->data)) {
				$this->data->{$prop} = $value;
			} else {
				$this->data[$prop] = $value;
			}
		}
	}

	public function addGroup($group)
	{
		if(!is_array($this->groups)) {
			$this->groups = array();
		}
		$this->groups[] = $group;
	}
	public function addNode(RepoNode $node)
	{

		if(!is_array($this->nodes)) {
			$this->nodes = array();
			$this->index = array();
		}
		if(!in_array($node->id, $this->index)) {
			$this->nodes[$node->id] = $node;
			$this->index[] = $node->id;
		}
	}
	public function addRegion($region) {
		if(is_null($this->regions)) {
			$this->regions = array();
		}
		if(!in_array($region, $this->regions)) {
			$this->regions[] = $region;
		}
	}

	public function setURIs($objs)
	{
		foreach((array)$objs as $region => $languages) {
			foreach((array)$languages as $language => $value) {
				$this->addURI($region, $language, $value);
			}
		}
	}

	public function setDestinationIds($objs)
	{
		if(!is_null($objs)) {
			foreach((array)$objs as $region => $languages) {
				foreach((array)$languages as $language => $value) {
					$this->addDestinationId($region, $language, $value);
				}
			}
		}
	}

	public function setNames($objs)
	{
		foreach((array)$objs as $language => $value) {
			$this->addName($language, $value);
		}
	}

	public function addURI($region, $language, $URI)
	{
		$this->addToRegionLanguageProp('URIs', $region, $language, $URI);
	}
	public function addDestinationId($region, $language, $destinationId)
	{
		$this->addToRegionLanguageProp('destinationIds', $region, $language, $destinationId);
	}
	public function addLinkId($region, $language, $linkId)
	{
		$this->addToRegionLanguageProp('linkIds', $region, $language, $linkId);
	}
	private function addToRegionLanguageProp($prop, $region, $language, $value)
	{
		if(!isset($this->{$prop})) {
			$this->{$prop} = array();
		}
		if(!isset($this->{$prop}[$region])) {
			$this->{$prop}[$region] = array();
		}
		$this->{$prop}[$region][$language] = $value;

	}
	public function addName($language, $name)
	{
		$this->names[$language] = $name;
	}

	//------------------------------------------------------------------------------------------------------------------
	// ~ Iterator and Countable implementation
	//------------------------------------------------------------------------------------------------------------------

    public function current()
	{
		return $this->nodes[$this->key()];
	}

    public function next()
	{
		$this->cursor ++;
	}

    public function key()
	{
		return $this->index[$this->cursor];
	}

    public function valid()
	{
		return count($this->index) > $this->cursor;
	}

    public function rewind()
	{
		$this->cursor = 0;
	}
	public function count()
	{
		return count($this->index);
	}
}