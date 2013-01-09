<?php

/**
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of fanController
 * @author naresh
 */
class fanController {

    /**
     * 
     * Users Personal Profile action
     * 
     * */
    public function fanPersonalProfileAction() {
        if (!isset($_SESSION['name'])) {
            header("Location:" . SITE_URL);
        }
        $fwrid = $_GET['fwrid']; //get fanwire id
        $uid = $_GET['uid']; //get user id
        $object = new Users(); //Creating the object for the class Users
        $errorObject = new Errors(Error_XML); //Creating the object for the class Errors
        $data = array();
        $obj = new Fanwires(); //Creating the object for the class Fanwires
        $obj->getHistory();
        $data['history'] = $_SESSION['history'];
        $data['active'] = 'fanwire';
        $userDetails = $object->getUserDetails($_SESSION['id']);
        if ($userDetails[0]['profile_image'] != "")
            $data['profile_image'] = SITE_URL . "profile_images/" . $userDetails[0]['profile_image'];
        else
            $data['profile_image'] = SITE_URL . "views/images/logodef.png";
        $data['fanwires']['name'] = $object->getUserName($uid); //fanwires details
        $data['fanwires_for_more'] = $obj->getMasterDetailsMore();
        $data['fanid'] = $uid;
        $data['userid'] = $_SESSION['id'];
        $data['userDetails'] = $object->getUserDetails($uid); //get the user details
        $data['fansFanwires'] = $object->getFansFanwires($uid);
        $data['zoomValues'] = $obj->saveZoomValues('profile', $_SESSION['id'], '', '', '');
        $nid = array();
        foreach ($data['fansFanwires'] as $key => $value) {
            $nid[] = $value['fanid'];
        }
        $nids = implode(',', $nid);
        $data['fansFanwiresDeetails'] = $object->getFansFanwiresDetails($nids);
        //echo "<pre>";print_r($data['fansFanwiresDeetails']);exit;
        $data['notifications'] = $object->getNotifications($_SESSION['id']);
        $data['connects'] = $object->getConnects($_SESSION['id'], $uid);
        $view = new View(); //Creating the object for the class View
        if (isset($_SESSION['name'])) {
            $view->loadView($data, 'fan/fanPersonalProfile');
        } else {
            $view->loadView($data, 'fan/fanPersonalProfileSocial');
        }
    }

    public function sendMessageAction() {
        if (!isset($_SESSION['name'])) {
            header("Location:" . SITE_URL);
        }

        $to = $_REQUEST['fanid'];
        $from = $_REQUEST['userid'];
        $message = $_REQUEST['msg'];
        $obj = new Fanwires();
        $obj->sendMessage($to, $from, $message);
    }

    public function messageCenterAction() {
        if (!isset($_SESSION['name'])) {
            header("Location:" . SITE_URL);
        }

        $object = new Users();
        $obj = new Fanwires(); //Creating the object for the class Fanwires
        $obj->getHistory();
        $data['history'] = $_SESSION['history'];
        $data['message'] = $data['active'] = 'message';
        $userDetails = $object->getUserDetails($_SESSION['id']);

        if ($userDetails[0]['profile_image'] != "")
            $data['profile_image'] = SITE_URL . "profile_images/" . $userDetails[0]['profile_image'];
        else
            $data['profile_image'] = "";
        $data['fanwires_for_more'] = $object->getMyFanwiresMore();
        $data['notifications'] = $object->getNotifications($_SESSION['id']);
        $list = $obj->getMessages($_SESSION['id']);
        $data['msgcnt'] = $obj->getMessageCount($_SESSION['id']);
        $data['list'] = $list;
        $view = new View();
        $view->loadView($data, "fan/messageCenter");
    }

