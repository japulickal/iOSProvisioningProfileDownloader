<?php

class CommonCliOutput extends CommonOutput {

	protected $arrMessage = array(
				'usernameMissing' => "Username missing!\n\nUsage:\n\tphp fetchProfiles -u <username> -p <password> -t <teamId>",
				'usernameInvalid' => "Username invalid!\n\nUsage:\n\tphp fetchProfiles -u <username> -p <password> -t <teamId>",
				'passwordMissing' => "Password missing!\n\nUsage:\n\tphp fetchProfiles -u <username> -p <password> -t <teamId>",
				'passwordInvalid' => "Password invalid!\n\nUsage:\n\tphp fetchProfiles -u <username> -p <password> -t <teamId>",
				'teamInvalid' => "Team Id invalid!\n\nUsage:\n\tphp fetchProfiles -u <username> -p <password> -t <teamId>"
			);

	protected function __construct() {
		CommonOutput::$objInstance = null;
	}

	public function output($strOutput) {
		echo $strOutput."\n";
	}
}

