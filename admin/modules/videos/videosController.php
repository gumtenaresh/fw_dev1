<?php

class videosController {

    public function addVideosAction() {

        if (empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE && $_SESSION['admintype'] != SUBADMIN_TYPE)) {
            header("Location:" . ADMIN_URL);
            exit;
        }
        $obj = new Videos();
        $pobj = new Photos();
        $object = new Users();
        $fan = new Fanwires();
        $data['fanwires'] = $pobj->getFanwires();
        $fan_id = $_REQUEST['id'];
        if(isset($fan_id))
        {
            $fan_name = $fan->getFanwireName($fan_id);
        }
        $data['fan_id'] = $fan_id; 
        $data['fan_name'] = $fan_name; 
        if (isset($_REQUEST['submit'])) { 
            $obj->insertVideos($_REQUEST);
        }
        $view = new View();
        $data['class'] = "fanwires";
        $data['menu'] = "media";
        $data['item'] = "videos";
        $data['ADMIN'] = ADMIN;
        $view->loadView($data, ADMIN . 'videos/addVideos');
    }

    public function videosListAction() {
        global $fc;
        $data = array();
        // authontication
        if (empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE && $_SESSION['admintype'] != SUBADMIN_TYPE)) {
            header("Location:" . ADMIN_URL);
            exit();
        }
        $fanwire_id = $_REQUEST['id'];
        $refid = $_REQUEST['refid'];
        $Obj = new Videos();
        $fan = new Fanwires();
        if (isset($_REQUEST['submit'])) {
            $cdate = $fan->dateformat($_REQUEST['cdate']);
            $Obj->updateDate($_REQUEST,$cdate);
            header("Location:" . ADMIN_URL . "videos/videosList?id=" . $fanwire_id."#".$refid);
        }
        if (isset($_REQUEST['release'])) {
            $dt_release = $fan->dateformat($_REQUEST['released_date']);
            $Obj->updateReleasedDate($_REQUEST,$dt_release);
            header("Location:" . ADMIN_URL . "videos/videosList?id=" . $fanwire_id."#".$refid);
        }
        if(isset($_REQUEST['deletebtn']))
        {
            extract($_REQUEST);
            if(isset($videochk))
            {
                $videoids = implode(",",$videochk);
                $Obj->deleteVideos($videoids);
            }
            
        }
        if(isset($_REQUEST['releasebtn']))
        {
            extract($_REQUEST);
            if(isset($videochk))
            {
                $videoids = implode(",",$videochk);
                $Obj->releaseVideos($videoids);
            }
        }
        
        $data['date'] = date("Y-m-d H:i:s");
        $cat = $Obj->getVideosList($fanwire_id);
        $data['list'] = $cat['list'];
        $data['navigation'] = $cat['navigation'];
        $data['ADMIN'] = ADMIN;
        $data['fanwire_id'] = $fanwire_id;
        $view = new View();
        $view->loadView($data, ADMIN . "videos/videosList");
    }

    /**
     * delete Videos 
     * @global type $fc 
     */
    public function deleteVideoAction() {
        global $fc;
        // authontication
        if (empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE && $_SESSION['admintype'] != SUBADMIN_TYPE)) {
            header("Location:" . ADMIN_URL);
            exit();
        }

        $id = $_REQUEST['id'];
        $fanwire_id = $_REQUEST['fanwire_id'];
        $Obj = new Videos();
        $Obj->deleteVideo($id);
        header("Location:" . ADMIN_URL . "videos/videosList?id=" . $fanwire_id);
    }

    /**
     * delete Videos 
     * @global type $fc 
     */
    public function editVideoAction() {
        global $fc;
        // authontication
        if (empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE && $_SESSION['admintype'] != SUBADMIN_TYPE)) {
            header("Location:" . ADMIN_URL);
            exit();
        }

        $id = $_REQUEST['id'];
        $fanwire_id = $_REQUEST['fanwire_id'];
        $Obj = new Videos();
         if (isset($_REQUEST['submit'])) {
            $Obj->editVideo($_REQUEST);
            //header("Location:" . ADMIN_URL . "videos/videosList?id=" . $fanwire_id);
        }
        $data['list'] = $Obj->getVideoDetails($id);
       // echo $id."<pre>";print_r($data['list']);
        $data['keywords'] = $data['list'][0]['keywords'];
        $data['additional_fans'] = $data['list'][0]['additional_fans'];        
        $var = explode('<', $data['list'][0]['video']);
        $var2 = explode('"', $var['1']);
        $data['videoNew'] = $var2['5'];
        $data['item'] = "videos";
        $data['ADMIN'] = ADMIN;
        $data['fanwire_id'] = $fanwire_id;
        $view = new View();
        $view->loadView($data, ADMIN . "videos/editVideos");
    }

    public function inlineEditAction() {
        
        $oj = new Videos();
        $var = explode('**', $_POST['rownum']);
         
        if (isset($_POST['rownum'])) {
            $oj->update_data($var[0], $_POST['value'], $var[1],$_POST['tbl_name']);
        }
    }

}
 