    public function requestsAction() {
        if (!isset($_SESSION['name'])) {
            header("Location:" . SITE_URL);
        }

        $object = new Users();
        $obj = new Fanwires(); //Creating the object for the class Fanwires
        $obj->getHistory();
        $data['history'] = $_SESSION['history'];
        $data['message'] = 'requests';
        $data['active'] = 'message';
        $userDetails = $object->getUserDetails($_SESSION['id']);
        if ($userDetails[0]['profile_image'] != "")
            $data['profile_image'] = SITE_URL . "profile_images/" . $userDetails[0]['profile_image'];
        else
            $data['profile_image'] = "";
        $data['fanwires_for_more'] = $object->getMyFanwiresMore();
        $data['notifications'] = $object->getNotifications($_SESSION['id']);
        $nid = array();
        foreach ($data['notifications'] as $key => $value) {
            $nid[] = $value['requester_user_id'];
            $data['commonFanwires'][$value['requester_user_id']] = $object->getCommonFanwires($value['requester_user_id'], $_SESSION['id']);
            //echo"<pre>";print_r($data['commonFanwires'][$value['requester_user_id']]);
            foreach ($data['commonFanwires'][$value['requester_user_id']] as $key1 => $value1) {
                $data['commonFanwiresDetails'][$value1['fanid']] = $object->getFanwireDetails($value1['fanid']);
            }
        }

        $nids = implode(",", $nid);
        //$data['commonFanwires']=$object->getCommonFanwires($nids);
        //echo "<pre>";print_r($data['commonFanwiresDetails']);exit;
        $data['userFansDetails'] = $object->getUserDetailsCombine($nids); //get the userdetails who ever sent request to connect the user
        // echo "<pre>";
        // print_r($data['commonFanwires']);
        $view = new View();
        $view->loadView($data, "fan/requests");
    }

    public function notificationsAction() {
        if (!isset($_SESSION['name'])) {
            header("Location:" . SITE_URL);
        }

        $object = new Users();
        $obj = new Fanwires(); //Creating the object for the class Fanwires
        $obj->getHistory();
        $data['history'] = $_SESSION['history'];
        $data['message'] = 'notifications';
        $userDetails = $object->getUserDetails($_SESSION['id']);
        if ($userDetails[0]['profile_image'] != "")
            $data['profile_image'] = SITE_URL . "profile_images/" . $userDetails[0]['profile_image'];
        else
            $data['profile_image'] = "";
        $data['fanwires_for_more'] = $object->getMyFanwiresMore();
        $data['notifications'] = $object->getNotifications($_SESSION['id']);
        $nid = array();
        foreach ($data['notifications'] as $key => $value) {
            $nid[] = $value['requester_user_id'];
            $data['commonFanwires'][$value['requester_user_id']] = $object->getCommonFanwires($value['requester_user_id'], $_SESSION['id']);
        }

        $nids = implode(",", $nid);
        //$data['commonFanwires']=$object->getCommonFanwires($nids);
        //echo "<pre>";print_r($data['commonFanwires']);exit;
        $data['userFansDetails'] = $object->getUserDetailsCombine($nids); //get the userdetails who ever sent request to connect the user
        // echo "<pre>";
        //    print_r($data['userFansDetails']);
        $view = new View();
        $view->loadView($data, "fan/notifications");
    }

    public function deleteMessageAction() {
        if (!isset($_SESSION['name'])) {
            header("Location:" . SITE_URL);
        }
        $senderid = $_REQUEST['id'];
        $obj = new Fanwires();
        $obj->deleteMessage($senderid, $_SESSION['id']);
    }

