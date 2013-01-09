<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * Description of itemController
 * @author naresh
 */

class socialController {

    /**
     * socialMedia
     * getting all information abput the  social media
     */
    public function socialMediaAction() {
        global $fc;
        $object = new Users(); //Creating the object for the class Users
        $objectPhotos = new Photos();
        $objectSocial = new Social();
        $errorObject = new Errors(Error_XML); //Creating the object for the class Errors
        $obj = new Fanwires(); //Creating the object for the class Fanwires
        $data = array();
        $data['path'] = $_REQUEST['path'];
        $data['fanwires_for_moreguest'] = $object->getMyFanwiresGuest(); //for guest login
        $userDetails = $object->getUserDetails($_SESSION['id']); //current user details
        if ($userDetails[0]['profile_image'] != "")
            $data['profile_image'] = SITE_URL . "profile_images/" . $userDetails[0]['profile_image'];
        else
            $data['profile_image'] = SITE_URL . "views/images/logodef.png";

        if (isset($_GET['fwrid'])) {
            $data['fwrid'] = $fwrid = $_GET['fwrid'];
            $data['ac'] = 2;
            $sta = 2;
        } else if (isset($_GET['uid'])) {
            $data['uid'] = $uid = $_GET['uid'];
            $data['ac'] = 4;
            $sta = 1;
        } else {

            $arr = explode("&", $fc->extra);
            $arr = explode("=", $arr[1]);

            if ($arr[0] == 'fwrid') {
                $data['fwrid'] = $fwrid = $arr[1];
                $data['ac'] = 2;
                $sta = 2;
            } else {
                $data['uid'] = $uid = $arr[1];
                $data['ac'] = 4;
                $sta = 1;
            }
        }
        if (isset($data['fwrid'])) {
            $sta = 2;
            $data['active'] = 'fanwire';
            $data['fanwires'] = $object->getFanwireDetails($fwrid); //fanwires details
            $data['albums'] = $objectSocial->getSocialMedia($fwrid, $sta); //first parameter is id to whoome it belongs and second parameter is to whoome it belongs either to user or fanwire 
        } else {
            $sta = 1;
            $data['active'] = 'fanwire';
            $data['userDetails'] = $object->getUserDetails($uid); //fan details
            $data['albums'] = $objectSocial->getSocialMedia($uid, $sta); //first parameter is id to whoome it belongs and second parameter is to whoome it belongs either to user or fanwire
        }
        $data['sta'] = $sta;
        $data['path'] = $_REQUEST['path'];
        $uid = (isset($data['uid'])) ? $data['uid'] : $data['fwrid'];
        $data['profileid'] = $uid;
        $obj->getHistory();
        $data['history'] = $_SESSION['history'];
        $data['fanwires_for_more'] = $obj->getMasterDetailsMore();
        $data['active'] = 'fanwire';
        $data['fanwires']['name'] = (isset($data['uid'])) ? $object->getUserName($uid) : $obj->getFanwireName($uid); //fanwires details
        //get the zoom values
        if ($_SESSION['id'])
            $session = $_SESSION['id'];
        else
            $session = session_id();
        $data['zoomValues'] = $obj->saveZoomValues(end(explode('/', $_REQUEST['path'])), $session, '', '', '');
        if ($data['zoomValues'] == 1)
            $data['zoomValues'] = array(array('value' => 68, 'width' => 246, 'height' => 186));
        $data['notifications'] = $object->getNotifications($_SESSION['id']);
        $data['pagetitle'] = $data['fanwires']['name'] . ' and Social Media | ' . $data['fanwires']['name'] . ' On FanWire.';
        $data['metadescription'] = 'Follow ' . $data['fanwires']['name'] . ' to find the latest information on all social media, photos, videos, and news about ' . $data['fanwires']['name'] . ' only on FanWire.';
        $view = new View(); //Creating the object for the class View        
        $view->loadView($data, 'social/thumbSocial');
    }

