<?php

class HTTPPost {
	
	private $strLastUrl;
	private $strData;
	
	public function post($strPostUrl, $arrData) {
		
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, $strPostUrl);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $arrData);
		
		$this->strData = curl_exec($ch);		
		$this->strLastUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
				
		curl_close($ch);
		
		return $this->strData;
	}
	
	public function getLastUrl() {
		return $this->strLastUrl;
	}
	
	public function getData() {
		return $this->strData;
	}

}