    public function moreMessagesAction() {

        if (!isset($_SESSION['name'])) {
            header("Location:" . SITE_URL);
        }
        $start = $_REQUEST['limit'];
        $object = new Users();
        $obj = new Fanwires(); //Creating the object for the class Fanwires
        $obj->getHistory();
        $data['history'] = $_SESSION['history'];
        $data['active'] = 'fanwire';
        $userDetails = $object->getUserDetails($_SESSION['id']);
        $data['profile_image'] = SITE_URL . "profile_images/" . $userDetails[0]['profile_image'];
        $data['fanwires_for_more'] = $object->getMyFanwiresMore();
        $data['notifications'] = $object->getNotifications($_SESSION['id']);
        $list = $obj->getMessages($_SESSION['id'], $start);

        $msgcnt = $obj->getMessageCount($_SESSION['id']);
        if ($msgcnt - 5 <= $start) {
            ?>
            <script>
                $("#showmore").hide();
            </script>
            <?php

        }
        // print_r($list);
        $output = '';
        foreach ($list as $key => $value) {
            // echo $value['sender_id'];

            if ($value['status'] == 0)
                $style = 'style="color:#000;font-weight:bold;cursor:pointer;"';
            else
                $style = 'style="cursor:pointer;color:#000;"';

            $output .= '<div id="message' . $value['id'] . '">
    <div class="message_data">    
    <div class="msg_photo">
    <a href="' . SITE_URL . 'profile?uid=' . $value['sender_id'] . '">
    <img src="' . SITE_URL . 'profile_images/' . $value['image'] . '" width="82" height="82" /></a><br />
  ' . $value['sender'] . '
    </div>
   <div class="msg_txt"> 
   <a href="' . SITE_URL . 'fan/conversations?sender_id=' . $value['sender_id'] . '" ' . $style . ' onclick="msgread(\'' . $value['sender_id'] . '\');">' . $value['message'] . '</a></div>
    <div class="reply_box">
        <input name="message_dialogue' . $value['id'] . '" id="message_dialogue' . $value['id'] . '" onclick="return opendialogue(\'#msg' . $value['id'] . '\',\'' . $value['id'] . '\');" type="button" class="reply_btn" value="Quick reply" />
        <input name="delete' . $value['id'] . '" id="delete' . $value['id'] . '" type="button" class="close_btn" value="X" onclick="return deleteMessage(\'' . $value['sender_id'] . '\',\'' . SITE_URL . 'fan/deleteMessage\',\'#message' . $value['id'] . '\',\'#output' . $value['id'] . '\');" />
    <br />
    <p>Last Message: ' . $value['date'] . '</p>
    </div>    	
    </div>
      </div>';

            $output .=
                    '<div id="overlay" class="web_dialog_overlay"></div>
     <form id="compose_message' . $value['id'] . '" name="compose_message' . $value['id'] . '" action="' . SITE_URL . 'fan/sendMessage" >
    <div id="dialog' . $value['id'] . '" class="web_dialog">
        <table style="width: 100%; border: 0px;" cellpadding="5" cellspacing="0">
            <tr>
                <td class="web_dialog_title">Compose</td>
                <td class="web_dialog_title align_right">
                    <a href="#" id="btnClose" onclick="closedialogue(' . $value['id'] . ');" >Close</a>
                </td>
            </tr>
            <tr><td colspan="2" style="height:30px;"></td></tr>
            <tr>
                <td align="right">Message:</td>
                <td>
                    <input type="hidden" id="fanid" name="fanid" value="' . $value['sender_id'] . '" />
                    <input type="hidden" id="userid" name="userid" value="' . $value['user_id'] . '" />
                    <textarea id="msg' . $value['id'] . '" name="msg' . $value['id'] . '" cols="30" rows="7" class="required"></textarea>
                </td>
            </tr>
          
            <tr>
                <td colspan="2" style="text-align: center;">
                    <input id="btnSubmit" type="button" value="Send" class="connect_btn" onclick="submitForm(\'' . $value['id'] . '\',\'' . SITE_URL . 'fan/sendMessage\');" style="margin-left:100px;margin-top: -5px" />
                </td>
            </tr>
        </table>        
    </div>
         </form>
      <div id="output' . $value['id'] . '" align="right" class="output"></div>';
        }

        echo $output;
    }

    public function conversationsAction() {

        if (!isset($_SESSION['name'])) {
            header("Location:" . SITE_URL);
        }
        $start = $_REQUEST['limit'];
        $object = new Users();
        $obj = new Fanwires(); //Creating the object for the class Fanwires
        $obj->getHistory();
        $data['history'] = $_SESSION['history'];
        $data['active'] = 'fanwire';
        $userDetails = $object->getUserDetails($_SESSION['id']);
        $data['profile_image'] = SITE_URL . "profile_images/" . $userDetails[0]['profile_image'];
        $data['fanwires_for_more'] = $object->getMyFanwiresMore();
        $data['notifications'] = $object->getNotifications($_SESSION['id']);
        $data['userid'] = $userid = $_SESSION['id'];
        $data['senderid'] = $senderid = $_REQUEST['sender_id'];
        if (isset($_REQUEST['submit']) && $_REQUEST['submit'] == 'Reply') {
            $message = $_REQUEST['msg'];
            $obj->sendMessage($senderid, $userid, $message);
        }
        $list = $obj->getConversation($userid, $senderid);
        $data['list'] = $list;
        //echo "<pre>";
        //print_r($list);exit();
        $view = new View();
        $view->loadView($data, "fan/conversation");
    }

