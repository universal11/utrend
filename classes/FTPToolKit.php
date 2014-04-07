<?php

class FTPToolKit{

	public static function upload($user, $password, $host, $port, $sourceFile, $destinationFile){
		// set up basic connection
		$connectionId = ftp_connect("{$host}:{$port}");

		// login with username and password
		$login_result = ftp_login($connectionId, $user, $password);

		// upload a file
		if (ftp_put($connectionId, $destinationFile, $sourceFile, FTP_ASCII)) {
		 echo "successfully uploaded $file\r\n";
		} else {
		 echo "There was a problem while uploading {$file}\r\n";
		}

		// close the connection
		ftp_close($connectionId);
	}
}

?>