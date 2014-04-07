<?php
define("FRAMEWORK_DIR", dirname(__FILE__));
include(FRAMEWORK_DIR . "/include/UniversalDB.php");
include(FRAMEWORK_DIR . "/include/UniversalModel.php");
include(FRAMEWORK_DIR . "/include/UniversalController.php");
include(FRAMEWORK_DIR . "databases.php");

$request = (object)$_REQUEST;

//Set &debug=1 on URL for debug mode

$debugMode = ( (empty($request->debug)) ? false : $request->debug );
if($debugMode){
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
}

//
// Route Handler
//

if(!empty($request->controller)){
	$controller = $request->controller;
	$boot = "controllers/{$controller}.php";
	if(file_exists($boot)){
		if($debugMode){ echo "Booting Controller: {$boot}<br>\r\n"; }
		include($boot);
		if(class_exists($controller)){
			if($debugMode){ echo "{$controller} loaded...<br>\r\n"; }
			$controller = new $controller();
			if(!empty($request->action)){
				$action = $request->action;
				if(method_exists($controller, $action)){
					if($debugMode){ echo "{$action} executed...<br>\r\n"; }
					$controller->{$action}($request, $databases);
				}
			}
			else{
				$controller->main($request, $databases);
			}
		}
	}
	
}


?>