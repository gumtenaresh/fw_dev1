
<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of werecommendController
 *
 * @author Rakesh
 * */
class werecommendController {

    /**
     * @description:the default action index action
     *
     * */
    public function recommendAction() {
        global $fc;
        $data = array();
        $object = new Users(); //Creating Users class object
        $errorObject = new Errors(Error_XML); //creating Error class object
        $data['active'] = 'recommend'; //assigning the active variable for the view
        $data['media'] = 'vedios'; //assigning the media variable for the view

        $userDetails = $object->getUserDetails($_SESSION['id']);
        $data['notifications'] = $object->getNotifications($_SESSION['id']);

        if ($userDetails[0]['profile_image'] != "")
            $data['profile_image'] = SITE_URL . "profile_images/" . $userDetails[0]['profile_image'];
        else
            $data['profile_image'] = "";

        $data['fanwires'] = $object->getMyFanwires();
        $data['fanwires_for_more'] = $object->getMyFanwiresMore();
        if (isset($_SESSION['name'])) {
            
        } else {
            header("Location:" . SITE_URL); //redirect to the site url
        }

        $catObject = new werecommend();
        $data['list'] = $catObject->getCelebrity($_SESSION['id']);
        // echo "<pre>";print_r($data['catdisp']);
        $obj = new Fanwires(); //create object for the Fanwire class
        $obj->getHistory(); //get the history which is recorded
        $data['history'] = $_SESSION['history'];

        $view = new View(); //creating the object to the view class
        $view->loadView($data, 'werecommend/weRecommend'); //load the correcsponding view
    }

    public function fashionAction() {
        global $fc;
        $data = array();
        $object = new Users(); //Creating Users class object
        $errorObject = new Errors(Error_XML); //creating Error class object
        $data['active'] = 'recommend'; //assigning the active variable for the view
        $data['media'] = 'vedios'; //assigning the media variable for the view
        $userDetails = $object->getUserDetails($_SESSION['id']);
        $data['notifications'] = $object->getNotifications($_SESSION['id']);
        if ($userDetails[0]['profile_image'] != "")
            $data['profile_image'] = SITE_URL . "profile_images/" . $userDetails[0]['profile_image'];
        else
            $data['profile_image'] = "";
        //$data['fanwires'] = $object->getMyFanwires();
        $data['fanwires_for_more'] = $object->getMyFanwiresMore();
        if (isset($_SESSION['name'])) {
            
        } else {
            header("Location:" . SITE_URL); //redirect to the site url
        }
        $catObject = new werecommend();
        $data['list'] = $catObject->getFashion($_SESSION['id']);
        $obj = new Fanwires(); //Creating the object for the class Fanwires
        $obj->getHistory();
        $data['history'] = $_SESSION['history'];
        $view = new View(); //creating the object to the view class
        $view->loadView($data, 'werecommend/fashion'); //load the correcsponding view
    }

    public function musicAction() {
        global $fc;
        $data = array();
        $object = new Users(); //Creating Users class object
        $errorObject = new Errors(Error_XML); //creating Error class object
        $data['active'] = 'recommend'; //assigning the active variable for the view
        $data['media'] = 'vedios'; //assigning the media variable for the view
        $userDetails = $object->getUserDetails($_SESSION['id']);
        $data['notifications'] = $object->getNotifications($_SESSION['id']);
        if ($userDetails[0]['profile_image'] != "")
            $data['profile_image'] = SITE_URL . "profile_images/" . $userDetails[0]['profile_image'];
        else
            $data['profile_image'] = "";
        //$data['fanwires'] = $object->getMyFanwires();
        $data['fanwires_for_more'] = $object->getMyFanwiresMore();
        if (isset($_SESSION['name'])) {
            
        } else {
            header("Location:" . SITE_URL); //redirect to the site url
        }
        $catObject = new werecommend();
        $data['list'] = $catObject->getMusic($_SESSION['id']);
        $obj = new Fanwires(); //Creating the object for the class Fanwires
        $obj->getHistory();
        $data['history'] = $_SESSION['history'];
        $view = new View(); //creating the object to the view class
        $view->loadView($data, 'werecommend/music'); //load the correcsponding view
    }

    public function newsAction() {
        global $fc;
        $data = array();
        $object = new Users(); //Creating Users class object
        $errorObject = new Errors(Error_XML); //creating Error class object
        $data['active'] = 'recommend'; //assigning the active variable for the view
        $data['media'] = 'vedios'; //assigning the media variable for the view
        $userDetails = $object->getUserDetails($_SESSION['id']);
        $data['notifications'] = $object->getNotifications($_SESSION['id']);
        if ($userDetails[0]['profile_image'] != "")
            $data['profile_image'] = SITE_URL . "profile_images/" . $userDetails[0]['profile_image'];
        else
            $data['profile_image'] = "";
        //$data['fanwires'] = $object->getMyFanwires();
        $data['fanwires_for_more'] = $object->getMyFanwiresMore();
        if (isset($_SESSION['name'])) {
            
        } else {
            header("Location:" . SITE_URL); //redirect to the site url
        }
        $catObject = new werecommend();
        $data['list'] = $catObject->getNews($_SESSION['id']);
        $obj = new Fanwires(); //Creating the object for the class Fanwires
        $obj->getHistory();
        $data['history'] = $_SESSION['history'];
        $view = new View(); //creating the object to the view class
        $view->loadView($data, 'werecommend/news'); //load the correcsponding view
    }

    public function sportsAction() {
        global $fc;
        $data = array();
        $object = new Users(); //Creating Users class object
        $errorObject = new Errors(Error_XML); //creating Error class object
        $data['active'] = 'recommend'; //assigning the active variable for the view
        $data['media'] = 'sports'; //assigning the media variable for the view
        $userDetails = $object->getUserDetails($_SESSION['id']);
        $data['notifications'] = $object->getNotifications($_SESSION['id']);
        if ($userDetails[0]['profile_image'] != "")
            $data['profile_image'] = SITE_URL . "profile_images/" . $userDetails[0]['profile_image'];
        else
            $data['profile_image'] = "";
        //$data['fanwires'] = $object->getMyFanwires();
        $data['fanwires_for_more'] = $object->getMyFanwiresMore();
        if (isset($_SESSION['name'])) {
            
        } else {
            header("Location:" . SITE_URL); //redirect to the site url
        }
        $catObject = new werecommend();
        $data['list'] = $catObject->getSports($_SESSION['id']);
        $obj = new Fanwires(); //Creating the object for the class Fanwires
        $obj->getHistory();
        $data['history'] = $_SESSION['history'];
        $view = new View(); //creating the object to the view class
        $view->loadView($data, 'werecommend/sports'); //load the correcsponding view
    }

    //code by Rak for likes and dislikes and comments
    public function fanwirelikesAction() {
        $fanwireid = $_REQUEST['fanwireid'];
        $like = $_REQUEST['like'];
        $dislike = $_REQUEST['dislike'];
        $obj = new Users();
        echo $obj->fanwireLikes($fanwireid, $_SESSION['id'], $like, $dislike);
    }

}

?>