    public function msgReadAction() {
        $senderid = $_REQUEST['senderid'];
        $obj = new Fanwires();
        $obj->msgRead($senderid, $_SESSION['id']);
    }

    public function sendCommentAction() {
        $id = $_REQUEST['id'];
        $comment = $_REQUEST['comment'];
        $type = $_REQUEST['type'];
        $obj = new Fanwires();
        //echo count($obj->checkCommentValidation($id,  $_SESSION['id'], $type)); exit;
        if (count($obj->checkCommentValidation($id, $_SESSION['id'], $type)) > 1) {
            echo '1';
        } else {
            $obj->sendComment($id, $comment, $_SESSION['id'], $type);
        }
    }

    public function viewAllCommentsAction() {
        $id = $_REQUEST['id'];
        $type = $_REQUEST['type'];
        $obj = new Users();
        $all_comments = $obj->viewAllComments($id, $_SESSION['id'], $type);

        $totc = '';
        // echo 'sai';
        $time = '';

        foreach ($all_comments as $comm) {

            $time = DatePicker::getTimeStamp($comm['comment_time']);

            $totc.="<li><img src='" . $comm['profile_image'] . "'><span><strong>" . $comm['name'] . "</strong><h5 style='float: right; text-decoration: none;' > " . $time . "</h5>&nbsp;</span><span>" . $comm['comment'] . "</span><div class='clear'></div></li >";
            $time = '';
        }
        echo $totc;
    }

    public function commentCountAction() {
        $id = $_REQUEST['id'];
        $type = $_REQUEST['type'];
        $obj = new Users();
        $count = $obj->getCommentCount($id, $type);
        $lastcomment = $obj->getLatestSixComments($id, $type);

        $comme = $lastcomment[0]['comment'];
        if ($lastcomment[0]['profile_image'] != "")
            $uimg = SITE_URL . "profile_images/" . $lastcomment[0]['profile_image'];
        else
            $uimg = SITE_URL . "views/images/logodef.png";
        $name = $lastcomment[0]['name'];
        $comment_time = $lastcomment[0]['comment_time'];
        $time = DatePicker::getTimeStamp($comment_time);


        echo $tot = $count . "@@" . "<ul><li><img src='" . $uimg . "'><span><strong>" . $name . "</strong><h5 style='float: right; text-decoration: none;' > " . $time . " </h5>&nbsp;</span><span>" . $comme . "</span><div class='clear'></div></li ></ul>";
    }

    public function fanwirelikesAction() {
        $fanwireid = $_REQUEST['fanwireid'];
        $type = $_REQUEST['type'];
        $like = $_REQUEST['like'];
        $dislike = $_REQUEST['dislike'];
        $obj = new Users();
        echo $obj->fanwireLikes($fanwireid, $_SESSION['id'], $like, $dislike, $type);
    }

    public function myFansAction() {
        if (!isset($_SESSION['name'])) {
            header("Location:" . SITE_URL);
        }
        $object = new Users();
        $obj = new Fanwires(); //Creating the object for the class Fanwires
        $obj->getHistory();
        $data['history'] = $_SESSION['history'];
        $data['active'] = 'group';
        $userDetails = $object->getUserDetails($_SESSION['id']);
        $data['profile_image'] = SITE_URL . "profile_images/" . $userDetails[0]['profile_image'];
        $data['fanwires_for_more'] = $object->getMyFanwiresMore();
        $data['userid'] = $userid = $_SESSION['id'];
        $data['notifications'] = $object->getNotifications($_SESSION['id']); //forgetting all the notifications which user received
        $dd = $object->getFansOfUser($_SESSION['id']);
        $nid = array();
        foreach ($dd as $key => $value) {
            $nid[] = $value['requester_user_id'];
            $data['commonFanwires'][$value['requester_user_id']] = $object->getCommonFanwires($value['requester_user_id'], $_SESSION['id']);
            //$data['userFansDetails'][$key]=$object->getUserDetails($value['requester_user_id']);
        }
        $nids = implode(",", $nid);
        $data['userFansDetails'] = $object->getUserDetailsCombine($nids); //get the userdetails who ever sent request to connect the user


        $view = new View();
        $view->loadView($data, "fan/myFans");
    }

