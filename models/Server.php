<?php

class Server extends UniversalModel{
	private $id;
	private $name;
	private $rootUser;
	private $rootPassword;
	private $sshHost;
	private $sshPort = 22;
	const VMTA_INSTALL_DIR = "/home/apps/umail";

	public function __construct(){

	}

	public function init($id, $name, $rootUser, $rootPassword, $sshHost, $sshPort){
		$this->id = $id;
		$this->name = $name;
		$this->rootUser = $rootUser;
		$this->rootPassword = $rootPassword;
		$this->sshHost = $sshHost;
		$this->sshPort = $sshPort;
	}

	public function initWithResultSet($resultSet){
		if($resultSet){
			$this->init($resultSet->server_id, $resultSet->name, $resultSet->root_user, $resultSet->root_password, $resutSet->ssh_host, $resultSet->ssh_port);
		}
	}

	public function getNetworkAddresses(){
		$output = SSHToolKit::execute($this->rootUser, $this->rootPassword, $this->sshHost, $this->sshPort, "ifconfig | grep 'inet addr:'");
		$networkInterfaces = explode("\n", $output);
		$networkInterfaces = array_filter($networkInterfaces);
		$networkAddresses = array();


		foreach($networkInterfaces as $index=>$networkInterface){

			$networkInterfaceDetails = explode("  ", $networkInterface);
			$networkInterfaceDetails = array_filter($networkInterfaceDetails);

			foreach($networkInterfaceDetails as $networkInterfaceDetail){
				$networkInterfaceDetailMap = explode(":", $networkInterfaceDetail);

				switch(trim($networkInterfaceDetailMap[0])){
					case "inet addr":
						$networkAddress = new NetworkAddress();
						$networkAddress->init(trim($networkInterfaceDetailMap[1]));
						$networkAddresses[] = $networkAddress;
						break;
					default:
						break;
				}
			}

		}

		return $networkAddresses;
	}


	public function uploadFile($localFilePath, $remoteFilePath){
		SSHToolKit::scp($this->rootUser, $this->rootPassword, $this->sshHost, $this->sshPort, $localFilePath, $remoteFilePath)
	}

	public function addSmtpSession($sessionId){
		$this->uploadFile(SmtpQueue::QUEUE_DIRECTORY . "/{$sessionId}", VMTA_INSTALL_DIR . "/sessions/{$sessionId}");
	}

	public function getSmtpSessions(){
		$command = "something that gets the sessions in the directory";
		$output = SSHToolKit::execute($this->rootUser, $this->rootPassword, $this->sshHost, $this->sshPort, "{$command}");
	}

	public function sendSmtpSessions($host, $port = 25, $clientAddress){
		//uMailProg -s 456789 -h mx1.hotmail.com -p 25 -b 77.235.50.197

		$command = "";
		foreach($smtpSessions as $smtpSessions){
			$command .= "umail -s {$smtpSession->getId()} -h {$host} -p {$port} -b {$clientAddress};";
		}

		$output = SSHToolKit::execute($this->rootUser, $this->rootPassword, $this->sshHost, $this->sshPort, "{$command}");
	}

	public static function create($name, $rootUser, $rootPassword, $sshHost, $sshPort, $dbConnection){
		return $dbConnection->execute("INSERT INTO utrend.servers('name', 'root_user', 'root_password', 'ssh_host', 'ssh_port') VALUES('{$name}', '{$rootUser}', '{$rootPassword}', '{$sshHost}', '{$sshPort}')");
	}

	public static function getById($id, $database){
		$server = new Server();
		$resultSet = $database->queryUniqueResult("SELECT * FROM utrend.servers WHERE server_id = {$id} LIMIT 1");
		return $server->initWithResultSet($resultSet);
	}

	public function installVMTA(){
		$command = "rm -rfd " . VMTA_INSTALL_DIR . ";"; #removes previous installation
		$command .= "mkdir " . VMTA_INSTALL_DIR . ";"; #creates install directory
		$command .= "cd " . VMTA_INSTALL_DIR . ";"; #move to install directory
		$command .= "git clone https://github.com/universal11/uMail.git;"; #retrieves source from repo
		$command .= "cd uMail;"; #move to source directory
		$command .= "g++ -Wall -l boost_system -l boost_filesystem *.cpp -o ../umail"; #compile program
		$command .= "cd ..;"; #move back to install directory
		$command .= "rm -rfd uMail;"; #move back to install directory

		$output = SSHToolKit::execute($this->rootUser, $this->rootPassword, $this->sshHost, $this->sshPort, "{$command}");
	}

}

?>