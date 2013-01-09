<?php

class Router {

    protected $url_delimiter = '/';
    public $controller = null;
    public $action = null;
    public $extra = null;
    public $ext = null;
    public $path = null;

    public function __construct($path_info) {

        /**
         *    This file contains list of short routes and renamed controller names
         *    Contains two arrays $controllers and $routes
         */
        require_once( DOC_ROOT_PATH . '/app/routes.php');

        //Handle website home
        $path_info = (empty($path_info) || ($path_info == '/')) ? '/index/index' : $path_info;

        @list($null, $controller, $action, $extra) = explode($this->url_delimiter, $path_info, 4);

        if (!empty($extra)) {
            $extra = explode($this->url_delimiter, $extra);
        } else {
            $extra = array();
        }
//echo $action;
        //used to map whole controller/action to single simple name
        //Ex: http://example.com/settings    will map to "user" controller and "settings" action
        if (empty($action) && !empty($routes[$controller])) {
            list($controller, $action) = explode($this->url_delimiter, $routes[$controller]);
        } else if (!empty($action) && (strtolower($action) == 'articles' || strtolower($action) == 'fb' || strtolower($action) == 'gallery' || strtolower($action) == 'videos') || (strtolower($action) == 'socialmedia') || (strtolower($action) == 'instagram')) {
          
            if (!empty($controller) && !empty($action) && !empty($extra[0])) {
                list($controller, $action) = explode($this->url_delimiter, $routes[$controller . "/" . $action . "/" . $extra[0]]);
            } else {
                list($controller, $action) = explode($this->url_delimiter, $routes[$controller . "/" . $action]);
            }
        }
        $arr = explode("?", $action);
        $action = $arr[0];
        $extra = $arr[1];

        //Check for extenstion in action
        $ext = null;
        if (strpos($action, '.')) {
            list($action, $ext) = explode('.', $action);
        }

        //used to rename controller with user friendly names.
        if (!empty($controllers[$controller])) {
            $controller = $controllers[$controller];
        }

        //used to rename action with user friendly names.
        if (!empty($actions[$controller][$action])) {
            $action = $actions[$controller][$action];
        }
        $path = null;
        if (file_exists(DOC_ROOT_PATH . '/modules/' . $controller . '/' . $controller . 'Controller.php')) {
            $path = DOC_ROOT_PATH . '/modules/' . $controller;
        } else {
            //404 Not found
            //@todo Throw and Exception to be handled by application
            if ($controller == 'views') {
                exit;
            }
            $view = new View();
            $view->loadView($data, "pagenotfound");
            exit();
            //echo "404 Not found from Router";            
        }

        $this->controller = $controller;
        $this->action = $action;
        $this->extra = $extra;
        $this->ext = $ext;
        $this->controllerPath = $path;
    }

}