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

namespace Foomo\ContentServer;
use Foomo\ContentServer\Vo\Content\Node;
use Foomo\ContentServer\Vo\Content\SiteContent;
use Foomo\SimpleData\VoMapper;
use Foomo\Timer;

/**
 * @link www.foomo.org
 * @license www.gnu.org/licenses/lgpl.txt
 */
abstract class AbstractProxy
{
	/**
	 * turn this of for higher performance
	 * @var bool
	 */
	public static $mapData = true;
	protected function mapResponse($response, $voClass)
	{
		if(self::$mapData) {
			return VoMapper::map($response, new $voClass);
		} else {
			return $response;
		}
	}
	/**
	 * get content
	 *
	 * @param Vo\Requests\Content $contentRequest
	 *
	 * @return SiteContent
	 */
	abstract public function getContent(Vo\Requests\Content $contentRequest);

	/**
	 *
	 * @param string $region
	 * @param string $language
	 * @param string[] $ids
	 *
	 * @return string[]
	 */
	abstract public function getURIs($region, $language, $ids);

	/**
	 * @param string $id
	 *
	 * @return string[]
	 */
	abstract public function getItemMap($id);

	/**
	 * @param Vo\Requests\Nodes $nodeRequest
	 *
	 * @return Node[] hash
	 */
	abstract public function getNodes(Vo\Requests\Nodes $nodeRequest);
}