<?php

/**
 * Contains the HTTPGet class
 *
 * PHP Version 5.2
 *
 * @category HTTP
 * @package  Classes
 * @author   Jose Antony <jose@joseantony.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://www.joseantony.com
 */

/**
 * Contains functions perfrom an http get request
 *
 * @category HTTP
 * @package  Classes
 * @author   Jose Antony <jose@joseantony.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://www.joseantony.com
 */
class HTTPGet
{

	private $_strLastUrl;

	private $_strData;

	/**
	 * performs a get operation against the given url
	 *
	 * @param string $strGetUrl get url
	 *
	 * @return string
	 */

	public function get($strGetUrl)
	{
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $strGetUrl);
		curl_setopt($ch, CURLOPT_POST, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_COOKIEJAR, '/tmp/cookies.txt');
		curl_setopt($ch, CURLOPT_COOKIEFILE, '/tmp/cookies.txt');

		$this->_strData    = curl_exec($ch);
		$this->_strLastUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);

		curl_close($ch);

		return $this->_strData;

	}//end get()


	/**
	 * Get the last fetch url after redirection of the the curl get request
	 *
	 * @return string
	 */

	public function getLastUrl()
	{
		return $this->_strLastUrl;

	}//end getLastUrl()


	/**
	 * return the html content of the curl request
	 *
	 * @return string
	 */

	public function getData()
	{
		return $this->_strData;

	}//end getData()


}//end class