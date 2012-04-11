<?php

/**
 * Contains the AppleFetchProfile class
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
 * Contains functions to fetch and download the apple development profile
 *
 * @category Apple
 * @package  Classes
 * @author   Jose Antony <jose@joseantony.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://www.joseantony.com
 */

class AppleFetchProfile
{

	private $_arrProfileUrls;

	/**
	 * Initialize the instance variables
	 *
	 */

	public function __construct()
	{
		$this->_arrProfileUrls = array();

	}//end __construct()


	/**
	 * Initialize the profile fetching
	 *
	 * @return void
	 */

	public function perfromFetchProfile()
	{
		$this->_fetchProfileUrls('https://developer.apple.com/ios/manage/provisioningprofiles/index.action');
		$this->_fetchProfileUrls('https://developer.apple.com/ios/manage/provisioningprofiles/viewDistributionProfiles.action');

	}//end perfromFetchProfile()


	/**
	 * Fetch profiles from the given URL
	 *
	 * @param string $strUrl apple url to fetch
	 *
	 * @return void
	 */

	private function _fetchProfileUrls($strUrl)
	{
		$httpGet = new HTTPGet();
		$strData = $httpGet->get($strUrl);
		if (preg_match('/selectTeam\.action/', $httpGet->getLastUrl()) === 1) {
			AppleTeamSelect::validateTeamSelect($strData);
		}

		$this->_parseHtml($strData);
		$this->_fetchProfiles();

	}//end _fetchProfileUrls()


	/**
	 * Fetch and download all profiles listed in _arrProfileUrl
	 *
	 * @return void
	 */
	private function _fetchProfiles()
	{
		foreach ($this->_arrProfileUrls as $intKey => $strProfileUrl) {
			echo "Fetching profile $strProfileUrl\n";

			$httpGet = new HTTPGet();
			$strData = $httpGet->get($strProfileUrl);

			$this->_saveMobileProvisioning($strData);
		}

	}//end _fetchProfiles()


	/**
	 * Save the given to content to user provisioning profile path.
	 * Code will parse the data to find the file name
	 *
	 * @param string $strData provisioning profile content
	 *
	 * @return void
	 */

	private function _saveMobileProvisioning($strData)
	{
		preg_match('/<key>UUID<\\/key>\n	<string>(.*)<\\/string>/', $strData, $arrMatches);

		$strUser = trim(get_current_user());
		echo "Saving profile {$arrMatches[1]}\n";

		file_put_contents("/Users/$strUser/Library/MobileDevice/Provisioning Profiles/{$arrMatches[1]}.mobileprovision", $strData);

	}//end _saveMobileProvisioning()


	/**
	 * Parse the entire html site and filter out the profile URLS
	 * into the array _arrProfileUrls
	 *
	 * @param string $strData html content of provisioing list page
	 *
	 * @throws InvalidDomDocumentException
	 *
	 * @return void
	 */

	private function _parseHtml($strData)
	{
		$objDomDocument = new DOMDocument();
		$bolDomStatus   = @$objDomDocument->loadHTML($strData);

		if ($bolDomStatus === false) {
			throw new InvalidDomDocumentException('Invalid Dom Document');
		}

		$objDomTableElements = $objDomDocument->getElementsByTagName('table');

		foreach ($objDomTableElements as $objDomTableElement) {
			$objTableRows = $objDomTableElement->getElementsByTagName('tr');

			foreach ($objTableRows as $objTableRow) {
				$objTableColumns = $objTableRow->getElementsByTagName('td');

				$arrRowContent = array();
				foreach ($objTableColumns as $objTableColumn) {
					if ($objTableColumn->getAttribute('class') === 'profile') {
						$objSpanElements = $objTableColumn->getElementsByTagName('span');
						foreach ($objSpanElements as $objSpanElement) {
							$arrRowContent['name'] = $objSpanElement->nodeValue;
							break;
						}

						continue;
					}

					if ($objTableColumn->getAttribute('class') === 'statusXcode') {
						$arrRowContent['status'] = trim($objTableColumn->nodeValue);
						continue;
					}

					if ($objTableColumn->getAttribute('class') === 'action') {
						if (preg_match('/Active/', $arrRowContent['status']) === 1) {
							$objATagElements = $objTableColumn->getElementsByTagName('a');

							foreach ($objATagElements as $objATagElement) {
								$objImagElements = $objATagElement->getElementsByTagName('img');

								foreach ($objImagElements as $objImageElement) {
									if ($objImageElement->getAttribute('alt') === 'download') {
										$arrRowContent['download'] = $objATagElement->getAttribute('href');
										break;
									}
								}
							}
						}

						break;
					}
				}//end foreach

				if (isset($arrRowContent['download']) === true) {
					$this->_arrProfileUrls[] = 'https://developer.apple.com'.$arrRowContent['download'];
				}
			}//end foreach
		}//end foreach

	}//end _parseHtml()


}//end class