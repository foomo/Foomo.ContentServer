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
use Foomo\CliCall;

/**
 * @link www.foomo.org
 * @license www.gnu.org/licenses/lgpl.txt
 */
class ServerManager
{
	public static function callGarden(DomainConfig $config, array $parameters)
	{
		$uri = '';
		foreach($parameters as $parameter) {
			$uri .= '/' . urlencode($parameter);
		}
		return file_get_contents($config->gardenDaemonAddress . $uri);
	}
	public static function startServer(DomainConfig $config)
	{
		self::callGarden($config, $config->getServerSpawnCommandArray());
	}
	public static function kill(DomainConfig $config)
	{
		self::callGarden($config, array('cmd', 'kill', $config->getDaemonName()));
	}
	public static function serverIsRunning(DomainConfig $config)
	{
		$daemonName = $config->getDaemonName();
		foreach(json_decode($reply = self::callGarden($config, array('status'))) as $daemonStatus) {
			if($daemonStatus->name == $daemonName && $daemonStatus->running == true) {
				return true;
			}
		}
		return false;
	}
}