    //find other fans rather than his fans
    public function findFansOtherAction() {
        if (!isset($_SESSION['name'])) {
            header("Location:" . SITE_URL);
        }
        $object = new Users();
        $obj = new Fanwires(); //Creating the object for the class Fanwires
        $obj->getHistory();
        $data['history'] = $_SESSION['history'];
        $data['active'] = 'group';
        $userDetails = $object->getUserDetails($_SESSION['id']);
        $data['profile_image'] = SITE_URL . "profile_images/" . $userDetails[0]['profile_image'];
        $data['fanwires_for_more'] = $object->getMyFanwiresMore();
        $data['userid'] = $userid = $_SESSION['id'];
        $data['notifications'] = $object->getNotifications($_SESSION['id']); //forgetting all the notifications which user received
        $dd = $object->getFansOfUser($_SESSION['id']);
        $nid = array();
        foreach ($dd as $key => $value) {
            $nid[] = $value['requester_user_id'];
            $data['commonFanwires'][$value['requester_user_id']] = $object->getCommonFanwires($value['requester_user_id'], $_SESSION['id']);
            //$data['userFansDetails'][$key]=$object->getUserDetails($value['requester_user_id']);
        }
        //$nids = implode(",", $nid);
        $data['userFansDetails'] = $object->getAllUser(); //get the userdetails who ever sent request to connect the user
        //echo "<pre>";print_r($data['userFansDetails']);

        $view = new View();
        $view->loadView($data, "fan/findFansOther");
    }

    public function connectAction() {
        if (!isset($_SESSION['name'])) {
            header("Location:" . SITE_URL);
        }
        $obj = new Users();
        $res = $obj->connectAsFriend($_POST['userId'], $_POST['friendId'], 1);
        /* if($res){
          $obj->connectAsFriend($_POST['friendId'],$_POST['userId'],2);
          } */
    }

    public function respondRequestAction() {
        if (!isset($_SESSION['name'])) {
            header("Location:" . SITE_URL);
        }
        $obj = new Users();
        $action = $_POST['action'] == 'ar' ? 4 : 3;
        echo $obj->requestAction($_POST['userId'], $_POST['fanId'], $action);
    }

    public function listFanwiresAction() {
        print_r($_GET['list']);
        exit;
    }

    public function theirFansAction() {
        if (!isset($_SESSION['name'])) {
            header("Location:" . SITE_URL);
        }
        $object = new Users();
        $obj = new Fanwires(); //Creating the object for the class Fanwires
        $errorObject = new Errors(Error_XML); //Creating the object for the class Errors
        $obj->getHistory();
        $uid = $_GET['uid']; //get user id
        $data = array();
        $nid = array();
        // $data['fanwires'] = $object->getFanwireDetails($fwrid); //fanwires details

        $data['fanwires_for_more'] = $obj->getMasterDetailsMore();
        $data['fanid'] = $uid;
        //$data['fans']=$object->getFanwiresFans($fwrid);//get the fanwires details
        $data['userDetails'] = $object->getUserDetails($uid); //get the  details of user fan
        $data['connects'] = $object->getConnects($_SESSION['id'], $uid);
        $data['history'] = $_SESSION['history'];
        $data['active'] = 'group';
        $userDetails = $object->getUserDetails($_SESSION['id']);
        //get the zoom values
        $data['zoomValues'] = $obj->saveZoomValues('uFans', $_SESSION['id'], '', '', '');
        if ($userDetails[0]['profile_image'] != "")
            $data['profile_image'] = SITE_URL . "profile_images/" . $userDetails[0]['profile_image'];
        else
            $data['profile_image'] = "";
        $data['userid'] = $userid = $_SESSION['id'];
        $data['active'] = 'fanwire';
        $data['fanwires']['name'] = $object->getUserName($uid); //fanwires details
        $data['notifications'] = $object->getNotifications($_SESSION['id']); //forgetting all the notifications which user received
        $dd = $object->getFansOfUser($uid);
        foreach ($dd as $key => $value) {
            $nid[] = $value['requester_user_id'];
            // $data['commonFanwires'][$value['requester_user_id']] = $object->getCommonFanwires($value['requester_user_id'], $_SESSION['id']);
        }
        $nids = implode(",", $nid);
        $data['userFansDetails'] = $object->getUserDetailsCombine($nids); //get the userdetails who ever sent request to connect the user
        $view = new View();
        $view->loadView($data, "fan/theirFans");
    }

