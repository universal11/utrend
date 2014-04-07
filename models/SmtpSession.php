<?php

class SmtpSession extends UniversalModel{
	private $id;
	private $envelopes = array();
	private $host;
	private $port = 25;

	public function __construct(){

	}

	public function init($id, $host, $port, $envelopes){
		$this->id = $id;
		$this->host = $host;
		$this->port = $port;
		$this->envelopes = $envelopes;
	}

	public function getId(){
		return $this->id;
	}

	public function getEnvelopes(){
		return $this->envelopes;
	}

	/*
	public function addToQueue(){
		$sessionDirectory = QUEUE_DIRECTORY . "/{$this->id}";
		if(!file_exists($sessionDirectory){
			mkdir($sessionDirectory, 0777, true);
		}

		foreach($this->envelopes as $envelope){
			file_put_contents("{$sessionDirectory}/{$envelope->getId()}.txt", 0777, $envelope->getContent());
		}

		//upload file
		FTPToolKit::upload();
		
		
	}
	*/
}

?>