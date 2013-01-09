
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
class fannetworkController {

    /**
     * @description:the default action index action
     *
     * */
    public function fannetworkAction() {
        global $fc;
        $data = array();
        $object = new Users(); //Creating Users class object
        $errorObject = new Errors(Error_XML); //creating Error class object
        $data['active'] = 'fannetwork'; //assigning the active variable for the view
        $data['media'] = 'vedios'; //assigning the media variable for the view


        $userDetails = $object->getUserDetails($_SESSION['id']);
        $data['notifications'] = $object->getNotifications($_SESSION['id']);
        if ($userDetails[0]['profile_image'] != "")
            $data['profile_image'] = SITE_URL . "profile_images/" . $userDetails[0]['profile_image'];
        else
            $data['profile_image'] = "";
        // $data['fanwires'] = $object->getMyFanwires();
        $data['fanwires_for_more'] = $object->getMyFanwiresMore();
        if (isset($_SESSION['name'])) {
            
        } else {
            header("Location:" . SITE_URL); //redirect to the site url
        }
        $fanObject = new fannetwork();
        $data['list'] = $data['fandisp'] = $fanObject->getfans($_SESSION['id']);
        //echo "<pre>";print_r($data['catdisp']);
        $obj = new Fanwires(); //Creating the object for the class Fanwires
        $obj->getHistory();

        $data['history'] = $_SESSION['history'];
        $view = new View(); //creating the object to the view class
        $view->loadView($data, 'fannetwork/fannetwork'); //load the correcsponding view
    }

}

?>