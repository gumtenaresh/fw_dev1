<?php

/**
 * Description of Social
 * @author naresh
 */
class Social {

    private $conn = null;
    private $sql = null;
    private $rs = null;
    private $data = array();

    /**
     * creating dao object
     */
    function __construct() {
        $this->conn =  Dao::getInstance();
        $this->obj = new Users();
    }

    //destroying memory
    function __destruct() {
        unset($this->conn);
        unset($this->rs);
        unset($this->sql);
        unset($this->data);
    }

    /**
     * getSocialMedia
     * @param integer $uid 
     * @param integer $belongsTo
     * @param integer $start
     * @param integer $limit
     * @return array $list
     */
    public function getSocialMedia($uid, $belongsTo, $start = 0, $limit = 10) {
        $obj = new Users();
        $object = new Fanwires();
        $userid = $_SESSION['id'];
        $list = array();
        $fan_id = $uid;
        $this->sql2 = "select id,cdate,released_date,article_from,title,description,photo,source,user_id from tbl_articles where released_date!='0000-00-00 00:00:00' and  belongsTo='" . $belongsTo . "' and user_id=? and source in('1','2') and visible=1 ORDER BY cdate DESC limit " . $start . "," . $limit . "";
        $rs2 = $this->conn->selectSql($this->sql2, array($fan_id));
        error_log($fan_id);
        error_log($this->sql2);
        $i = 0;
        foreach ($rs2 as $val2) {
            $list[$i]['id'] = $val2['id'];
            $list[$i]['title'] = $val2['title'];
            $list[$i]['name'] = $object->getFanwireName($uid);
            $list[$i]['photo'] = $val2['photo'];
            if ($val2['photo'] == "") {
                $list[$i]['fanwire_profile_img'] = $object->getFanwireProfileImage($val2['user_id']);
            }
            list($width, $height) = getimagesize(DOC_ROOT_PATH . "/photos/" . $val2['photo']);
            $list[$i]['width'] = $width;
            $list[$i]['height'] = $height;
            $list[$i]['link'] = SITE_URL . $object->getFanwireUrl($uid); // "phots?fwrid=" . $uid . "&ac=1";
            $list[$i]['title_link'] = SITE_URL . $object->getFanwireUrl($uid) . "/Articles/" . str_replace(" ", "-", str_replace("'", "", $val2['title'])); //"viewArticle?aid=" . $val2['id'];
            $arr = explode(",", $obj->getLikesCount($val2['id'], '2'));
            $list[$i]['like'] = $arr[0];
            $list[$i]['dislike'] = $arr[1];
            $list[$i]['commentcnt'] = $obj->getCommentCount($val2['id'], '2');
            $list[$i]['status'] = $object->getCollectedStatus($val2['id'], '2');
            $list[$i]['type'] = '2';
            $list[$i]['source'] = $val2['source'];
            $list[$i]['article_from'] = $val2['article_from'];

            if ($val2['source'] == 0)
                $list[$i]['description'] = $val2['description'];
            else
                $list[$i]['description'] = YoutubeUrl::replace_plain_text_link(strtolower($val2['description']));
            if ($list[$i]['article_from'] == "Twitter") {
                $list[$i]['description'] = preg_replace('/(#\w+)/', '<span class=\'twitcolor\'>\1</span>', $list[$i]['description']);
            }
            $list[$i]['comments_for_this_post'] = $obj->getComments($val2['id'], '2');
            $list[$i]['time'] = DatePicker::getTimeStamp($val2['cdate']);
            $name = $obj->getFanwireDetails($fan_id);
            $list[$i]['fanwirename'] = $name['name'];
            $list[$i]['fanwireid'] = $name['id'];
            $i++;
        }

        return $list;
    }

    

}