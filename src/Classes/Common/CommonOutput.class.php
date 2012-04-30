<?php

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
