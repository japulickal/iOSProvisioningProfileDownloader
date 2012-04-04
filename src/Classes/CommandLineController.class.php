<?php

/** 
 * @author jose
 * 
 */

require_once 'Classes/Validator.class.php';
require_once 'Classes/HTTP/HTTPGet.class.php';
require_once 'Classes/HTTP/HTTPPost.class.php';
require_once 'Classes/Apple/AppleLogin.class.php';
require_once 'Classes/Apple/AppleTeamSelect.class.php';
require_once 'Classes/Apple/AppleFetchProfile.class.php';

class CommandLineController {
	
	const kUsernameShortKey = 'u';
	const kPasswordShortKey = 'p';
	const kTeamShortKey = 't';
	
	const kUsernameLongKey = 'username';
	const kPasswordLongKey = 'password';
	const kTeamLongKey = 'team';
	
	private $arrValidationErrors;
	private $strUsername;
	private $strPassword;
	private $strTeamId;
	
	public function __construct() {
		
		$this->arrValidationErrors = array();
		$this->strUsername            = null;
		$this->strPassword            = null;
		$this->strTeamId              = null;
	}
	
	public function getApplicationArguments() {
		
		return getopt(
				self::kUsernameShortKey.':'.self::kPasswordShortKey.':'.self::kTeamShortKey.':',
				array(
						self::kUsernameLongKey.':',
						self::kPasswordLongKey.'password:',
						self::kTeamLongKey.'team:'
					)
			);
	}	
	
	public function validateAndSetUsername() {
		try {
			$this->strUsername = Validator::compareAndValidate(
					$this->getApplicationArguments(),
					self::kUsernameShortKey, 
					self::kUsernameLongKey
				);
		} catch (Exception $e) {
			$this->arrValidationErrors[] = 'Username error! Make sure that you use either -u or --username option';
		}
	}
	
	public function validateAndSetPassword() {		
		try {
			$this->strPassword = Validator::compareAndValidate(
					$this->getApplicationArguments(), 
					self::kPasswordShortKey, 
					self::kPasswordLongKey
				);
		} catch (Exception $e) {
			$this->arrValidationErrors[] = 'Password error! Make sure that you use either -p or --password option';
		}
	}
	
	public function validateAndSetTeam() {
		try {
			$this->strTeamId = Validator::compareAndValidate(
					$this->getApplicationArguments(), 
					self::kTeamShortKey, 
					self::kTeamLongKey, 
					true
				);
		} catch (Exception $e) {
			$this->arrValidationErrors[] = 'Team error! Make sure that you use either -t or --team option';
		}
	}
	
	public function validateArguments() {
		$this->validateAndSetUsername();
		$this->validateAndSetPassword();
		$this->validateAndSetTeam();
	}
	
	
	
	public function execute() {
		
		echo "Initializing ...\n";
		
		echo "Validating inputs ...\n";
		$this->validateArguments();
		
		if (count($this->arrValidationErrors) > 0) {
			print_r($this->arrValidationErrors);
			
			return 1;
		}
		
		@unlink('/tmp/cookies.txt');
		
		echo "Login to developer portal with username {$this->strUsername}...\n";
	
		$objAppleLogin = new AppleLogin($this->strUsername, $this->strPassword);
		$objAppleLogin->performLogin();
		
		if (!empty($this->strTeamId)) {
			echo "Selecting team {$this->strTeamId} ...\n";
			
			$objAppleTeamSelect = new AppleTeamSelect($this->strTeamId);
			$objAppleTeamSelect->performTeamSelect();
		}

		echo "Fetch Profiles ...\n";

		$objAppleFetchProfile = new AppleFetchProfile();
		$objAppleFetchProfile->perfromFetchProfile();
	}
}