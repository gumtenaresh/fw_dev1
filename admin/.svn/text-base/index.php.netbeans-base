<?php

include('../config.inc.php');
define('API_ROOT_PATH', dirname(__FILE__));
//Don't rename this $fc, it will contain controller, action and extra params
try{
	$fc = new ApiRouter($path);
} catch (Exception $e) {
	var_dump($e);
	
}
//var_dump($fc);
//Call respective action for the controller
include($fc->controllerPath . '/' . $fc->controller . 'Controller.php');
//echo $fc->controller . 'Controller', $fc->action . 'Action';
if(method_exists($fc->controller . 'Controller', $fc->action . 'Action')){
    call_user_func($fc->controller . 'Controller::' . $fc->action . 'Action');    
}else{
    call_user_func($fc->controller . 'Controller::indexAction');
}

