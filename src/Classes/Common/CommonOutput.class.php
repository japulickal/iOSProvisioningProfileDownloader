<?php
/**
 * Contains the abstract CommonOutput class
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
 * This is an abstract class for output printing
 *
 * @category Common
 * @package  Classes
 * @author   Jose Antony <jose@joseantony.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://www.joseantony.com
 */
abstract class CommonOutput {

	protected static $objInstance;

	protected $arrMessage;

	protected function __construct() {
		self::$objInstance = null;
	}

	public static function getCommonOutput() {
		if (empty(self::$objInstance) === true) {
			if (php_sapi_name() === "cli") {
				self::$objInstance = new CommonCliOutput();
			} else {
				self::$objInstance = new CommonHttpOutput();
			}
		}

		return self::$objInstance;
	}

	abstract public function output($strOutput);

}
