<?php

/**
 * Contains the CommonExceptionAutoLoader class
 *
 * PHP Version 5.2
 *
 * @category Common
 * @package  Classes
 * @author   Jose Antony <jose@joseantony.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://www.joseantony.com
 */

/**
 * When initialized this class register aspl
 *
 * @category Common
 * @package  Classes
 * @author   Jose Antony <jose@joseantony.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://www.joseantony.com
 */
class CommonExceptionAutoLoader
{
	public function __construct()
	{
		spl_autoload_register(array($this, '_loader'));
	}

	private function _loader($strClassName)
	{
		if (preg_match('/Exception/', $strClassName) === 1) {
			eval('class '.$strClassName.' extends Exception {}');
		}
	}
}