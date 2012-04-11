<?php

/**
 * Contains the AppleLogin class
 *
 * PHP Version 5.2
 *
 * @category Apple
 * @package  Classes
 * @author   Jose Antony <jose@joseantony.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://www.joseantony.com
 */

/**
 * Contains functions to login to apple portal and save the cookie for future use
 *
 * @category Apple
 * @package  Classes
 * @author   Jose Antony <jose@joseantony.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://www.joseantony.com
 */
class AppleLogin
{

	private $_strUsername;

	private $_strPassword;

	private $_strWosid;

	private $_strTheAuxValue;

	private $_strLoginAction;

	/**
	 * set the initial values for the instance variables
	 *
	 * @param string $strUsername user name
	 * @param string $strPassword password
	 */

	public function __construct($strUsername, $strPassword)
	{
		$this->_strUsername = $strUsername;
		$this->_strPassword = $strPassword;

		$this->_strWosid       = null;
		$this->_strLoginAction = null;
		$this->_strTheAuxValue = null;

	}//end __construct()


	/**
	 * Perfom the actual login using the given username and password.
	 * This will actually fetch the login information like the Wosid for
	 * constructing the login url and then post username and the password
	 *
	 * @return void
	 */

	public function performLogin()
	{
		$this->_fetchLoginInformations();

		$this->_login();

	}//end performLogin()


	/**
	 * Performs the actual login by posting the username and password
	 *
	 * @throws InvalidLoginException
	 *
	 * @return void
	 */

	private function _login()
	{
		$httpPost = new HTTPPost();

		$strData = $httpPost->post(
			$this->_strLoginAction,
			array(
			 'theAccountName' => $this->_strUsername,
			 'theAccountPW'   => $this->_strPassword,
			 '1.Continue.x'   => '44',
			 'wosid'          => $this->_strWosid,
			)
		);

		if (preg_match('/Your Apple ID or password was entered incorrectly/', $strData) === 1) {
			throw new InvalidLoginException('Invalid Username / Password');
		}

	}//end _login()


	/**
	 * Function to fetch the login information like Wosid and TheAuxValue
	 *
	 * @throws InvalidDomDocumentException
	 *
	 * @return void
	 */

	private function _fetchLoginInformations()
	{
		$httpGet = new HTTPGet();
		$strData = $httpGet->get(
			'https://developer.apple.com/ios/manage/provisioningprofiles/index.action'
		);

		$objDomDocument = new DOMDocument();
		$bolDomStatus   = @$objDomDocument->loadHTML($strData);

		if ($bolDomStatus === false) {
			throw new InvalidDomDocumentException('Invalid Dom Document');
		}

		$objDomFormElements = $objDomDocument->getElementsByTagName('form');

		foreach ($objDomFormElements as $objFormDomElement) {
			$arrUrlDetails = parse_url($httpGet->getLastUrl());
			$this->_strLoginAction = $arrUrlDetails['scheme'].'://'.$arrUrlDetails['host'].$objFormDomElement->getAttribute('action');

			$objDomInputElements = $objFormDomElement->getElementsByTagName('input');

			foreach ($objDomInputElements as $objDomInputElement) {
				if (preg_match('/wosid/', $objDomInputElement->getAttribute('name')) === 1) {
					$this->_strWosid = $objDomInputElement->getAttribute('value');
				}

				if (preg_match('/theAuxValue/', $objDomInputElement->getAttribute('name')) === 1) {
					$this->_strTheAuxValue = $objDomInputElement->getAttribute('value');
				}
			}
		}

	}//end _fetchLoginInformations()


}//end class