    /**
     * fancollection
     */
    public function fanCollectionsAction() {

        if (!isset($_SESSION['name'])) {
            header("Location:" . SITE_URL);
        }

        $fwrid = $_GET['fwrid']; //get fanwire id
        $uid = $_GET['uid']; //get user id
        $object = new Users(); //Creating the object for the class Users
        $errorObject = new Errors(Error_XML); //Creating the object for the class Errors
        $data = array();
        $obj = new Fanwires(); //Creating the object for the class Fanwires
        $obj->getHistory();
        $data['history'] = $_SESSION['history'];
        $data['active'] = 'fanwire';
        $userDetails = $object->getUserDetails($_SESSION['id']);
        if ($userDetails[0]['profile_image'] != "")
            $data['profile_image'] = SITE_URL . "profile_images/" . $userDetails[0]['profile_image'];
        else
            $data['profile_image'] = "";
        $data['fanwires'] = $object->getFanwireDetails($fwrid); //fanwires details
        $data['fanwires_for_more'] = $obj->getMasterDetailsMore();
        $data['fanid'] = $uid;
        $data['userid'] = $_SESSION['id'];
        //$data['fans']=$object->getFanwiresFans($fwrid);//get the fanwires details
        $data['userDetails'] = $object->getUserDetails($uid); //get the user details
        //$data['fansFanwires'] = $object->getFansFanwires($uid);
        $data['fansCollections'] = $object->getFansCollections($uid);
        //get the zoom values
        $data['zoomValues'] = $obj->saveZoomValues('fCollections', $_SESSION['id'], '', '', '');
        $nid = array();
        foreach ($data['fansCollections'] as $key => $value) {
            $nid[] = $value['fanwire_id'];
        }
        $nids = implode(',', $nid);
        //$data['fansFanwiresDeetails'] = $object->getFansFanwiresDetails($nids);
        $data['fansFanwiresDeetails'] = $object->getCollectedFanwires($nids);
        $data['notifications'] = $object->getNotifications($_SESSION['id']);
        $data['connects'] = $object->getConnects($_SESSION['id'], $uid);
        $data['active'] = 'fanwire';
        $data['fanwires']['name'] = $object->getUserName($uid); //fanwires details
        //echo "<pre>";print_r($data['fansFanwiresDeetails']);
        $view = new View(); //Creating the object for the class View
        if (isset($_SESSION['name'])) {
            $view->loadView($data, 'fan/fanCollections');
        } else {
            $view->loadView($data, 'fanwires/fanwirePersonalProfileSocialMedia');
        }
    }

