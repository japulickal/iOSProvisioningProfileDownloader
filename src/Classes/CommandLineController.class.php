<?php

/** 
 * @author jose
 * 
 */

require_once 'Classes/Validator.class.php';

class CommandLineController {
	
	const kUsernameShortKey = 'u';
	const kPasswordShortKey = 'p';
	const kTeamShortKey = 't';
	
	const kUsernameLongKey = 'username';
	const kPasswordLongKey = 'password';
	const kTeamLongKey = 'team';
	
	private $arrValidationErrors;
	private $username;
	private $password;
	private $teamId;
	
	public function __construct() {
		
		$this->arrValidationErrors = array();
		$this->username            = null;
		$this->password            = null;
		$this->teamId              = null;
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
			$this->username = Validator::compareAndValidate(
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
			$this->password = Validator::compareAndValidate(
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
			$this->teamId = Validator::compareAndValidate(
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
		$this->validateArguments();
		
		if (count($this->arrValidationErrors) > 0) {
			print_r($this->arrValidationErrors);
			
			return 1;
		}
		//$arrApplicationArguments = getopt('u:p:t:', array('username:', 'password', 'team'));
		//print_r($arrApplicationArguments);
	}
}