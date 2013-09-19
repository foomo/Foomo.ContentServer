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
class Proxy implements ProxyInterface
{
	/**
	 * @var TCPProxy\Client
	 * @internal get it from your domain config
	 */
	private $client;
	public function __construct(DomainConfig $config)
	{
		$this->client = new TCPProxy\Client($config);
	}

	/**
	 * get content
	 *
	 * @param Vo\Requests\Content $contentRequest
	 *
	 * @return SiteContent
	 */
	public function getContent(Vo\Requests\Content $contentRequest)
	{
		Timer::start('calling for content');
		$raw = $this->client->call('content', $contentRequest)->reply;
		Timer::stop('calling for content');
		Timer::start('map result');
		$mapped = $this->mapResponse($raw, new SiteContent());
		Timer::stop('map result');
		return $mapped;
	}

	/**
	 * @param string    $region
	 * @param string    $language
	 * @param \string[] $ids
	 *
	 * @return \string[]
	 */
	public function getURIs($region, $language, $ids)
	{
		$request = new Vo\Requests\URIs();
		$request->ids = $ids;
		$request->region = $region;
		$request->language = $language;
		return $this->client->call('getURIs', $request)->reply;
	}

	/**
	 * @param string $id
	 *
	 * @return \string[]
	 */
	public function getItemMap($id)
	{
		$request = new Vo\Requests\ItemMap();
		$request->id = $id;
		return $this->client->call('getItemMap', $request)->reply;
	}

	/**
	 * @param Vo\Requests\Nodes $nodeRequest
	 *
	 * @return Node[] hash
	 */
	public function getNodes(Vo\Requests\Nodes $nodeRequest)
	{
		$nodes = array();
		foreach($this->client->call('getNodes', $nodeRequest)->reply as $nodeName => $rawNode) {
			$nodes[$nodeName] = VoMapper::map($rawNode, new Node());
		}
		return $nodes;
	}

	/**
	 * @return Vo\Responses\Update
	 */
	public function update()
	{
		return self::mapResponse($this->client->call('update', new Vo\Requests\Update())->reply, new Vo\Responses\Update());
	}


	/**
	 * turn this of for higher performance
	 * @var bool
	 */
	public static $mapData = true;
	protected function mapResponse($response, $voClass)
	{
		$response = (array) $response;
		if(count($response) == 2 && sort(array_keys($response)) == array('code', 'message')) {
			throw new \Exception($response['message'], $response['code']);
		} else {
			if(self::$mapData) {
				return VoMapper::map($response, new $voClass);
			} else {
				return $response;
			}
		}
	}
}