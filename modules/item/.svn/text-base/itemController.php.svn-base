<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of itemController
 *
 * @author naresh
 */
class itemController {

    /**
     * Profile screen
     * This will be shown after user logged in

     * @global  $fc
     */
    public function indexAction() {
        $object = new Users();
        $errorObject = new Errors(Error_XML);
        $data = array();
        if (isset($_SESSION['name'])) {
            $data['active'] = 'article';
            $userDetails = $object->getUserDetails($_SESSION['id']);
            //print_r($userDetails);
            $data['profile_image'] = SITE_URL . "profile_images/" . $userDetails[0]['profile_image'];
            // $data['fanwires'] = $object->getAllFanwires();
            $data['fanwires'] = $object->getMyFanwires();
            $data['fanwires_for_more'] = $object->getMyFanwiresMore();
            $data['notifications'] = $object->getNotifications($_SESSION['id']);
        } else {
            header("Location:" . SITE_URL);
        }
        $obj = new Fanwires();
        $obj->getHistory();
        $data['history'] = $_SESSION['history'];
        $view = new View();
        $view->loadView($data, 'item/my_fanwire_add_item');
    }

    public function testAction() {
        echo "This is a test";
        $data['test'] = "This is a test";
        $view = new View();
        $view->loadView($data);
    }

}

?>
