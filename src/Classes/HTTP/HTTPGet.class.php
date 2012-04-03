<?php

class HTTPGet {
	
	private $strLastUrl;
	private $strData;
	
	public function get($strGetUrl) {
		
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, $strGetUrl);
		curl_setopt($ch, CURLOPT_POST, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);		
		
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