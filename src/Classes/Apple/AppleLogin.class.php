<?php

class AppleLogin {
	
	private $strUsername;
	private $strPassword;
	private $strWosid;
	private $strTheAuxValue;
	
	private $strLoginAction;
	
	public function __construct($strUsername, $strPassword) {
		$this->strUsername = $strUsername;
		$this->strPassword = $strPassword;
		
		$this->strWosid = null;
		$this->strLoginAction = null;
		$this->strTheAuxValue = null;
	}

	public function performLogin() {
		$this->fetchLoginInformations();
		
		$this->login();
	}
	
	private function login() {
		$httpPost = new HTTPPost();
		
		$strData = $httpPost->post(
				$this->strLoginAction, 
				array (
						'theAccountName' => $this->strUsername,
						'theAccountPW'  => $this->strPassword,
						'1.Continue.x'  => '44',
						'wosid'         => $this->strWosid
					)
			);
		
		if (preg_match('/Your Apple ID or password was entered incorrectly/', $strData)) {
			throw new Exception('Invalid Username / Password');
		}
	}
	
	
	
	private function fetchLoginInformations() {
		$httpGet = new HTTPGet();
		$strData = $httpGet->get('https://developer.apple.com/ios/manage/provisioningprofiles/index.action');
		
		
		$objDomDocument = new DOMDocument();
		$bolDomStatus = @$objDomDocument->loadHTML($strData);
		
		if ($bolDomStatus == false) {
			throw new Exception('Invalid Dom Document');
		}
		
		$objDomFormElements = $objDomDocument->getElementsByTagName('form');
		
		foreach ($objDomFormElements as $objFormDomElement) {
			$arrUrlDetails = parse_url($httpGet->getLastUrl());
			$this->strLoginAction = $arrUrlDetails['scheme'].'://'.$arrUrlDetails['host'].$objFormDomElement->getAttribute('action');
			
			$objDomInputElements = $objFormDomElement->getElementsByTagName('input');
			
			foreach ($objDomInputElements as $objDomInputElement) {
				if ($objDomInputElement->getAttribute('name') == 'wosid') {
					$this->strWosid = $objDomInputElement->getAttribute('value');
				}
				
				if ($objDomInputElement->getAttribute('name') == 'theAuxValue') {
					$this->strTheAuxValue = $objDomInputElement->getAttribute('value');
				}
			}
		}
	}
	
}