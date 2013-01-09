
<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of mediaManagerController
 *
 * @author naresh
 * */
class mediamanagerController {

   
    /**
     * @description:the default action index action
     *
     * */
    public function myPhotosAction() {
        global $fc;
        $data = array();
        $object = new Users(); //Creating Users class object
        $errorObject = new Errors(Error_XML); //creating Error class object
        $data['active'] = 'cam'; //assigning the active variable for the view
        $data['media'] = 'photos'; //assigning the media variable for the view
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
        $data['list'] = $object->getAlbumsList($_SESSION['id']);
    // echo "<pre>";print_r($data['list']);

        $obj = new Fanwires(); //create object for the Fanwire class
        $obj->getHistory(); //get the history which is recorded
        $data['history'] = $_SESSION['history'];
        $view = new View(); //creating the object to the view class
        $view->loadView($data, 'mediamanager/myPhotos'); //loda the correcsponding view
    }

    /**
     * Myvedios action
     * */
    public function myVediosAction() {
        global $fc;
        $data = array();
        $object = new Users(); //Creating Users class object
        $errorObject = new Errors(Error_XML); //creating Error class object
        $objectVideos = new Videos();
        $data['active'] = 'cam'; //assigning the active variable for the view
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
        $data['list'] = $objectVideos->getVideos($_SESSION['id'],'1');
        // echo "<pre>";print_r($data['list']);
        $obj = new Fanwires(); //create object for the Fanwire class
        $obj->getHistory(); //get the history which is recorded
        $data['history'] = $_SESSION['history'];
        $view = new View(); //creating the object to the view class
        $view->loadView($data, 'mediamanager/myVedios'); //loda the correcsponding view
    }

    /**
     * MyArticles action
     * */
    public function myArticlesAction() {
        global $fc;
        $data = array();
        $object = new Users(); //Creating Users class object
        $errorObject = new Errors(Error_XML); //creating Error class object
        $objectArticle = new Articles();
        $data['active'] = 'cam'; //assigning the active variable for the view
        $data['media'] = 'articles'; //assigning the media variable for the view
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
        $data['list'] = $objectArticle->getArticles($_SESSION['id'], '1');
        //   echo "<pre>";print_r($data['list']);
        //echo "<pre>";print_r($data['list']);
        $obj = new Fanwires(); //create object for the Fanwire class
        $obj->getHistory(); //get the history which is recorded
        $data['history'] = $_SESSION['history'];
        $view = new View(); //creating the object to the view class
        $view->loadView($data, 'mediamanager/myArticles'); //loda the correcsponding view
    }

    /**
     * MyLinks action
     * */
    public function myLinksAction() {
        global $fc;
        $data = array();
        $object = new Users(); //Creating Users class object
        $errorObject = new Errors(Error_XML); //creating Error class object
        $data['active'] = 'cam'; //assigning the active variable for the view
        $data['media'] = 'links'; //assigning the media variable for the view
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

        $obj = new Fanwires(); //create object for the Fanwire class
        $obj->getHistory(); //get the history which is recorded
        $data['history'] = $_SESSION['history'];
        $view = new View(); //creating the object to the view class
        $view->loadView($data, 'mediamanager/myLinks'); //loda the correcsponding view
    }

    public function photoslistAction() {
        global $fc;
        $data = array();
        $object = new Users(); //Creating Users class object
        $errorObject = new Errors(Error_XML); //creating Error class object
        $data['active'] = 'cam'; //assigning the active variable for the view
        $data['media'] = 'photos'; //assigning the media variable for the view
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
        $aid = $_REQUEST['id'];
        $obj = new Fanwires();
        $data['list'] = $obj->getAlbumDetails($aid);
        $view = new View(); //creating the object to the view class
        $view->loadView($data, 'mediamanager/photoslist'); //loda the correcsponding view
    }

    public function deleteAlbumAction() {
        $id = $_REQUEST['id'];
        $obj = new Fanwires();
        $obj->deleteAlbum($id);
        header("Location:".SITE_URL."/mediaCenter/");
       // echo "#div" . $id;
    }

    public function deleteArticleAction() {
        $id = $_REQUEST['id'];
        $obj = new Fanwires();
        $obj->deleteArticle($id);
        echo "#div" . $id;
    }
      public function deleteVideoAction() {
        $id = $_REQUEST['id'];
        $obj = new Fanwires();
        $obj->deleteVideo($id);
        echo "#div" . $id;
    }
    

    public function sendCommentAction() {
        $id = $_REQUEST['id'];
        $comment = $_REQUEST['comment'];
        $obj = new Fanwires();
        $obj->addPhotoComment($id, $comment);
    }

}

?>
