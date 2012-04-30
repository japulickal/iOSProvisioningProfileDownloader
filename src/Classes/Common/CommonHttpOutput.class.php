<?php

class CommonHttpOutput extends CommonOutput {

	protected $arrMessage = array(
				'usernameMissing' => 'Please enter the username to login',
				'usernameInvalid' => 'Please enter a valid username to login',
				'passwordMissing' => 'Please enter the password to login',
				'passwordInvalid' => 'Please enter a valid password to login',
				'teamInvalid' => 'please enter a valid team id to sync'
			);

	protected function __construct() {
		CommonOutput::$objInstance = null;
	}

	public function output($strOutput) {
		echo $strOutput."<br />";
	}
}

