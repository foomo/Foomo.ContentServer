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
use Foomo\Config\AbstractConfig;

/**
 * @link www.foomo.org
 * @license www.gnu.org/licenses/lgpl.txt
 */
class DomainConfig extends AbstractConfig
{
	const NAME = 'Foomo.ContentServer.config';
	private $proxy;
	/**
	 * where to get my content from / where to spawn a server
	 *
	 * @var string
	 */
	public $server = "tcp://127.0.0.1:8081";
	/**
	 * this is where your repo json comes from
	 *
	 * @var string
	 */
	public $repo = "http://test.bestbytes/foomo/modules/Foomo.Page.Content/services/content.php";
	/**
	 * name for the server
	 *
	 * @var string
	 */
	public $name = "default";
	/**
	 * one of error, record, warning, notice, debug
	 *
	 * @var string
	 */
	public $logLevel = "record";
	/**
	 * map data from anonymous json data back to prop php objects (performance hog)
	 * @var bool
	 */
	public $mapData = false;
	/**
	 * @return Proxy
	 */
	public function getProxy()
	{
		if(is_null($this->proxy)) {
			switch($scheme = parse_url($this->server, PHP_URL_SCHEME)) {
				case 'tcp':
					$this->proxy = new Proxy($this);
					break;
				default:
					trigger_error('unsupported scheme - can not get a proxy for ' . $scheme, E_USER_ERROR);
			}
		}
		return $this->proxy;
	}
	public function getServerCommand()
	{
		$urlParts = parse_url($this->server);
		return Module::getBaseDir('bin') . DIRECTORY_SEPARATOR . 'content-server-linux-amd64 -address=' . addslashes($urlParts['host'] . ':' . $urlParts['port'] . ' -protocol=' . addslashes($urlParts['scheme']) . ' -logLevel=' . addslashes($this->logLevel) . ' ' . addslashes($this->repo) );
	}
	public function getLogfile()
	{
		return Module::getLogDir() . DIRECTORY_SEPARATOR . 'content-server-' . $this->name;
	}
}