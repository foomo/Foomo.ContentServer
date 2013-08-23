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
class RepoNode
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
	 * @var hash
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
	 * @var array
	 */
	public $URIs;
	/**
	 * @var bool
	 */
	public $hidden;
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
		}
		$this->nodes[$node->id] = $node;
	}
	public function addRegion($region) {
		if(is_null($this->regions)) {
			$this->regions = array();
		}
		$this->regions[] = $region;
	}
	public function addURI($region, $language, $URI)
	{
		$this->addToRegionLanguageProp('URIs', $region, $language, $URI);
	}
	public function addLinkId($region, $language, $linkId)
	{
		$this->addToRegionLanguageProp('linkIds', $region, $language, $linkId);
	}
	private function addToRegionLanguageProp($prop, $region, $language, $value)
	{
		if(!is_array($this->{$prop})) {
			$this->{$prop} = array();
		}
		if(!is_array($this->{$prop}[$region])) {
			$this->{$prop}[$region] = array();
		}
		$this->{$prop}[$region][$language] = $value;

	}
	public function addName($language, $name)
	{
		$this->names[$language] = $name;
	}
}