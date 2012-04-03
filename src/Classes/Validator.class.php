<?php

class Validator {

	public static function compareAndValidate(
			$arrApplicationArguments, 
			$strShortKey, 
			$strLongKey, 
			$boolOptional = false
		) {
	
		if (
				isset($arrApplicationArguments[$strShortKey]) &&
				isset($arrApplicationArguments[$strLongKey])
		) {
	
			$arrApplicationArguments[$strShortKey] = trim($arrApplicationArguments[$strShortKey]);
			$arrApplicationArguments[$strLongKey]  = trim($arrApplicationArguments[$strLongKey]);
	
			if (strcmp($arrApplicationArguments[$strShortKey], $arrApplicationArguments[$strLongKey]) == 0) {
	
				return $arrApplicationArguments[$strShortKey];
			} else {
	
				throw new Exception('Argument inconsitancy');
			}
		}
	
		if (isset($arrApplicationArguments[$strShortKey])) {
			$arrApplicationArguments[$strShortKey] = trim($arrApplicationArguments[$strShortKey]);
	
			return 	$arrApplicationArguments[$strShortKey];
		}
	
		if (isset($arrApplicationArguments[$strLongKey])) {
			$arrApplicationArguments[$strLongKey] = trim($arrApplicationArguments[$strLongKey]);
	
			return 	$arrApplicationArguments[self::kUsernameLongKey];
		}
	
		if (!$boolOptional) {
			throw new Exception('Argument missing');
		}
	
		return null;
	}
	
}