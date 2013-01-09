<?php

//Load config
include('config.inc.php');
//echo DOC_ROOT_PATH;
//Don't rename this $fc, it will contain controller, action and extra params
try {
    $fc = new Router(strtolower($path));
} catch (Exception $e) {
    //var_dump($e);
}
//creating object to facebook class
$facebook = new Facebook(array(
            'appId' => FB_APP_ID,
            'secret' => FB_APP_SECRET,
        ));
//creating object to twitter
$twitterObj = new EpiTwitter(YOUR_CONSUMER_KEY, YOUR_CONSUMER_SECRET);
$twitterObj1 = new EpiTwitter('m8F8pTvYHrzTloIJzC7VJw', '8D00noEBXflxu2krnw5ypZrwkO119Kq4BL70CtLbI');
$globalArray = array();

//Call respective action for the controller
include($fc->controllerPath . '/' . $fc->controller . 'Controller.php');

if (method_exists($fc->controller . 'Controller', $fc->action . 'Action')) {
    call_user_func(array($fc->controller . 'Controller', $fc->action . 'Action'));
} else {
    call_user_func(array($fc->controller . 'Controller', 'indexAction'));
}


