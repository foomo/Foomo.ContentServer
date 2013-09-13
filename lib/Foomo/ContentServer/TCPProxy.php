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
use Foomo\ContentServer\Vo\Content\SiteContent;
use Foomo\SimpleData\VoMapper;
use Foomo\Timer;

/**
 * @link www.foomo.org
 * @license www.gnu.org/licenses/lgpl.txt
 */
class TCPProxy extends AbstractProxy
{
	/**
	 * @var TCPProxy\Client
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

	public function getURIs($region, $language, $ids)
	{
		$request = new Vo\Requests\URIs();
		$request->ids = $ids;
		$request->region = $region;
		$request->language = $language;
		return $this->client->call('getURIs', $request)->reply;
	}
}