    /**
     * ajaxMoreProfilesSocial
     * to load social data in ajax
     */
    public function ajaxMoreProfilesSocialAction() {

        global $fc;
        $actual_image_name = "";
        $object = new Users(); //Creating the object for the class Users
        $objectFanwires = new Fanwires();
        $objectSocial = new Social();
        $errorObject = new Errors(Error_XML); //Creating the object for the class Errors
        $data = array();
        $userDetails = $object->getUserDetails($_SESSION['id']);
        if ($userDetails[0]['profile_image'] != "")
            $data['profile_image'] = SITE_URL . "profile_images/" . $userDetails[0]['profile_image'];
        else
            $data['profile_image'] = SITE_URL . "views/images/logodef.png";
        if ($_SESSION['id'])
            $session = $_SESSION['id'];else
            $session = session_id();

        $zoom = $data['zoomValues'] = $objectFanwires->saveZoomValues(end(explode('/', $_POST['path'])), $session, '', '', '');
        $start = $_POST['strlimit'];
        $lastmsg = $start + 10;
        $result = $objectSocial->getSocialMedia($_POST['profileid'], $_POST['belongsTo'], $start);
        $html = '';
        foreach ($result as $key => $value) {
            $nu = '';
            $style1 = '';
            $comment = '';
            $style2 = '';
            $style3 = '';
            $style4 = '';
            $share_n_collect = '';
            $description2 = '';
            $description = '';
            $description1 = '';
            $comments = '';
            $share_popup = '';
            $show = '';
            if ($value['source'] != 2 && $value['source'] != 1) {
                if ($value['type'] != 4) {
                    $show .="<div style=' display: block; clear: both;float:right;'><span ><img src='" . SITE_URL . "views/images/display" . $value['type'] . ".png' width='25' height='21' align='left' /></span></div>";
                }
            }
            if ($zoom[0]['width'] == 0 || $zoom[0]['width'] == '')
                $zoom[0]['width'] = 246;
            if ($zoom[0]['width']) {
                $style = "style='width:" . $zoom[0]['width'] . "px;'";
            }
            if ($value['source'] == 2 || $value['photo'] == '') {
                $style1 .= "style=float:left;width:79px;";
            } else {
                $style1 .= "";
            }
            if ($value['source'] == 2) {
                $style2 .= '<img src="' . IMAGE_URL . $value['photo'] . '"  width="75" height="75"/>';
            } else if ($value['photo'] == '') {
                $style2 .= '<img src="' . IMAGE_URL . $value['fanwire_profile_img'] . '" width="75" height="75"/>';
            } else {
                if ($value['type'] == 0) {
                    $style3 .= 'href="' . $value['link'] . '"';
                    if ($zoom[0]['width'] > 0) {
                        $widthImageShare = $zoom[0]['width'];
                        $heightImageShare = $zoom[0]['width'] * ($value['height'] / $value['width']);
                    } else {
                        $widthImageShare = 246;
                        $heightImageShare = 246 * ($value['height'] / $value['width']);
                    }
                } else {
                    $style3 .= 'href="' . $value['title_link'] . '"';
                    if ($zoom[0]['width'] > 0) {
                        $widthImageShare = $zoom[0]['width'];
                        $heightImageShare = $zoom[0]['width'] * ($value['height'] / $value['width']);
                    } else {
                        $widthImageShare = 246;
                        $heightImageShare = 246 * ($value['height'] / $value['width']);
                    }
                }
                $style2 .= '<input type="hidden" name="image_width" id="image_width"  value="' . $value['width'] . '" /><input type="hidden" name="image_height" id="image_height" value="' . $value['height'] . '" /><a ' . $style3 . 'class="userProf" ><img id="image" src="' . IMAGE_URL . $value['photo'] . '" width="' . $widthImageShare . '" height="' . $heightImageShare . '"/></a>';
            }
            if ($value['source'] == 2 || $value['photo'] == '') {

                if ($zoom[0]['width'] < 215)
                    $style4 .= "style='float: left;width:51%'";
                else
                    $style4 .= "style='float: left;width:66%'";
            }
            $share = ''; // '<div id="fanid'.$value['id'].'" class="share_n_colletc"> <a class="share_img_btn" name =\'share\' onclick = "getFanwireFans(\''.$value['id'].'\'); shareTogg(\''.$value['id'].'\');displayBlock(\'black\'); " href="javascript:void(0);"> <span class="icon"><img src="'.SITE_URL.'views/images/share_icon.png"/></span><span>Share</span> </a>  </div>'



            if ($value['title_link'] != '' && $value['source'] != 1 && $value['source'] != 2) {
                $description.='<a href="' . $value['title_link'] . '" style="color:#20A0BF;text-transform: none;text-decoration: none;font-size: 12px;">' . $value['title'] . '</a><br />';
            } else if ($value['source'] != 1 && $value['source'] != 2) {
                $description .=' <span style="color:#20A0BF;text-transform: none;text-decoration: none;font-size: 12px;">' . $value['title'] . '</span>';
            }
            if ($value['source'] == 2)
                $description .='<div style=" display: block; clear: both;"><span style="float:left;padding: 5px 5px 0 0px;">via</span><span style="float:left"> <img src="' . SITE_URL . 'views/images/twt.png" width="25" height="21" align="left" /></span><span style="float:left;padding: 5px 0 0 0px;"><b>Twitter</b></span></div> ';
            if ($value['source'] == 1 && $value['photo'] == '')
                $description .='<div style=" display: block; clear: both;"><span style="float:left;padding: 5px 3px 0 0;">via</span><span style="float:left;padding: 5px"> <img src="' . SITE_URL . 'views/images/facebook.png" align="left" /></span><span style="float:left;padding: 5px 0 0 3px;"><b>Facebook</b></span></div>';

            $textDescription = YoutubeUrl::truncate_chars($value['description'], 300);
            if ($value['title_link'] != '' && $value['source'] != 1 && $value['source'] != 2) {
                $description1 .= '<a onclick="return javascript:void(0);" href="' . $value['title_link'] . '" style="text-decoration: none;color:#424140;">' . $textDescription . '</a>';
            } else {
                $description1 .= $textDescription;
            }

            if ($value['source'] == 1 && $value['photo'] != '')
                $description2 .= '<div style=" display: block; clear: both;"><span style="float:left;padding: 5px 3px 0 0;">via</span><span style="float:left;padding: 5px"> <img src="' . SITE_URL . 'views/images/facebook.png" align="left" /></span><span style="float:left;padding: 5px 0 0 3px;"><b>Facebook</b></span></div>';
            if (ACTIVATE == 1)
                $share_popup .='<a href="javascript:void(0)" class="close"  onClick="Popup.show(\'sub' . $value['id'] . $value['type'] . '\');" ></a>';
            if ($value['article_from'] == 'twitter' || $value['article_from'] == 'facebook')
                $text = 'posts';
            $share_popup .='<div id = "sub' . $value['id'] . $value['type'] . '" class = "sub3" >
                <ul class = "facebook_dd">
                    <li><a href="' . SITE_URL . 'remove?id=' . $value['id'] . '&type=' . $value['type'] . '">Hide Element</a></li>
                    <li><a href = "#" onclick = "return tog(\'tog' . $value['id'] . $value['type'] . '\');">Report element or spam</a>
                        <div id = "tog' . $value['id'] . $value['type'] . '" style = "display:none;">
                            <span><a class = "selected" href = "#">Inappropriate content</a></span>
                            <span><a href = "#">Sapm</a></span>
                            <span><a href = "#">Miscategorized</a></span>
                            <span><a href = "#">Other</a></span>
                        </div>
                     </li>
                     <li><a href="#">Remove ' . $value['article_from'] . $text . ' from my profile</li>
                     <li><a href="' . SITE_URL . 'remove?id={$fan.id}&type={$fan.type}">Remove {$fan.name} permanently</a></li>
                </ul>
             </div>';

            if ($value['status'] == 1)
                $share_n_collect .='<span class="icon" ><img src="' . SITE_URL . 'views/images/collected_icon.png"/></span><span style="background: none repeat scroll 0 0 green;" >collected</span>'; else
                $share_n_collect .='<span class="icon"><img src="' . SITE_URL . 'views/images/collect_icon.png"/></span> <span>collect</span>';
            if ($value['commentcnt'] > 0)
                $comment .= '(' . $value['commentcnt'] . ')';
            if ($value['dislike'] > 0)
                $dislike = '(' . $value['dislike'] . ')'; else
                $dislike = '&nbsp;';
            if ($value['like'] > 0)
                $like = '(' . $value['like'] . ')'; else
                $like = '&nbsp;';
            if ($value['commentcnt'] > 5)
                $comm = '<a id="view_all_link' . $value['id'] . $value['type'] . '" href="javascript:void(0);" onclick="viewAllComments(\'' . $value['id'] . '\',\'' . $value['type'] . '\', \'' . SITE_URL . 'fan/viewAllComments\')" >view all comments</a>'; else
                $comm = '<a href="javascript:void(0);" >&nbsp;</a>';

            if ($value['type'] == 4) {
                $inst = '<div style=" display: block; clear: both;"><span style="float:left;padding: 5px 5px 0 0px;">via</span><span style="float:left"> <img src="{$smarty.const.SITE_URL}views/images/display4.png" width="19" height="19" align="left" /></span><span style="float:left;padding: 5px 0 0 0px;"><b> &nbsp;Instagram</b></span></div>';
            }
            if ($value['commentcnt'] > 5)
                $height = 'class="staticUL"';
            if ($value['commentcnt'] < 5)
                $height = ' style="max-height:' . ($value['commentcnt'] * 42) . 'px;"';
            foreach ($value['comments_for_this_post'] as $keys => $values) {
                $comments.='<li ><img src = "' . $values['profile_image'] . '"><span style = "float: left;"><strong>' . $values['name'] . '</strong> <h5 style = "float: right; text-decoration: none;" >' . $values['stamp'] . '</h5>&nbsp;</span><span>' . $values['comment'] . '</span><div class = "clear"></div></li >';
            }
            $html .= '<div  id="mydiv" class="collect_photo item block"' . $style . '>
                    <div class="image_share_popup" id="image_share_popup" ' . $style1 . '>' . $style2 . '</div>
                    <div class="red_links" >' . $share . '
                    <div id="collect' . $value['id'] . $value['type'] . '" class="share_n_colletc">
                    <a class="collect_img_btn" href="javascript:void(0);" onclick="collect(\'' . $value['id'] . '\',\'' . $value['type'] . '\',\'' . SITE_URL . 'collect\');">' . $share_n_collect . '</a>
                    </div>' . $share_popup . '</div> 
                    <div class="data">
                    <a href="' . $value['link'] . '" style="color: #F04F2C;font-size: 14px;font-family: OswaldBook;text-decoration: float:left; none;text-transform: uppercase;">' . $value['name'] . '</a>
                    ' . $show . '<span style="float:right;padding:0 3px 0 ;">' . $value['time'] . '</span>
                    <div style="clear:both;"></div> ' . $description . $inst . '
                        
                    <div class="clear"></div>
                    </div>
                    <div class="data" style="clear:both;padding: 8px 0 8px 0;"><span style="clear:both;"  class=\'more1\'>' . $description1 . '</span>
                       <br /><span style="line-height:25px;margin-bottom: 5px; ">' . $description2 . '</span></div>
                    <div class="photo_post">
                     <div class="message" id="showcomment' . $value['id'] . $value['type'] . '" onClick="ShowDialog(\'true\',\'' . $value['id'] . '\',\'' . $value['type'] . '\');">
                        ' . $comment . '
                     </div>
                     <script type="text/javascript"> window.onload=onloadcall(\'' . $value['id'] . '\');</script>
                     <div id="showdislike' . $value['id'] . $value['type'] . '" class="red" onclick="dislikefanwire(\'' . $value['id'] . '\',\'' . $value['type'] . '\',\'' . SITE_URL . 'fan/fanwirelikes\');">' . $dislike . '</div>
                     <div id="showlike' . $value['id'] . $value['type'] . '" class="blue" onclick="likefanwire(\'' . $value['id'] . '\',\'' . $value['type'] . '\',\'' . SITE_URL . 'fan/fanwirelikes\');">' . $like . '</div>
                     <div id="dialog' . $value['id'] . $value['type'] . '" class="web_dialog" >
                     <div class="comments" >
                    <div>
                        <ul >  
                            <li class="view_comments">
                               ' . $comm . ' 
                               <a href="javascript:void(0);" class="cross_btn" onclick="HideDialog(\'' . $value['id'] . '\',\'' . $value['type'] . '\');" >X</a>
                            </li>
                        </ul>
                    </div>     
                             <div ' . $height . '>
                                 <ul id="all_comments' . $value['id'] . $value['type'] . '"  >

                                    ' . $comments . '

                                               </ul>

                                           </div> 
                                           <div id=\'comm' . $value['id'] . $value['type'] . '\'></div>
                    <div>
                                               <ul>
                                                   <li >
                                                       <img src="' . $data['profile_image'] . '" style="float: left;">
                                                       <textarea  id="msg' . $value['id'] . $value['type'] . '"  style="float: left;" name="msg' . $value['id'] . $value['type'] . '"  onkeyup=\'expandtext(this)\' onclick="return showSend(\'' . $value['id'] . '\');"onfocus=" return textlimits(\'#remainingCharacters' . $value['id'] . $value['type'] . '\',\'#msg' . $value['id'] . $value['type'] . '\');" onKeyPress="Javascript:if(event.keyCode==13) submitForm(\'' . $value['id'] . '\',\'' . $value['type'] . '\',\'' . SITE_URL . 'fan/sendComment\',\'' . SITE_URL . 'fan/commentCount\');" placeholder="say something..." title="say something..."   autocomplete="off" rows="1" ></textarea>
                    <div id = "commentsToShow' . $value['id'] . $value['type'] . '" style = "display: none;">
                    <button type = "button" class = "sendComment" onclick = "submitForm(\'' . $value['id'] . '\',\'' . $value['type'] . '\',\'' . SITE_URL . 'fan/sendComment\',\'' . SITE_URL . 'fan/commentCount\');">send</button>
                    <span class = "characters" id = "remainingCharacters' . $value['id'] . $value['type'] . '">140 Characters</span>
                    </div>
                    <div class = "clear"></div>
                    </li>
                    <input type = "hidden" id = "id" name = "id" value = "' . $value['id'] . '" />
                    <input type = "hidden" name = "type" id = "type" value = "' . $value['type'] . '" />
                    </ul>
                    </div>
                    </div>
                    </div>
                    </div>
                    <div class = "clr"></div>
                    </div>
                    <div id = "shareLoader' . $value['id'] . '" style = " display: none; top: 38%;position: absolute;left: 45%;z-index: 9999;"><img src = "' . SITE_URL . 'views/images/loaderBlack.gif"></div>
                    <div id = "light1' . $value['id'] . '" class = "share_fanwire" style = "display: none;">
                    <div style = "padding:0 0 0 15px;"><img src = "' . SITE_URL . 'views/images/1.png" height = "13" width = "22" alt = ""/></div>
                    <div class = "share_fanwire_content">
                    <a href = "javascript:void(0);" style = "position: absolute;bottom: 0px;right: 0px;" onclick = "shareTogg(\'light1' . $value['id'] . '\');">
                    <img src = "' . SITE_URL . 'views/images/closebtn.gif">
                    </a>
                    <div class = "share_fanwire_content1">
                    <form action = "' . SITE_URL . 'shareContent" name = \'share\' method = "GET">
                    <div style = "float:left;">
                    <p>SHARE THIS FANWIRE<span id = "errorShare' . $value['id'] . '" style = "color: orangered;padding: 0 0 0 10px;font-size: 14px; font-family: OpenSansRegular;"></span></p></div>
                    <div class = "connect10"><a href = "#" onclick = "Share(\'' . $value['id'] . '\');shareTheContentToRespectNetworks(\'' . $value['id'] . '\');">send message</a></div>
                    <div class = "clear"></div>
                    </div>
                    <div class = "share_fanwire_left">
                    <input type = "hidden" name = \'fanid\' value = \'' . $value['link'] . '\'/>
                    <p><input type = "checkbox" name = \'email_share\' id = "email_share' . $value['id'] . '" /><label>Email</label></p>
                    <p style = "padding:18px 0 3px 18px;">To (Email Address)</p>
                    <div class = "clear"></div>
                    <input type = "text" name = \'email_addresses\' id = "email_addresses' . $value['id'] . '" class = "textfieldshare"/>
                    <div class = "share_fanwire_lefta">
                    <p><a href = "javascript:void(0);" onclick = "addPerMes(\'' . $value['id'] . '\',\'mail\')">+ Add Personal message</a></p>
                    <div id = "addPmesg' . $value['id'] . '" style = "display: none;">
                    <textarea name = "personalmessage" id = "personalmessage' . $value['id'] . '">hello</textarea>
                    </div>
                    <p><a href = "#">+Preview email</a></p>
                    </div>
                    <p style = "padding:10px 0 0 0"><input type = "checkbox" name = \'facebook_share\' id = \'facebook' . $value['id'] . '\' value = ""/><label>Facebook</label></p>
                    <div class = "share_fanwire_lefta">
                    <p><a href = "javascript:void(0);" onclick = "addPerMes(\'' . $value['id'] . '\',\'face\')">+ Add Personal message</a></p>
                    <div id = "addPmesgFace' . $value['id'] . '" style = "display: none;">
                    <textarea name = "personalmessageface" id = "personalmessageface' . $value['id'] . '">hello</textarea></div>
                    </div>
                    <p style = "padding:24px 0 0 0"><input type = "checkbox" name = \'twitter_share\'id = \'twitter' . $value['id'] . '\'value = "" />
                    <label style = "color:#a8a8a8">Twitter<a href = "#">&nbsp;
                    connect</a></label></p>
                    </div>
                    <div class = "share_fanwire_right">
                    <p>
                    <input type = "checkbox" />
                    <label>Fanwire Fans</label></p>
                    <p style = "padding:18px 0 3px 18px;">Share This to:</p>
                    <div class = "clear"></div>
                    <div class = "share_fanwire_checkbox" >
                    <input type = \'checkbox\' value = \'on\' name = \'allbox\' id = \'allbox' . $value['id'] . '\' class = "allbox" onclick = \'checkAll();\' >Select/Deselect All<br>
                    <div id = \'fanwire_fans' . $value['id'] . '\'></div>
                    </div>
                    </div>
                    </form>
                    <div class = "clear">&nbsp;
                    </div>
                    </div>
                    </div>
                    <div class = "clr"></div>';
        }
        echo $html . "##" . $lastmsg;
    }

    public function ajaxphotosAction() {
        $obj = new Social();
        $result = $obj->getImagess();
        $str = "<table ><tr><td><img src = " . IMAGE_URL . $result['list'][0]['photo'] . " height = \"70\" width = \"125\" /></td><td><img src = " . IMAGE_URL . $result['list'][1]['photo'] . " height = \"70\" width = \"125\" /></td></tr><tr><td><img src = " . IMAGE_URL . $result['list'][2]['photo'] . " height = \"70\" width = \"125\" /></td><td><img src = " . IMAGE_URL . $result['list'][3]['photo'] . " height = \"70\" width = \"125\" /><td></tr><tr><td><img src = " . IMAGE_URL . $result['list'][4]['photo'] . " height = \"70\" width = \"125\" /></td><td><img src = " . IMAGE_URL . $result['list'][5]['photo'] . " height = \"70\" width = \"125\" /><td></tr></table>";
        $str.= ":::" . $result['navigation'];
        echo $str;
    }

}

?>