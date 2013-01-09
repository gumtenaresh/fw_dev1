<?php

/**
 * This class will handle how to load views
 * @author shiva
 *
 */
class View {

    private $viewPath;

    public function __construct() {
        $this->viewPath = DOC_ROOT_PATH . '/views/';
    }

    /**
     * Loads the view with $data, $viewName
     * @param array $data
     * @param string $viewName Optional
     */
    public function loadView($data, $viewName = null) {

        if (empty($viewName)) {
            global $fc;
            $viewName = $fc->controller . '/' . $fc->action;
        }
        $this->loadSmartyView($viewName, $data);
    }

    /**
     * Creates a smarty object and loads a smarty template
     * @param string $templateName
     * @param array $data, this should be an associative array
     * @return bool
     */
    private function loadSmartyView($templateName, $data) {
        //Initialize smarty
        require_once(DOC_ROOT_PATH . '/libs/Smarty/Smarty.class.php');
        $smarty = new Smarty();
        $smarty->template_dir = $this->viewPath;
        $smarty->compile_dir = DOC_ROOT_PATH . '/data/smarty/templates_c';
        $smarty->cache_dir = DOC_ROOT_PATH . '/data/smarty/cache';
        $smarty->config_dir = DOC_ROOT_PATH . '/data/smarty/configs';
        //Initialization of smarty completed

        foreach ($data as $name => $value) {
            $smarty->assign($name, $value);
        }
        unset($data);

        $smarty->display($templateName . '.tpl.html');
        unset($smarty);

        return true;
    }

}