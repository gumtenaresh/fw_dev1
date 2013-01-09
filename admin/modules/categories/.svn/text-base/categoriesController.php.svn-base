<?php

    /**
    * Description of categories  *
    * @author gangaraju
    */
class categoriesController {

    public function indexAction() {
        // authentication
        if (empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE and $_SESSION['admintype'] != SUBADMIN_TYPE)) {
            header("Location:" . ADMIN_URL);
            exit;
        }
    }

    /**
     *displaying categories list
     * @global type $fc 
     */

    public function categoriesListAction() {
        global $fc;
        $data = array();
        // authontication
        if (empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE and $_SESSION['admintype'] != SUBADMIN_TYPE)) {
            header("Location:" . ADMIN_URL);
            exit();
        }
        $Obj = new Categories();
        $cat = $Obj->getCategoriesList();
        //$cat = $Obj->getCategories();
        $data['list'] = $cat;
        $data['ADMIN'] = ADMIN;
        $view = new View();
        $view->loadView($data, ADMIN . "categories/categoriesList");
    }

    /**
    *add category
    * @global type $fc 
    */

    public function addCategoryAction() {
        global $fc;
        $data = array();
        // authontication
        if (empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE and $_SESSION['admintype'] != SUBADMIN_TYPE)) {
            header("Location:" . ADMIN_URL);
            exit();
        }

        $data['msg'] = "";
        $Obj = new Categories();
        if (isset($_REQUEST['submit']) && $_REQUEST['submit'] == 'Submit') {            
            $Obj->addCategory($_REQUEST);
            header("Location:".ADMIN_URL."categories/categoriesList");
        }        
        $cat = $Obj->getCategories();            
        $data['list'] = $cat;
        $data['ADMIN'] = ADMIN;
        $view = new View();
        $view->loadView($data, ADMIN . "categories/addCategory");
    }
    
    /**
     *edit category
     * @global type $fc 
     */
     public function editCategoryAction() {
        global $fc;
        $data = array();
        // authontication
        if (empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE and $_SESSION['admintype'] != SUBADMIN_TYPE)) {
            header("Location:" . ADMIN_URL);
            exit();
        }
        $id = $_REQUEST['catid'];
        $data['msg'] = "";
        $Obj = new Categories();
        if (isset($_REQUEST['submit']) && $_REQUEST['submit'] == 'Submit') {            
            $Obj->editCategory($_REQUEST);
            header("Location:".ADMIN_URL."categories/categoriesList");
        }
        $cat = $Obj->getCategory($id);
        $data['list'] = $cat;
        $data['ADMIN'] = ADMIN;
        $view = new View();
        $view->loadView($data, ADMIN . "categories/editCategory");
    }
    
    /**
     *delete category
     * @global type $fc 
     */    
     public function deleteCategoryAction() {
        global $fc;        
        // authontication
        if (empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE and $_SESSION['admintype'] != SUBADMIN_TYPE)) {
            header("Location:" . ADMIN_URL);
            exit();
        }
        
        $id = $_REQUEST['catid'];
        $Obj = new Categories();
        $Obj->deleteCategory($id);
        header("Location:".ADMIN_URL."categories/categoriesList");
        
    }

}
?>