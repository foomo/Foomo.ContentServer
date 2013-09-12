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
	public static function startServer(DomainConfig $config)
	{
		file_put_contents(
			self::getPidFile($config),
			$pid = trim(shell_exec($cmd = sprintf(
				'%s > %s 2>&1 & echo $!',
				$config->getServerCommand(),
				$config->getLogfile()
			)))
		);
		if(!self::serverIsRunning($config)) {
			trigger_error('could not start server with this config ' . var_export($config, true), E_USER_ERROR);
		}
	}
	public static function kill(DomainConfig $config)
	{
		if(self::serverIsRunning($config)) {
			$call = CliCall::create('kill', array(self::getPid($config)));
			$call->execute();
		}
	}
	public static function serverIsRunning(DomainConfig $config)
	{
		$pid = self::getPid($config);
		if($pid) {
			$call = CliCall::create('ps', array('--no-headers', '-o%p', '-p', $pid));
			$call->execute();
			return $call->exitStatus == 0 && trim($call->stdOut) == $pid;
		} else {
			return false;
		}
		// http://stackoverflow.com/questions/11532188/how-to-get-rid-of-the-headers-in-a-ps-command-in-mac-os-x
		// ps -p 111 -o %p | sed 1d
		// http://stackoverflow.com/questions/3043978/bash-how-to-check-if-a-process-id-pid-exists

		/*
		 *
		 *
		 *
			frederik@renelezard-dev:~/.ssh$ ps --no-headers -p 19771
			19771 ?        00:00:00 apache2
			frederik@renelezard-dev:~/.ssh$ ps -p 19771
  			PID TTY          TIME CMD
			19771 ?        00:00:00 apache2
		*/

	}
	public static function getPid(DomainConfig $config)
	{
		$pidFile = self::getPidFile($config);
		$pid = null;
		if(file_exists($pidFile)) {
			$pid = trim(file_get_contents(self::getPidFile($config)));
		}
		return !empty($pid)?$pid:null;
	}
	public static function getPidFile(DomainConfig $config)
	{
		return Module::getTempDir() . DIRECTORY_SEPARATOR . $config->getName();
	}

}