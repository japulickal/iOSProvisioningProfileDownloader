<?php

/**
 * This file consist of the Class CommandLineController. This class is responsible
 * for controlling the eniter logic flow
 *
 * PHP Version 5.2
 *
 * @category Controller
 * @package  Classes
 * @author   Jose Antony <jose@joseantony.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://www.joseantony.com
 */

require_once dirname(__FILE__).'/Validator.class.php';
require_once dirname(__FILE__).'/HTTP/HTTPGet.class.php';
require_once dirname(__FILE__).'/HTTP/HTTPPost.class.php';
require_once dirname(__FILE__).'/Apple/AppleLogin.class.php';
require_once dirname(__FILE__).'/Apple/AppleTeamSelect.class.php';
require_once dirname(__FILE__).'/Apple/AppleFetchProfile.class.php';

/**
 * CommandLineController will fetch and validate all the inputs.
 * Once the inputs are validated it will start Login, select the user team
 * and then download the provisioning profiles
 *
 * PHP Version 5.2
 *
 * @category Controller
 * @package  Classes
 * @author   Jose Antony <jose@joseantony.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://www.joseantony.com
 */

class CommandLineController
{
	const USERNAME_SHORT_KEY = 'u';
	const PASSWORD_SHORT_KEY = 'p';
	const TEAM_SHORT_KEY     = 't';

	const USERNAME_LONG_KEY = 'username';
	const PASSWORD_LONG_KEY = 'password';
	const TEAM_LONG_KEY     = 'team';

	private $_arrValidationErrors;

	private $_strUsername;

	private $_strPassword;

	private $_strTeamId;

	/**
	 * Constructor to initial the variables
	 */

	public function __construct()
	{
		$this->_arrValidationErrors = array();
		$this->_strUsername         = null;
		$this->_strPassword         = null;
		$this->_strTeamId           = null;

	}//end __construct()


	/**
	 * Get the application arguments
	 *
	 * @return array
	 */
	public function getApplicationArguments()
	{
		if (php_sapi_name() === "cli") {
			return getopt(
				self::USERNAME_SHORT_KEY.':'.self::PASSWORD_SHORT_KEY.':'.self::TEAM_SHORT_KEY.':',
				array(
				 self::USERNAME_LONG_KEY.':',
				 self::PASSWORD_LONG_KEY.'password:',
				 self::TEAM_LONG_KEY.'team:',
				)
			);
		} else {
			return $_REQUEST;
		}

	}//end getApplicationArguments()


	/**
	 * Validate the username and set that to instance variable
	 *
	 * @return null
	 */

	public function validateAndSetUsername()
	{
		try {
			$this->_strUsername = Validator::compareAndValidate(
				$this->getApplicationArguments(),
				self::USERNAME_SHORT_KEY,
				self::USERNAME_LONG_KEY
			);
		} catch (ArgumentMisMatchException $objArgumentMisMatchException) {
			$this->_arrValidationErrors[] = 'Username error! Make sure that you use either -u or --username option';
		} catch (ArgumentMissingException $objArgumentMissingException) {
			$this->_arrValidationErrors[] = 'Username error! Make sure that you use either -u or --username option';
		}

	}//end validateAndSetUsername()


	/**
	 * Validate the password and set that to instance variable
	 *
	 * @return null
	 */

	public function validateAndSetPassword()
	{
		try {
			$this->_strPassword = Validator::compareAndValidate(
				$this->getApplicationArguments(),
				self::PASSWORD_SHORT_KEY,
				self::PASSWORD_LONG_KEY
			);
		} catch (ArgumentMisMatchException $objArgumentMisMatchException) {
			$this->_arrValidationErrors[] = 'Password error! Make sure that you use either -p or --password option';
		} catch (ArgumentMissingException $objArgumentMissingException) {
			$this->_arrValidationErrors[] = 'Password error! Make sure that you use either -p or --password option';
		}

	}//end validateAndSetPassword()


	/**
	 * Validate the team and set that to instance variable
	 *
	 * @return null
	 */

	public function validateAndSetTeam()
	{
		try {
			$this->_strTeamId = Validator::compareAndValidate(
				$this->getApplicationArguments(),
				self::TEAM_SHORT_KEY,
				self::TEAM_LONG_KEY,
				true
			);
		} catch (Exception $e) {
			$this->_arrValidationErrors[] = 'Team error! Make sure that you use either -t or --team option';
		}

	}//end validateAndSetTeam()


	/**
	 * Funcction to validate all incoming arguments namely
	 * username
	 * password
	 * teamId
	 *
	 * @return null
	 */

	public function validateArguments()
	{
		$this->validateAndSetUsername();
		$this->validateAndSetPassword();
		$this->validateAndSetTeam();

	}//end validateArguments()


	/**
	 * Main function call this will execute the entire project
	 * This function will first validate the user input and Login to the apple
	 * After login depending on the team parameter it will try to select the team
	 * Then it will start downloading the provisioning profiles
	 *
	 * @return int
	 */

	public function execute()
	{
		echo "Initializing ...\n";

		echo "Validating inputs ...\n";
		$this->validateArguments();

		if (count($this->_arrValidationErrors) > 0) {
			foreach ($this->_arrValidationErrors as $strError) {
				echo "$strError\n";
			}

			return 1;
		}

		@unlink('/tmp/cookies.txt');

		echo "Login to developer portal with username {$this->_strUsername}...\n";

		$objAppleLogin = new AppleLogin($this->_strUsername, $this->_strPassword);
		$objAppleLogin->performLogin();

		if (empty($this->_strTeamId) === false) {
			echo "Selecting team {$this->_strTeamId} ...\n";

			$objAppleTeamSelect = new AppleTeamSelect($this->_strTeamId);
			$objAppleTeamSelect->performTeamSelect();
		}

		echo "Fetch Profiles ...\n";

		$objAppleFetchProfile = new AppleFetchProfile();
		$objAppleFetchProfile->perfromFetchProfile();

		return 0;

	}//end execute()


}//end class