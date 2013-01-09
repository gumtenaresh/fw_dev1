<?php

/**
 * Description of users   
 * @author gangaraju
 */
class usersController {

    public function indexAction() {
        // authentication
        if (empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE && $_SESSION['admintype'] != SUBADMIN_TYPE)) {
            header("Location:" . ADMIN_URL);
            exit;
        }
    }

   /**
    *list users
    * @global type $fc 
    */

    public function userslistAction() {
        global $fc;
        $data = array();
        $params = array();
        $data['msg'] = ""; 
        // authentication
        if (empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE && $_SESSION['admintype'] != SUBADMIN_TYPE)) {
            header("Location:" . ADMIN_URL);
            exit;
        }

        $qObj = new Users();
        $list = $qObj->getUsersList();
        $data['list'] = $list['list'];
        $data['ADMIN'] = ADMIN;
        $data['SITE_URL'] = SITE_URL;
        $data['navigation'] = $list['navigation'];
        $view = new View();
        $view->loadView($data, ADMIN . "users/userslist");
    }

     /**
      *delete user 
      */
    public function deleteUserAction() {
        if (empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE && $_SESSION['admintype'] != SUBADMIN_TYPE)) {
            header("Location:" . ADMIN_URL);
            exit;
        }
        $id = $_REQUEST['id'];
        $type = $_REQUEST['type'];        
        $qObj = new Users();
        $list = $qObj->deleteUser($id);
        if($type=='admin')
            header("Location:" . ADMIN_URL . "users/adminlist/");
        else
            header("Location:" . ADMIN_URL . "users/userslist/");
    }

    public function adminListAction() {
        global $fc;
        $data = array();
        $params = array();
        $data['msg'] = "";
        // authentication
        if (empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE && $_SESSION['admintype'] != SUBADMIN_TYPE)) {
            header("Location:" . ADMIN_URL);
            exit;
        }

        $qObj = new Users();
        $list = $qObj->getAdminList();
        $data['list'] = $list['list'];
        $data['ADMIN'] = ADMIN;
        $data['SITE_URL'] = SITE_URL;
        $data['navigation'] = $list['navigation'];
        $view = new View();
        $view->loadView($data, ADMIN . "users/adminlist");
    }

     /**
      *delete user 
      */
    public function deleteAdminAction() {
        if (empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE && $_SESSION['admintype'] != SUBADMIN_TYPE)) {
            header("Location:" . ADMIN_URL);
            exit;
        }
        $id = $_REQUEST['id'];
        $qObj = new Users();
        $list = $qObj->deleteUser($id);
        header("Location:" . ADMIN_URL . "users/adminlist/");
    }
    
    public function createUserAction() {
        if (empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE && $_SESSION['admintype'] != SUBADMIN_TYPE)) {
            header("Location:" . ADMIN_URL);
            exit;
        }
        if(isset($_REQUEST['submit']))
        {           
            $qObj = new Users();
            $qObj->createUser($_REQUEST);
            header("Location:" . ADMIN_URL."users/adminlist/");
        }
        $view = new View();
        $view->loadView($data, ADMIN . "users/createUser");
    }
    
    /**
     *change password
     * @global type $fc 
     */
    public function changePasswordAction() {
        global $fc;
        $data = array();
        $data['error'] = null;

        if (empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE && $_SESSION['admintype'] != SUBADMIN_TYPE)) {
            header("Location:" . ADMIN_URL);
            exit;
        }
        // creating error class object

        $Obj = new Users();
        if (!empty($_POST)) {
            // validating password
            if ($Obj->isAuthorisedUser($_SESSION['adminid'], $_POST['oldpwd'],$_SESSION['admintype']) === false) {
                $data['error'] = "Invalid User";
            } else {
                // updating new pass word
                $bl = $Obj->updatePassword($_SESSION['adminid'], $_POST['newpwd']);
                $data['error'] = "Password Updated";               
            }
        }

        $data['ADMIN'] = ADMIN;
        $view = new View();
        $view->loadView($data, ADMIN . "users/changePassword");
    }

    /**
     *delete profile image 
     */
    public function deleteProfileImageAction() {
        if (empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE && $_SESSION['admintype'] != SUBADMIN_TYPE)) {
            header("Location:" . ADMIN_URL);
            exit;
        }
        $id = $_REQUEST['id'];
        $qObj = new Users();
        $list = $qObj->getUserDetails($id);
        $image = $list[0]['profile_image'];         
        $qObj->updateProfileImage($id);
        @unlink(DOC_ROOT_PATH."/profile_images/".$image); 
        header("Location:" . ADMIN_URL . "users/userslist/");
    }
    
     public function changeUserTypeAction() {
        if (empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE && $_SESSION['admintype'] != SUBADMIN_TYPE)) {
            header("Location:" . ADMIN_URL);
            exit;
        }
        $id = $_REQUEST['id'];
        $type = $_REQUEST['type'];
        $qObj = new Users();
        $qObj->chagneUserType($id,$type);        
    }
    
      public function editUserAction() {
        global $fc;
        $data = array();
        // authentication
        if (empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE && $_SESSION['admintype'] != SUBADMIN_TYPE)) {
            header("Location:" . ADMIN_URL);
            exit;
        }
        $id= $_REQUEST['id'];
        $qObj = new Users();        
        extract($_REQUEST);
        if(isset($_REQUEST['submit']) && $password!="" && $password!=$confpassword)
        {
            $data['msg'] = "Please re enter same password";
        }
        else if(isset($_REQUEST['submit']))
        {
            $qObj->updateEditorProfile($_REQUEST);
            $data['success'] = "Profile updated successfully.";
        }
        $list = $qObj->getUserDetails($id);
        $data['list'] = $list;                
        $view = new View();
        $view->loadView($data, ADMIN . "users/editUser");
    }
}

//end class
?>
