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
 * @link www.foomo.org
 * @license www.gnu.org/licenses/lgpl.txt
 */
class SiteContent
{
	const STATUS_OK = 200;
	const STATUS_NOT_FOUND = 404;
	const STATUS_FORBIDDEN = 403;
	/**
	 * @var int
	 */
	public $status;
	/**
	 * resolved part of URI
	 *
	 * @var string
	 */
	public $URI;
	/**
	 * @var string
	 */
	public $region;
	/**
	 * @var string
	 */
	public $language;
	/**
	 * @var string
	 */
	public $handler;
	/**
	 * @var Item
	 */
	public $item;
	/**
	 * @var mixed
	 */
	public $data;
	/**
	 * @var Item[]
	 */
	public $path = array();
	/**
	 * @var Node[]
	 */
	public $nodes = array();
	public function addToNodes($key, $value) {
		$this->nodes[$key] = VoMapper::map($value, new Node);
	}
}