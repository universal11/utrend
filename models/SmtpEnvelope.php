<?php

class SmtpEnvelope extends UniversalModel{
	private $message;

	public function init($message){
		$this->message = $message;
	}

	public function getMessage(){
		return $this->message;
	}
}

?>