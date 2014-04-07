<?php

class NetworkAddress extends UniversalModel{
	private $address;

	public function __construct(){

	}

	public function getAddress(){
		return $this->address;
	}

	public function init($address){
		$this->address = $address;
	}

	public function isIPv4(){

	}

	public function isIPv6(){

	}
}

?>