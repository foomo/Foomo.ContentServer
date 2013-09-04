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

namespace Foomo\ContentServer\HTTPProxy;

/**
 * @link www.foomo.org
 * @license www.gnu.org/licenses/lgpl.txt
 */
class Client
{
	private $server;
	public function __construct($server)
	{
		$this->server = $server;
	}
	public function get($uri)
	{
		return json_decode(file_get_contents($uri));
	}
	function post($uri, $data, $username = null, $password = null)
	{
		$data = http_build_query(array('request' => json_encode($data)));
		$opts = array('http' =>
			array(
				'method'  => 'POST',
				'header'  => 'Content-type: application/x-www-form-urlencoded',
				'content' => $data
			)
		);
		if($username && $password) {
			$opts['http']['header'] = ("Authorization: Basic " . base64_encode("$username:$password"));
		}
		$context = stream_context_create($opts);
		// error handling
		$json = file_get_contents($this->getUrl($uri), false, $context);
		//echo $json;
		return json_decode($json);
	}
	private function getUrl($uri)
	{
		return $this->server .= $uri;
	}
}