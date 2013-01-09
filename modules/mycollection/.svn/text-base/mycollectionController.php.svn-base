<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of mycollectionController
 *
 * @author naresh
 */
class mycollectionController {

    /**
     * index action
     *
     * Profile screen
     * This will be shown after user logged in
     * @global  $fc
     */
    public function indexAction() {
        global $fc; //global variable 
        $data = array(); //declare the array
        $object = new Users(); //create the object to the Users class
        $errorObject = new Errors(Error_XML); //create object to the Errors class
        $objectCollection = new Collections(); //create the object to the collection class
        $objectFanwires = new Fanwires(); //create the object to the fanwires modal class
        $data['active'] = 'collection'; //assigning the active variable for the view
        $data['media'] = 'sports'; //assigning the media variable for the view
        $data['notifications'] = $object->getNotifications($_SESSION['id']); //getting the information about the notification
        $data['zoomValues'] = $objectFanwires->saveZoomValues('myFanwire', $_SESSION['id'], '', '', ''); //get the respective zoom values 
        if (isset($_SESSION['name'])) {//check session weather session is available or not 
            $data['active'] = 'collection'; //the active variable for the selecting the vaigation feild in the view part
            $userDetails = $object->getUserDetails($_SESSION['id']); //getting the user details 
            if ($userDetails[0]['profile_image'] != "")
                $data['profile_image'] = SITE_URL . "profile_images/" . $userDetails[0]['profile_image'];
            else
                $data['profile_image'] = SITE_URL . "views/images/logodef.png";
            $data['fanwires_for_more'] = $objectFanwires->getMasterDetailsMore(); //this for the leftbar fanwires names 
            $data['list'] = $objectCollection->getCollectedFanwires($_SESSION['id']); //get the colected items 
            $data['collection'] = $data['list'];
        } else {
            header("Location:" . SITE_URL);
        }
        $data['pagetitle'] = 'FanWire | My Collection';
        $data['metadescription'] ="";
        $objectFanwires->getHistory(); //getting the history for the history widget
        $data['history'] = $_SESSION['history'];
        $view = new View(); //creating the view class object 
        $view->loadView($data); //loading the view object
    }

    public function fanwirelikesAction() {
        $fanwireid = $_REQUEST['fanwireid'];
        $like = $_REQUEST['like'];
        $dislike = $_REQUEST['dislike'];
        $obj = new Users();
        echo $obj->fanwireLikes($fanwireid, $_SESSION['id'], $like, $dislike);
    }

}

?>
