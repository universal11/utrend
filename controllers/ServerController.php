<?php

class ServerController extends UniversalController{

	public function __construct(){

	}

	public function create($request, $databases){
		include("models/Server.php");

		if(isset($request->name) && isset($request->rootUser) && isset($request->rootPassword) && isset($request->sshHost) && isset($request->sshPort)){
			Server::create($request->name, $request->rootUser, $request->rootPassword, $request->sshHost, $request->sshPort);
		}
	}

	public function getList($request){
		include("models/Server.php");

		$servers = array();

		print_r($servers);
	}

	public function getNetworkAddresses($request, $databases){
		include("models/Server.php");
		include("classes/SSHToolKit.php");
		include("classes/FTPToolKit.php");

		if(isset($request->id)){
			print_r($databases);
			$server = Server::getById($request->id, $databases->production);
			print_r($server);
			print_r($server->getNetworkAddresses());
		}
	}

	//ifconfig | grep 'inet addr:'

}

?>