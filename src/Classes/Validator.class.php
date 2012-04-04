<?php

/**
 * Contains the validation class
 *
 * PHP Version 5.2
 *
 * @category Validator
 * @package  Classes
 * @author   Jose Antony <jose@joseantony.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://www.joseantony.com
 */

/**
 * Class consist of validation functions
 *
 * PHP Version 5.2
 *
 * @category Controller
 * @package  Classes
 * @author   Jose Antony <jose@joseantony.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://www.joseantony.com
 */

class Validator
{

	/**
	 * Compare two arguments and check if both are same if that exist. Throws error
	 * if both doesn't match
	 * 
	 * @param array  $arrApplicationArguments Arguments array
	 * @param string $strShortKey             short key
	 * @param string $strLongKey              long key
	 * @param string $boolOptional            optional check flag
	 * 
	 * @throws Exception
	 * 
	 * @return null
	 */

	public static function compareAndValidate(
		array $arrApplicationArguments,
		$strShortKey,
		$strLongKey,
		$boolOptional=false
	) {
		if (isset($arrApplicationArguments[$strShortKey]) === true
			&& isset($arrApplicationArguments[$strLongKey]) === true
		) {
			$arrApplicationArguments[$strShortKey] = trim($arrApplicationArguments[$strShortKey]);
			$arrApplicationArguments[$strLongKey]  = trim($arrApplicationArguments[$strLongKey]);

			if (strcmp($arrApplicationArguments[$strShortKey], $arrApplicationArguments[$strLongKey]) === 0) {
				return $arrApplicationArguments[$strShortKey];
			} else {
				throw new Exception('Argument inconsitancy');
			}
		}

		if (isset($arrApplicationArguments[$strShortKey]) === true) {
			$arrApplicationArguments[$strShortKey] = trim($arrApplicationArguments[$strShortKey]);
			return $arrApplicationArguments[$strShortKey];
		}

		if (isset($arrApplicationArguments[$strLongKey]) === true) {
			$arrApplicationArguments[$strLongKey] = trim($arrApplicationArguments[$strLongKey]);
			return $arrApplicationArguments[$strLongKey];
		}

		if ($boolOptional === false) {
			throw new Exception('Argument missing');
		}

		return null;

	}//end compareAndValidate()


}//end class
