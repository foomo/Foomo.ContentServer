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

use Foomo\ContentServer\DomainConfig;
use Foomo\ContentServer\ServerManager;
use Foomo\Lock;
use Foomo\Timer;

/**
 * @link    www.foomo.org
 * @license www.gnu.org/licenses/lgpl.txt
 */
class Client
{
	// --------------------------------------------------------------------------------------------
	// ~ Constants
	// --------------------------------------------------------------------------------------------

	const SOCKET_READ_WINDOW_SIZE = 8192;
	const MAX_CONNECTION_ATTEMPTS = 1;

	// --------------------------------------------------------------------------------------------
	// ~ Static variables
	// --------------------------------------------------------------------------------------------

	/**
	 * @var bool
	 */
	public static $debug = false;

	// --------------------------------------------------------------------------------------------
	// ~ Variables
	// --------------------------------------------------------------------------------------------

	/**
	 * @var string
	 */
	private $server;
	/**
	 * @var resource
	 */
	private $socket;

	// --------------------------------------------------------------------------------------------
	// ~ Constructor
	// --------------------------------------------------------------------------------------------

	/**
	 * @param DomainConfig $config
	 * @throws \Exception
	 */
	public function __construct(DomainConfig $config)
	{
		$this->server = $config->server;
		$urlParts = parse_url($this->server);
		if (!isset($urlParts['port'])) {
			trigger_error('you have to specify a port, because there is no std port for me', E_USER_ERROR);
		}
		if (!isset($urlParts['host'])) {
			trigger_error('i am missing a host to connect to in :' . $this->server, E_USER_ERROR);
		}
		$address = gethostbyname($urlParts['host']);
		$this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		if ($this->socket === false) {
			trigger_error('failed to create socket: ' . socket_strerror(socket_last_error()), E_USER_ERROR);
		}
		$connected = false;
		$attempts = 0;
		while ($connected === false) {
			$connected = socket_connect($this->socket, $address, $urlParts['port']);
			if ($attempts > self::MAX_CONNECTION_ATTEMPTS) {
				header('HTTP/1.1 503 Service Unavailable');
				header('Status: 503 Service Unavailable');
				header('Retry-After: 15');
				throw new \Exception('failed to connect socket : ' . socket_strerror(socket_last_error($this->socket), 503));
			} else if ($connected === false) {
				throw new \Exception('failed to connect socket : ' . socket_strerror(socket_last_error($this->socket), 500));
			}
			$attempts++;
		}
	}

	/**
	 * @param $handler
	 * @param $request
	 * @return mixed|void
	 */
	public function call($handler, $request)
	{
		if (self::$debug) {
			return $this->sendDebug($handler, json_encode($request));
		} else {
			return $this->send($handler, json_encode($request));
		}
	}

	// --------------------------------------------------------------------------------------------
	// ~ Private methods
	// --------------------------------------------------------------------------------------------

	/**
	 * @param $handler
	 * @param $rawData
	 * @return void|mixed
	 */
	private function sendDebug($handler, $rawData)
	{
		Timer::start($topic = __METHOD__ . ' sending data to ' . $handler);
		$sendBytes = $handler . ':' . strlen($rawData) . $rawData;
		$bytesWritten = socket_write($this->socket, $sendBytes, strlen($sendBytes));
		if ($bytesWritten != strlen($sendBytes)) {
			trigger_error('failed to write my bytes', E_USER_ERROR);
		}
		Timer::stop($topic);
		Timer::start($wt = __METHOD__ . ' waiting ' . $handler);
		$bytesRead = 0;
		$bytesToRead = -1;
		$msg = '';
		$window = 1;
		while (false !== $incoming = socket_read($this->socket, $window)) {
			if ($bytesToRead < 0) {
				if ($incoming == '{') {

					$bytesToRead = ((int) $msg) - 1;
					Timer::stop($wt);
					Timer::start($rt = __METHOD__ . ' receive ' . $bytesToRead . ' for ' . $handler);
					$msg = '{';
					$window = self::SOCKET_READ_WINDOW_SIZE;
				} else {
					$msg .= $incoming;
				}
			} else {
				Timer::addMarker(__METHOD__ . " receiving data " . strlen($incoming));
				$bytesRead += strlen($incoming);
				$msg .= $incoming;
				if ($bytesRead == $bytesToRead) {
					Timer::stop($rt);
					return json_decode($msg);
				}
			}
		}
		$this->triggerSendError($bytesToRead, $bytesRead, $incoming, $msg);
	}

	/**
	 * @param $bytesToRead
	 * @param $bytesRead
	 * @param $incoming
	 * @param $msg
	 */
	private function triggerSendError($bytesToRead, $bytesRead, $incoming, $msg)
	{
		trigger_error(
			'well, that did not work bytes to read: ' . $bytesToRead . ', bytesRead: ' .
			$bytesRead . ', incoming: ' . $incoming . ', msg: ' . $msg,
			E_USER_ERROR
		);
	}

	/**
	 * @param $handler
	 * @param $rawData
	 * @return void|mixed
	 */
	private function send($handler, $rawData)
	{
		$sendBytes = $handler . ':' . strlen($rawData) . $rawData;
		$bytesWritten = socket_write($this->socket, $sendBytes, strlen($sendBytes));
		if ($bytesWritten != strlen($sendBytes)) {
			trigger_error('failed to write my bytes', E_USER_ERROR);
		}
		$bytesRead = 0;
		$bytesToRead = -1;
		$msg = '';
		$window = 1;
		while (false !== $incoming = socket_read($this->socket, $window)) {
			if ($bytesToRead < 0) {
				if ($incoming == '{') {
					$bytesToRead = ((int) $msg) - 1;
					$msg = '{';
					$window = self::SOCKET_READ_WINDOW_SIZE;
				} else {
					$msg .= $incoming;
				}
			} else {
				$bytesRead += strlen($incoming);
				$msg .= $incoming;
				if ($bytesRead == $bytesToRead) {
					return json_decode($msg);
				}
			}
		}
		$this->triggerSendError($bytesToRead, $bytesRead, $incoming, $msg);
	}

	// --------------------------------------------------------------------------------------------
	// ~ Magic methods
	// --------------------------------------------------------------------------------------------

	/**
	 *
	 */
	public function __destroy()
	{
		socket_close($this->socket);
	}
}
