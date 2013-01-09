<?php
/**
 * Description of fanwires  *
 * @author gangaraju
 */
class footerController
{
    public function indexAction()
    {
        // authentication
        if(empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE and $_SESSION['admintype'] != SUBADMIN_TYPE))
        {
            header("Location:".ADMIN_URL);
            exit;
        }
    }

    /**
    *fanwires list
    * @global type $fc 
    */
    public function aboutAction()
    { 
        global $fc;
        $data = array();
        // authentication
        if(empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE and $_SESSION['admintype'] != SUBADMIN_TYPE))
        {
            header("Location:".ADMIN_URL);
            exit;
        }
        
        $qObj = new Fanwires();    
        
        if(isset($_REQUEST['submit']))
        {
            extract($_REQUEST);
            $qObj->updateAbout($about);               
        }        
        $about = $qObj->getAbout();
        $data['about'] = $about;        
        $data['ADMIN'] = ADMIN;

        $view = new View();
        $view->loadView($data,ADMIN."footer/about");
    } 
    
    public function termsAction()
    { 
        global $fc;
        $data = array();
        // authentication
        if(empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE and $_SESSION['admintype'] != SUBADMIN_TYPE))
        {
            header("Location:".ADMIN_URL);
            exit;
        }
        
        $qObj = new Fanwires();    
        
        if(isset($_REQUEST['submit']))
        {            
            extract($_REQUEST);
            $qObj->updateTerms($terms);               
        }        
        $terms = $qObj->getTerms();
        $data['terms'] = $terms;
        $data['ADMIN'] = ADMIN;

        $view = new View();
        $view->loadView($data,ADMIN."footer/terms");
    } 
    
    
     public function privacyAction()
    { 
        global $fc;
        $data = array();
        // authentication
        if(empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE and $_SESSION['admintype'] != SUBADMIN_TYPE))
        {
            header("Location:".ADMIN_URL);
            exit;
        }
        
        $qObj = new Fanwires();    
        
        if(isset($_REQUEST['submit']))
        {
             extract($_REQUEST);
            $qObj->updatePrivacy($privacy);               
        }        
        $privacy = $qObj->getPrivacy();
        $data['privacy'] = $privacy;
        $data['ADMIN'] = ADMIN;

        $view = new View();
        $view->loadView($data,ADMIN."footer/privacy");
    } 
    
}//end class
?>