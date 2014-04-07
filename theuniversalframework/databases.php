<?php

//////////////////////////////////////////////
//				DATABASES
//////////////////////////////////////////////

/*
*
*	$database = new UniversalDB($host, $username, $password);
*
*/

$databases = (object) array(
	'production' => UniversalDB::alloc()::init("localhost", "root", "root"),
	'localDev' => UniversalDB::alloc()::init("localhost", "root", "root")
);

?>