<?php

/**
 * Contains the AppleTeamSelect class
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
 * Contains functions to select the user team
 *
 * @category Apple
 * @package  Classes
 * @author   Jose Antony <jose@joseantony.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://www.joseantony.com
 */
class AppleTeamSelect
{

	private $_strTeamId;

	/**
	 * initialize the instance variable
	 *
	 * @param string $strTeamId team id
	 */

	public function __construct($strTeamId)
	{
		$this->_strTeamId = $strTeamId;

	}//end __construct()


	/**
	 * Perform the actual http post and select the team
	 *
	 * @return void
	 */

	public function performTeamSelect()
	{
		$httpPost = new HTTPPost();

		$strData = $httpPost->post(
			'https://developer.apple.com/devcenter/saveTeamSelection.action',
			array(
			 'memberDisplayId'               => $this->_strTeamId,
			 'action:saveTeamSelection!save' => 'Continue',
			)
		);

		self::validateTeamSelect($strData);

	}//end performTeamSelect()


	/**
	 * Validate the team select by checking the return html
	 * if the return html again promt team select then we have a validation issue
	 * we then select all the team values from the html and throws and exception
	 *
	 * @param string $strData html content
	 *
	 * @throws InvalidDomDocumentException
	 * @throws InvalidTeamIdException
	 *
	 * @return void
	 */

	public static function validateTeamSelect($strData)
	{
		if (preg_match('/Multiple Developer Programs/', $strData) === 1) {
			$objDomDocument = new DOMDocument();
			$bolDomStatus   = @$objDomDocument->loadHTML($strData);

			if ($bolDomStatus === false) {
				throw new InvalidDomDocumentException('Invalid Dom Document');
			}

			$objDomSelectElements = $objDomDocument->getElementsByTagName('select');

			$strTeamIds = null;

			foreach ($objDomSelectElements as $objDomSelectElement) {
				if (preg_match('/memberDisplayId/', $objDomSelectElement->getAttribute('name')) === 1) {
					$objDomOptionElements = $objDomSelectElement->getElementsByTagName('option');

					foreach ($objDomOptionElements as $objDomOptionElement) {
						$strTeamIds .= "{$objDomOptionElement->nodeValue} => {$objDomOptionElement->getAttribute('value')}\n";
					}
				}
			}

			throw new InvalidTeamIdException("Invalid team Id. Please select one from the following\n".$strTeamIds);
		}//end if

	}//end validateTeamSelect()


}//end class