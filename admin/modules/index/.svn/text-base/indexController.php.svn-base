<?php
/**
 *
 * @author gangaraju
 */
class indexController{
/**
 *login function
 * @global type $fc 
 */
    public function indexAction(){
        global $fc;
        $data = array();
        $error = null;
        $errorMsg = null;
        $rs = null;        
        if (!empty($_SESSION['adminid']) && $_SESSION['admintype'] == ADMIN_TYPE) {
            header("Location:" . ADMIN_URL."fanwires/fanwiresList/");
            exit;
        }
        
        // creating error class object
        $errorObj = new Errors(Error_XML);        
         if(isset($_POST['email']) && isset($_POST['pwd']))
         {
            //All fields must be filled
            if(empty($_POST['email']) || empty($_POST['pwd']))
            {
                $error = 1;
                $errorMsg = $errorObj->getErrorMessage("error4");
                $rs = $_POST;
            }
            else
            {
                    $obj =new Users();
                   $rs = $obj->authenticateUser($_POST['email'], $_POST['pwd']);
                        if( $rs === false){
                        $error = 1;
                        $errorMsg = $errorObj->getErrorMessage("error9");
                    }else{                        
                        //Storing Login info in "SESSION"
                        $_SESSION['adminid']   = $rs[0]['id'];
                        $_SESSION['adminname'] = $rs[0]['fname'].' '.$rs[0]['lname'];
                        $_SESSION['adminemail'] = $rs[0]['email'];
                        $_SESSION['admintype'] = $rs[0]['usertype'];
                        
                        // redirection to company list
                        header("Location:".ADMIN_URL."fanwires/fanwiresList/");
                        exit;
                    }
            }


        }

        $data['controllerName'] = $fc->controller;
        $data['actionName'] = $fc->action;
        $data['error'] = $error;
        $data['errorMsg'] =$errorMsg;
        $data['rs'] =$rs;
        $data['ADMIN'] = 'admin';
        $view = new View();
        $view->loadView($data,ADMIN."index/index");
    }   

     /**
      * logout
      */
     public function logoutAction()
     {
        unset($_SESSION['adminid']);
        unset($_SESSION['admintype']);
        header("Location:".ADMIN_URL);

    }
}
?>
