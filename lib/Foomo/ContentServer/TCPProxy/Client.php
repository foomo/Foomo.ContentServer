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

namespace Foomo\ContentServer\TCPProxy;

/**
 * @link www.foomo.org
 * @license www.gnu.org/licenses/lgpl.txt
 */
class Client
{
	private $server;
	private $socket;
	public function __construct($server)
	{
		$this->server = $server;
		$urlParts = parse_url($this->server);
		if(!isset($urlParts['port'])) {
			trigger_error('you have to specify a port, because there is no std port for me', E_USER_ERROR);
		}
		if(!isset($urlParts['host'])) {
			trigger_error('i am missing a host to connect to in :' . $server, E_USER_ERROR);
		}
		$address = gethostbyname($urlParts['host']);
		$this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		if ($this->socket === false) {
			trigger_error('failed to create socket: ' . socket_strerror(socket_last_error()), E_USER_ERROR);
		}
		$result = socket_connect($this->socket, $address, $urlParts['port']);
		if ($result === false) {
			trigger_error('failed to connect socket : ' . socket_strerror(socket_last_error($socket)), E_USER_ERROR);
		}
	}
	public function __destroy()
	{
		socket_close($this->socket);
	}

	private function send($handler, $rawData)
	{
		$sendBytes = $handler . ':' . strlen($rawData) . ':' . $rawData;
		$bytesWritten = socket_write($this->socket, $sendBytes, strlen($sendBytes));
		if($bytesWritten != strlen($sendBytes)) {
			trigger_error('failed to write my bytes', E_USER_ERROR);
		}
		$bytesRead = 0;
		$bytesToRead = -1;
		$msg = '';
		$window = 1;
		while (false !== $incoming = socket_read($this->socket, $window)) {
			if($bytesToRead < 0) {
				if($incoming == '{') {
					$bytesToRead = ((int) $msg) - 1;
					$msg = '{';
					$window = $bytesToRead;
				} else {
					$msg .= $incoming;
				}
			} else {
				$bytesRead += strlen($incoming);
				$msg .= $incoming;
				if($bytesRead == $bytesToRead) {
					return json_decode($msg);
				}
			}
		}
		var_dump('wtf', $bytesToRead, $bytesRead, $incoming, $msg);
	}
	public function call($handler, $request)
	{
		return $this->send($handler, json_encode($request));
	}
}