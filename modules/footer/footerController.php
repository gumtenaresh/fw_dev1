
<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of mediaManagerController
 *
 * @author Rakesh
 * */
class footerController {

    /**
     * @description:the default action index action
     *
     * */
    public function aboutAction() {
        global $fc;
        $data = array();
        $data['name'] = "";
        if (isset($_SESSION['name'])) {
            $data['name'] = $_SESSION['name'];
        }
        $data['activefooter'] = 'about';
        $errorObject = new Errors(Error_XML); //creating Error class object
        $objectFanwires = new Fanwires();
        $object = new Users(); //Creating Users class object
        $data['fanwires_for_moreguest'] = $object->getMyFanwiresGuest();
        $data['fanwires_for_more'] = $objectFanwires->getMasterDetailsMore();
        $about = $objectFanwires->getAbout();
        $data['about'] = $about;
        $data['pagetitle'] = 'FanWire | Aboutus';
        $data['metadescription'] = "";
        $view = new View(); //creating the object to the view class
        $view->loadView($data, 'footer/aboutus'); //loda the correcsponding view
    }

    public function termsAction() {
        global $fc;
        $data = array();
        $data['name'] = "";
        if (isset($_SESSION['name'])) {
            $data['name'] = $_SESSION['name'];
        }
        $data['activefooter'] = 'terms';
        $errorObject = new Errors(Error_XML); //creating Error class object
        $objectFanwires = new Fanwires();
        $object = new Users(); //Creating Users class object
        $data['fanwires_for_moreguest'] = $object->getMyFanwiresGuest();
        $data['fanwires_for_more'] = $objectFanwires->getMasterDetailsMore();
        $terms = $objectFanwires->getTerms();
        $data['terms'] = $terms;
        $data['pagetitle'] = 'FanWire | Terms and Conditions';
        $data['metadescription'] = "";
        $view = new View(); //creating the object to the view class
        $view->loadView($data, 'footer/termsandconditions'); //loda the correcsponding view
    }

    public function privacyAction() {
         
        global $fc;
        $data = array();
        $data['name'] = "";
        $data['activefooter'] = 'privacy';
        $errorObject = new Errors(Error_XML); //creating Error class object
        $objectFanwires = new Fanwires();
        $object = new Users(); //Creating Users class object
        $privacy = $objectFanwires->getPrivacy();
        $data['privacy'] = $privacy;
        
        $data['pagetitle'] = 'FanWire | Privacy Policy';
        $data['metadescription'] = "";
        $view = new View(); //creating the object to the view class
        $view->loadView($data, 'footer/privacypolicy'); //loda the correcsponding view
    }

    public function developersAction() {
        global $fc;
        $data = array();
        $data['activefooter'] = 'dev';
        $errorObject = new Errors(Error_XML); //creating Error class object
        $data['active'] = 'cam'; //assigning the active variable for the view
        $data['media'] = 'photos'; //assigning the media variable for the view
        /* $userDetails = $object->getUserDetails($_SESSION['id']);
          $data['notifications'] = $object->getNotifications($_SESSION['id']);
          $userDetails = $object->getUserDetails($_SESSION['id']);
          $data['notifications'] = $object->getNotifications($_SESSION['id']);
          if ($userDetails[0]['profile_image'] != "")
          $data['profile_image'] = SITE_URL . "profile_images/" . $userDetails[0]['profile_image'];
          else
          $data['profile_image'] = "";
          if(isset($_SESSION['name'])){

          }else{
          header("Location:".SITE_URL);//redirect to the site url
          } */
        $view = new View(); //creating the object to the view class
        $view->loadView($data, 'footer/developers'); //loda the correcsponding view
    }

    public function advertisingAction() {
        global $fc;
        $data = array();
        $data['activefooter'] = 'advert';
        $errorObject = new Errors(Error_XML); //creating Error class object
        $data['active'] = 'cam'; //assigning the active variable for the view
        $data['media'] = 'photos'; //assigning the media variable for the view
        /* $userDetails = $object->getUserDetails($_SESSION['id']);
          $data['notifications'] = $object->getNotifications($_SESSION['id']);
          $userDetails = $object->getUserDetails($_SESSION['id']);
          $data['notifications'] = $object->getNotifications($_SESSION['id']);
          if ($userDetails[0]['profile_image'] != "")
          $data['profile_image'] = SITE_URL . "profile_images/" . $userDetails[0]['profile_image'];
          else
          $data['profile_image'] = "";
          if(isset($_SESSION['name'])){

          }else{
          header("Location:".SITE_URL);//redirect to the site url
          } */
        $view = new View(); //creating the object to the view class
        $view->loadView($data, 'footer/advertising'); //loda the correcsponding view
    }

    public function helpAction() {
        global $fc;
        $data = array();
        $data['activefooter'] = 'help';
        $errorObject = new Errors(Error_XML); //creating Error class object
        $data['active'] = 'cam'; //assigning the active variable for the view
        $data['media'] = 'photos'; //assigning the media variable for the view
        /* $userDetails = $object->getUserDetails($_SESSION['id']);
          $data['notifications'] = $object->getNotifications($_SESSION['id']);
          $userDetails = $object->getUserDetails($_SESSION['id']);
          $data['notifications'] = $object->getNotifications($_SESSION['id']);
          if ($userDetails[0]['profile_image'] != "")
          $data['profile_image'] = SITE_URL . "profile_images/" . $userDetails[0]['profile_image'];
          else
          $data['profile_image'] = "";
          if(isset($_SESSION['name'])){

          }else{
          header("Location:".SITE_URL);//redirect to the site url
          } */
        $view = new View(); //creating the object to the view class
        $view->loadView($data, 'footer/help'); //loda the correcsponding view
    }

    public function contactAction() {
        global $fc;
        $data = array();
        $data['activefooter'] = 'connect';
        
        $data['name'] = "";
        if (isset($_SESSION['name'])) {
            $data['name'] = $_SESSION['name'];
        }
        $objectFanwires = new Fanwires();
        $object = new Users(); //Creating Users class object
        $data['fanwires_for_moreguest'] = $object->getMyFanwiresGuest();
        $data['fanwires_for_more'] = $objectFanwires->getMasterDetailsMore();
        $errorObject = new Errors(Error_XML); //creating Error class object
        $data['userdetails'] = $userDetails = $object->getUserDetails($_SESSION['id']);
        //   echo "<pre>";print_r($_REQUEST); exit;

        if (isset($_REQUEST['submit'])) {
            extract($_REQUEST);
            Mailer::sendMail(SUPPORT_INFO_EMAIL, $subject, $message, $email, '', '', '', '');
            $data['msg'] = "Your message was sent . Thank you.";
        }
        $data['pagetitle'] = 'FanWire | Contact Us';
        $data['metadescription'] = "";
        $view = new View(); //creating the object to the view class
        $view->loadView($data, 'footer/contact'); //loda the correcsponding view
    }

}

?>
