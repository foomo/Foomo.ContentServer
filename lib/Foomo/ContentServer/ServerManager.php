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

/**
 * @link www.foomo.org
 * @license www.gnu.org/licenses/lgpl.txt
 */
class ServerManager
{
	private $pid;

	public static function startServer(DomainConfig $config)
	{
		// http://braincrafted.com/php-background-processes/
		file_put_content(
			self::getPidFile($config),
			shell_exec(sprintf(
				'%s > %s 2>&1 & echo $!',
				$config->getServerCommand(),
				$config->getLogfile()
			))
		);
	}
	public static function serverIsRunning($config)
	{
		return false;
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
			try {
				$result = shell_exec(sprintf('ps %d', $this->pid));
				if(count(preg_split("/\n/", $result)) > 2) {
					return true;
				}
			} catch(Exception $e) {}
		*/

	}
	public static function getPidFile(DomainConfig $config)
	{
		return Module::getTempDir() . DIRECTORY_SEPARATOR . $config->getId();
	}

}