    /**
     * about the fan  is one of the user
     */
    public function aboutThisFanAction() {

        if (!isset($_SESSION['name'])) {
            header("Location:" . SITE_URL);
        }

        $fwrid = $_GET['fwrid']; //get fanwire id
        $uid = $_GET['uid']; //get user id
        $object = new Users(); //Creating the object for the class Users
        $errorObject = new Errors(Error_XML); //Creating the object for the class Errors
        $data = array();
        $obj = new Fanwires(); //Creating the object for the class Fanwires
        $obj->getHistory();
        $data['history'] = $_SESSION['history'];
        $data['active'] = 'fanwire';
        $userDetails = $object->getUserDetails($_SESSION['id']);
        if ($userDetails[0]['profile_image'] != "")
            $data['profile_image'] = SITE_URL . "profile_images/" . $userDetails[0]['profile_image'];
        else
            $data['profile_image'] = "";
        $data['fanwires'] = $object->getFanwireDetails($fwrid); //fanwires details
        $data['fanwires_for_more'] = $object->getMyFanwiresMore();
        $data['fanid'] = $uid;
        $data['userid'] = $_SESSION['id'];
        //$data['fans']=$object->getFanwiresFans($fwrid);//get the fanwires details
        $data['userDetails'] = $object->getUserDetails($uid); //get the user details
        //$data['fansFanwires'] = $object->getFansFanwires($uid);
        /* $data['fansCollections'] = $object->getFansCollections($uid);
          $nid = array();
          foreach ($data['fansCollections'] as $key => $value) {
          $nid[] = $value['fanwire_id'];
          }
          $nids = implode(',', $nid); */
        $data['fansFanwiresDeetails'] = $object->getUserDetails($uid); //getFansFanwiresDetails($nids);
        //get the zoom values
        $data['zoomValues'] = $obj->saveZoomValues('fanInfo', $_SESSION['id'], '', '', '');
        $data['notifications'] = $object->getNotifications($_SESSION['id']);
        $data['connects'] = $object->getConnects($_SESSION['id'], $uid);
        $data['active'] = 'fanwire';
        $data['fanwires']['name'] = $object->getUserName($uid); //fanwires details

        if (isset($_SESSION['name'])) {
            //	$data['fanwires'] = $object->getAllFanwiresWithOutCat();
            $view = new View(); //Creating the object for the class View
            $view->loadView($data, 'fan/aboutFan');
        } else {
            $view = new View(); //Creating the object for the class View
            $view->loadView($data, 'fanwires/fanwirePersonalProfileSocialMedia');
            //header('Location:'.SITE_URL);
        }
    }

//this is for changing the cover photo using the desktop upload to change and to remove this function is used
    public function changeCoverUsingDesktopAction() {

        global $fc;
        $object = new Users();
        $data = array();
        // authentication
        if (empty($_SESSION['id'])) {
            header("Location:" . SITE_URL);
            exit();
        }
        $userid = $_REQUEST['userid'];
        $Obj = new Fanwires();
        //echo "submit";
        $image = new SimpleImage();

        /*  if (isset($_REQUEST['avatar'])) {
          $image_name = $_FILES['avatar_img']['name'];
          list($txt, $ext) = explode(".", $image_name);
          $name = strtotime(date("Y-m-d H:i:s")) . "." . $ext;
          $tmp = $_FILES['avatar_img']['tmp_name'];
          $image->load($tmp);
          $image->resizeToWidth(225);
          $image->save(DOC_ROOT_PATH . '/photos/' . $name);
          $image->save(DOC_ROOT_PATH . '/photos/thumbs/' . $name);
          $Obj->updateAvatarPhoto($name, $fan_id);
          } */

        if (isset($_REQUEST['timeline']) && $_REQUEST['timeline'] = 'timeline') {
            $image_name = $_FILES['timeline_img']['name'];
            list($txt, $ext) = explode(".", $image_name);
            $name = strtotime(date("Y-m-d H:i:s")) . "." . $ext;
            $tmp = $_FILES['timeline_img']['tmp_name'];
            $image->load($tmp);
            //$image->resizeToWidth(1024);
            $image->save(DOC_ROOT_PATH . '/photos/' . $name);
            DOC_ROOT_PATH . '/photos/' . $name;

            $Obj->updateTimelinePhotoUser($name, $userid);
            header("Location:" . SITE_URL . 'profile?uid=' . $userid . '&ac=1');
            exit;
        }
        if (isset($_REQUEST['act']) && $_REQUEST['act'] == 'remove') {
            $name = '';
            echo $Obj->updateTimelinePhotoUser($name, $userid);
            exit;
        }
    }

}
?>
