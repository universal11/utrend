<?php

class SSHToolKit{

	public static function scp($user, $password, $host, $port, $sourcePath, $destinationPath){
		$connection = ssh2_connect($host, $port);
		ssh2_auth_password($connection, $user, $password);
		return ssh2_scp_send($connection, $sourcePath, $destinationPath, 0777);
	}

	public static function execute($user, $password, $host, $port, $command){
		$connection = ssh2_connect($host, $port);
		ssh2_auth_password($connection, $user, $password);
		$stream = ssh2_exec($connection, $command);
		stream_set_blocking($stream, true);
		$output = stream_get_contents($stream);
		fclose($stream);
		return $output;
	}
}

?>