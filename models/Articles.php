<?php

/**
 * Description of Articles
 * @author gangaraju
 */
class Articles {

    private $conn = null;
    private $sql = null;
    private $rs = null;
    private $data = array();

    /**
     * creating dao object
     */
    function __construct() {
        $this->conn = Dao::getInstance();
        $this->conn1 = Dao1::getInstance();
        $this->obj = new Users();
    }

    //destroying memory
    function __destruct() {
        unset($this->conn);
        unset($this->conn1);
        unset($this->rs);
        unset($this->sql);
        unset($this->data);
    }

    /**
     * get articles list
     * @param type $pageSize
     * @return type
     */
    public function getArticlesList($fanwire_id, $sort, $pageSize = 100) {
        $orderby = " order by article_from asc";
        if ($sort != "")
            $orderby = " order by article_from " . $sort;
        $this->sql = "SELECT * from tbl_articles where belongsTo='2' and source in ('0','3')  and visible!='2' and user_id=? " . $orderby;
        $pageObject = new Pagination($pageSize, 9); //params: pagesize, scroll size
        $navigation = $pageObject->showNavigation(sizeof($this->conn->executeQuery($this->sql)));
        $this->sql .= $pageObject->getLimitQry();
        $this->rs = $this->conn->selectSql($this->sql, array($fanwire_id));
        $i = 0;
        $obj = new Fanwires();
        foreach ($this->rs as $val) {
            $this->data[$i]['id'] = $val['id'];
            $this->data[$i]['title'] = $val['title'];
            $this->data[$i]['description'] = $val['description'];
            $this->data[$i]['visible'] = $val['visible'];
            $this->data[$i]['photo'] = $val['photo'];
            $this->data[$i]['fanwire_photo'] = $obj->getFanwireProfileImage($val['user_id']);
            list($width, $height) = getimagesize(DOC_ROOT_PATH . "/photos/" . $val['photo']);
            $new_width = 800;
            if ($width > $new_width) {
                $this->data[$i]['width'] = $new_width;
                $this->data[$i]['height'] = $new_width * ($height / $width);
            } else {
                $this->data[$i]['width'] = $width;
                $this->data[$i]['height'] = $height;
            }

            $this->data[$i]['fanwire'] = $obj->getFanwireName($val['user_id']);
            $this->data[$i]['cdate'] = $val['cdate'];
            $this->data[$i]['article_from'] = $val['article_from'];
            $this->data[$i]['released_date'] = $val['released_date'];
            $i++;
        }
        $this->data['list'] = $this->data;
        $this->data['navigation'] = $navigation;
        return $this->data;
    }

    /**
     * delete article
     * @param type $id
     * @return type
     */
    public function deleteArticle($id) {
        $this->sql = "update tbl_articles set visible='2' where id=?";
        $data = array($id);
        return $this->conn->executePrepared($this->sql, $data) or die(mysql_error());
    }

    /**
     * add article
     * @param type $title
     * @param type @fanid
     * @param type @description
     * @return type
     */
    public function addArticle($data) {
        extract($data);

        $this->sql = "INSERT INTO tbl_articles(`title`,`user_id`,`description`) values(?,?,?)";
        $data = array($title, $user_id, htmlentities($elm1));


        $this->conn->executePrepared($this->sql, $data) or die(mysql_error());
        return $this->conn->getInsertId();
    }

    /**
     *
     * @param type $id
     * @param type $album
     * @param type $dir_array
     * @return type
     */
    public function addArticlesUser($id, $title, $album, $dir_array, $cover_image, $sta, $description) {

        if ($title == "")
            $title = "Default one";


        $this->sql = "insert into tbl_articles(`user_id`,`title`,`description`,`photo`,`status`,`belongsTo`) values(?,?,?,?,?,?)";
        $data = array($id, $title, $description, $cover_image, $sta, '1');


        $this->conn->executePrepared($this->sql, $data) or die(mysql_error());
        $albumid = $this->conn->getInsertId();
        foreach ($dir_array as $val) {
            $p = explode("_", $val);
            $q = explode('.', $p[1]);
            $this->sql = "insert into tbl_photos(`name`,userid,article_id,status,caption) values(?,?,?,?,?)";
            $data = array($val, $id, $albumid, $sta, $q[0]);
            $this->conn->executePrepared($this->sql, $data) or die(mysql_error());
        }
        return $albumid;
    }

    /**
     * update article status
     * @param type $article_id
     * @param type $status
     * @return type
     */
    public function updateArticleStatus($article_id, $status) {
        $this->sql = "update tbl_articles set status=? where id=?";
        $data = array($status, $article_id);
        return $this->conn->executePrepared($this->sql, $data) or die(mysql_error());
    }

    /**
     *
     * @param type $aid
     * @return type
     */
    public function getArticleDetails($aid) {
        $this->sql = "SELECT * from tbl_articles where id=?";
        $this->rs = $this->conn->selectSql($this->sql, array($aid));
        $i = 0;
        $objUser = new Users();
        $obj = new Fanwires();
        foreach ($this->rs as $val) {
            $this->data[$i]['id'] = $val['id'];
            $this->data[$i]['user_id'] = $val['user_id'];
            $this->data[$i]['title'] = ($val['title']);
            $this->data[$i]['description'] = stripslashes($val['description']);
            $this->data[$i]['photo'] = $val['photo'];
            $this->data[$i]['heigtwidth'] = YoutubeUrl::thumbnail_sizefix($val['photo'], 288, 373);
            $this->data[$i]['published_date'] = $val['published_date'];
            $this->data[$i]['article_source'] = $val['article_source'];
            $this->data[$i]['article_link'] = $val['article_link'];
            $sub = explode('.', $val['article_link']);
            $this->data[$i]['article_sub_link'] = $sub['0'] . '.' . $sub['1'] . '.' . end(array_reverse(explode('/', $sub['2'])));
            $this->data[$i]['type_source'] = $val['type_source'];
            $this->data[$i]['article_from'] = $val['article_from'];
            $this->data[$i]['cdate'] = DatePicker::getTimeStamp($val['cdate']);
            $this->data[$i]['date'] = date("m/d/y", strtotime($val['cdate']));
            //$arr = explode(",", $objUser->getLikesCount($val['id'], ART_TYPE));
            //$this->data[$i]['like'] = $arr[0];
            $this->data[$i]['source'] = $val['source'];
            //$this->data[$i]['dislike'] = $arr[1];
            $this->data[$i]['released_date'] = $val['released_date'];
            //$this->data[$i]['artstatus'] = $obj->getCollectedStatus($val['id'], ART_TYPE);
            //$this->data[$i]['keywords'] = $obj->getKeywords($val['id'], '3');
            //$this->data[$i]['additional_fans'] = $obj->getAdditionalFans($val['id'], '1');
            $i++;
        }
        return $this->data;
    }

    /**
     *
     * @param type $userid
     * @return type
     */
    public function getUserName($userid) {
        $sql = "select fname,lname from tbl_users where id=?";
        $rs = $this->conn->selectSql($sql, array($userid));
        return $rs[0]['fname'] . " " . $rs[0]['lname'];
    }

    /**
     * @param type $uid,$belongsTo
     * @return type
     */
    public function getArticles($uid, $belongsTo) {
        $obj = new Users();
        $this->sql = "SELECT * from tbl_articles where visible='1' and user_id=? and belongsTo=?";
        $this->rs = $this->conn->selectSql($this->sql, array($uid, $belongsTo));
        $i = 0;
        foreach ($this->rs as $val) {
            $this->data[$i]['id'] = $val['id'];
            $this->data[$i]['title'] = $val['title'];
            $this->data[$i]['description'] = stripslashes($val['description']);
            $this->data[$i]['photo'] = $val['photo'];
            $this->data[$i]['status'] = $val['status'];
            $arr = explode(",", $obj->getLikesCount($val['id']));
            $this->data[$i]['like'] = $arr[0];
            $this->data[$i]['dislike'] = $arr[1];
            $this->data[$i]['commentcnt'] = $obj->getCommentCount($val['id'], 2);
            $this->data[$i]['userid'] = $this->getUserName($val['user_id']);
            $this->data[$i]['comments_for_this_post'] = $obj->getComments($val['id'], 2);
            $i++;
        }
        return $this->data;
    }

    /**
     *  getArticlePhotos
     * @param type $uid,$belongsTo
     * @return type
     */
    public function getArticlePhotos($aid) {
        $this->sql = "select name from tbl_photos where article_id=?";
        $this->rs = $this->conn->selectSql($this->sql, array($aid));
        return $this->rs;
    }

    /**
     *
     * @param object $params
     * @param object $dir_array array directory

     */
    public function insertArticle($params, $dir_array) {
        extract($params);
        $status = '1';
        $this->sql = "insert into tbl_articles(`user_id`,`title`,`description`,`photo`,`status`,`belongsTo`,`source`,`released_date`) values(?,?,?,?,?,?,?,?)";
        $data = array($fanwireName, $articlename, $description, $cover_image, $status, '2', $source, date("Y-m-d H:i:s", strtotime($released)));
        $this->conn->executePrepared($this->sql, $data) or die(mysql_error());
        $articleid = $this->conn->getInsertId();
        //echo "<pre>";print_r($dir_array);
        foreach ($dir_array as $val) {
            $p = explode("_", $val);
            $q = explode('.', $p[1]);
            $this->sql = "insert into tbl_photos(`name`,userid,article_id,status,caption) values(?,?,?,?,?)";
            $data = array($val, $_SESSION['adminid'], $articleid, $status, $q[0]);
            $this->conn->executePrepared($this->sql, $data) or die(mysql_error());
        }

        if (isset($keyword)) {
            $keys = explode(",", $keyword);
            foreach ($keys as $val) {
                $this->sql = "insert into tbl_keywords(`keyword`,`referer_id`,`type`) values(?,?,?)";
                $data = array($val, $articleid, 3);
                $this->conn->executePrepared($this->sql, $data) or die(mysql_error());
            }
        }
        if (isset($fanwire)) {
            $fans = explode(",", $fanwire);
            foreach ($fans as $value) {
                $fanwire_id = $this->getFanwireId($value);
                if (isset($fanwire_id)) {
                    $this->sql = "insert into tbl_additional_fanwires(`fanwire_id`,`content_id`,`content_type`) values(?,?,?)";
                    $data = array($fanwire_id, $articleid, '1');
                    $this->conn->executePrepared($this->sql, $data) or die(mysql_error());
                }
            }
        }
    }

    /**
     * name    getFanwireid
     * @param object $name fanwire name
     * @return object fanwire id
     */
    public function getFanwireId($name) {
        $this->sql = "SELECT id from tbl_fanwires where name=?";
        $this->rs = $this->conn->selectSql($this->sql, array($name));
        return $this->rs[0]['id'];
    }

    /**
     * getComments
     * @param integer $id id of the block which it belongs to
     * @param integer $type type of blcok eg article /video/photo etc.
     * @return array set of comments
     *
     */
    public function getComments($aid, $type) {
        $this->sql = "SELECT * FROM tbl_comments where receiver_type=? and receiver_id=? ORDER BY  `id` DESC LIMIT 0, 3";
        $this->rs = $this->conn->selectSql($this->sql, array($type, $aid));
        $i = 0;
        $obj = new Users(); //This object is created to fetch commentcount display
        foreach ($this->rs as $value) {
            $data[$i]['id'] = $value['id'];
            $data[$i]['name'] = $this->getUserName($value['userid']);
            $data[$i]['description'] = $value['comment'];
            $data[$i]['photo'] = $this->obj->getUserPhoto($value['userid']);
            $data[$i]['cover_photo'] = $this->obj->getCoverPhoto($value['userid']);
            $data[$i]['commentcnt'] = $obj->getCommentCount($aid, $type); //fetch commentcount diplay number
            $data[$i]['comments_for_this_post'] = $obj->getComments($value['id'], $type); //fetch comments display
            //below code this to fetch likes and dislikes display in onload

            $arr = explode(",", $obj->getLikesCount($value['id'], $type));
            $data[$i]['like'] = $arr[0];
            $data[$i]['dislike'] = $arr[1];
            $data[$i]['comment_time'] = $value['comment_time'];

            $time = DatePicker::getTimeStamp($value['comment_time']);
            $data[$i]['stamp'] = $time;
            $i++;
        }
        return $data;
    }

    /**
     * getRelatedArticlesNames
     * @param string  $string Title name
     * @return array array of related titles
     */
    function getRelatedArticlesNames($string) {
        $objectFanwires = new Fanwires();
        if ($_SESSION['id']) {
            $limit = 6;
        } else {
            $limit = 6; //10
        }
        $this->sql = "SELECT DISTINCT id,title,photo,user_id from tbl_articles where `title` NOT LIKE '$string' and `source` in ('0','3') and visible='1' ORDER BY RAND() ASC LIMIT 0,$limit  ";
        $this->rs = $this->conn->executeQuery($this->sql);
        $i = 0;
        foreach ($this->rs as $value) {
            $data[$i]['id'] = $value['id'];
            $data[$i]['title'] = $value['title'];
            $data[$i]['photo'] = $value['photo'];
            $title = str_replace("'", "", $value['title']);
            $title = str_replace(" ", "-", $title);
            $title = str_replace(",", "-", $title);
            $title = str_replace(".", "-", $title);
            $title = str_replace("(", "-", $title);
            $title = str_replace(")", "-", $title);
            $title = str_replace('"', '-', $title);
            $title = str_replace('#', '', $title);
            $title = str_replace('&', '', $title);
            $data[$i]['title_link'] = SITE_URL . $objectFanwires->getFanwireUrl($value['user_id']) . "/Articles/" . $title;
            list($width, $height) = getimagesize(IMAGE_URL . $data[$i]['photo']);
            $data[$i]['width'] = 40;
            $data[$i]['height'] = floor(($height / $width) * 40);
            $i++;
        }
        return $data;
    }

    /**
     * checkArticle
     * @param string  $title Title of the articles
     * @param integer $type  type of the article
     * @return interger count of the records
     */
    function checkArticle($title, $type) {
        $specialChars = array("@", "%", "|", ",", "'", "\"", "#", "!", "*", "&", "(", ")", "?", "^", "`", "~", "<", ">");
        $title = str_replace($specialChars, "", $title);
        if ($type == 'website') {
            $this->sql = "SELECT count(*) as cnt from tbl_articles where `title`  LIKE '$title'";
        } else if ($type == 'twitter') {
            $this->sql = "SELECT count(*) as cnt from tbl_articles where `description` LIKE '$title'";
        } else {//if($type=='facebook'){
            $this->sql = "SELECT count(*) as cnt from tbl_articles where `description` LIKE '$title'";
        }

        $this->rs = $this->conn->executeQuery($this->sql) or die(mysql_error());

        return $this->rs[0]['cnt'];
    }

    /**
     * checkFbTweet
     * @param string  $title message of tweet/post
     * @param integer $type type of the article
     * @param string $iamge name of image
     * @return integer count of records
     */
    function checkFbTweet($title, $type, $image = '') {

        if ($title != "")
            $this->sql = "SELECT count(*) as cnt from tbl_articles where `description` LIKE '$title'";
        else
            $this->sql = "SELECT count(*) as cnt from tbl_articles where `photo` = '$image'";

        $this->rs = $this->conn->executeQuery($this->sql) or die(mysql_error());

        return $this->rs[0]['cnt'];
    }

    /**
     * getRelatedVideoNames
     * @param string  $string title of the vedio
     * @return array list of video titles
     */
    function getRelatedVideosNames($string) {
        $objectFanwires = new Fanwires();
        if ($_SESSION['id']) {
            $limit = 6;
        } else {
            $limit = 10;
        }
        $this->sql = "SELECT DISTINCT id, title,thumbnail,whomItBelongsTo from tbl_videos where released_date!='0000-00-00 00:00:00' and `title` NOT LIKE '$string' and `source` in ('0','3') and visible='1' ORDER BY RAND() ASC LIMIT 0," . $limit;
        $this->rs = $this->conn->selectSql($this->sql, array());
        $i = 0;
        foreach ($this->rs as $value) {
            $data[$i]['id'] = $value['id'];
            $data[$i]['title'] = $value['title'];
            $data[$i]['photo'] = $value['thumbnail'];
            $data[$i]['fanwirename'] = $objectFanwires->getFanwireName($value['whomItBelongsTo']);
            $title = str_replace("'", "", $value['title']);
            $title = str_replace(" ", "-", $title);
            $title = str_replace(",", "-", $title);
            $title = str_replace(".", "-", $title);
            $title = str_replace("(", "-", $title);
            $title = str_replace(")", "-", $title);
            $title = str_replace('"', '-', $title);
            $title = str_replace('#', '', $title);
            $title = str_replace('&', '', $title);
            $data[$i]['title_link'] = SITE_URL . $objectFanwires->getFanwireUrl($value['whomItBelongsTo']) . "/Videos/" . $title;
            list($width, $height) = getimagesize(IMAGE_URL . $data[$i]['photo']);
            $data[$i]['width'] = 40;
            $data[$i]['height'] = floor(($height / $width) * 40);
            $i++;
        }
        return $data;
    }

    /**
     * addArticlesFromWebsite
     * @param object $data
     */
    function addArticleFromWebsite($data) {
        extract($data);
        preg_match('/(w.)(.*)com/', $string, $link);
        if (empty($ItemDesc))
            $ItemDesc = $desc;

        $getDate = date("Y-m-d H:i:s", strtotime(trim(str_replace('ET', '', str_replace('Posted ', '', $getDate)))));
        $specialChars = array("@", "%", "|", ",", "'", "\"", "#", "!", "*", "&", "(", ")", "?", "^", "`", "~", "<", ">");

        $this->sql = "insert into tbl_articles(`user_id`,`title`,`description`,`photo`,`cdate`,`article_source`,`article_link`,`type_source`,`belongsTo`,`article_from`,`visible`,`source`) values(?,?,?,?,?,?,?,?,'2',?,?,?)";
        $data = array($fanwireid, str_replace($specialChars, "", $tit), $ItemDesc, $getreviewImageL, $getDate, $getAuthor, $url, $websiteCrawlname, $articleFrom, '0', '3');
        $this->conn->executePrepared($this->sql, $data) or die(mysql_error());
        $this->conn1->executePrepared($this->sql, $data) or die(mysql_error());
    }

    /**
     * addTweetFromTwitter
     * @param object $data
     */
    function addTweetsFromTwitter($data) {
        extract($data);
        $this->sql = "insert into tbl_articles(`user_id`,`title`,`description`,`photo`,`cdate`,`article_source`,`belongsTo`,`source`,`article_from`,`released_date`) values(?,?,?,?,?,?,'2','2',?,?)";
        $data = array($data['id'], $data['name'], $data['tweet'], $data['imagename'], date("Y-m-d H:i:s", strtotime($data['time'])), "http://www.twitter.com", "Twitter", date("Y-m-d H:i:s", strtotime($data['time'])));
        $this->conn->executePrepared($this->sql, $data);
        $this->conn1->executePrepared($this->sql, $data);
    }

    /**
     * addPostsFromFaceBook
     * @param object $data
     */
    function addPostsFromFaceBook($data) {
        $this->sql = "insert into tbl_articles(`user_id`,`title`,`description`,`photo`,`cdate`,`article_source`,`belongsTo`,`source`,`article_from`,`released_date`) values(?,?,?,?,?,?,'2','1',?,?)";
        $data = array($data['id'], $data['name'], $data['message'], $data['imagename'], date("Y-m-d H:i:s", strtotime($data['time'])), "http://www.facebook.com", "Facebook", date("Y-m-d H:i:s", strtotime($data['time'])));
        $this->conn->executePrepared($this->sql, $data) or die(mysql_error());
        $this->conn1->executePrepared($this->sql, $data) or die(mysql_error());
    }

    /**
     * updateDate
     * @param object $params
     */
    function updateDate($params,$cdate1) {
        extract($params);
        $this->sql = "update tbl_articles set cdate=? where id=?";
        //$data = array(date("Y-m-d H:i:s", strtotime($cdate)), $article_id);
        $data = array($cdate1, $article_id);
        $this->conn->executePrepared($this->sql, $data);
    }

    /**
     * updateReleasedDate
     * @param object $params
     */
    function updateReleasedDate($params,$dt_release) {
        extract($params);
        $this->sql = "update tbl_articles set released_date=?,visible='1' where id=?";
        //$data = array(date("Y-m-d H:i:s", strtotime($released_date)), $article_id);
        $data = array($dt_release, $article_id);
        $this->conn->executePrepared($this->sql, $data);
    }

    /**
     * updateReleagetArticleImagessedDate
     * @param integer $aid
     * @return object
     */
    function getArticleImages($aid) {
        $this->sql = "SELECT * from tbl_photos where article_id=?";
        return $this->rs = $this->conn->selectSql($this->sql, array($aid));
    }

    /**
     * editArticle
     * @param integer $params
     * @param array $dir_array
     * @return object
     */
    function editArticle($params, $dir_array) {
        extract($params);
        $this->sql = "update tbl_articles set title=?,description=?,released_date=?,photo=? where id=?";
        $data = array($articlename, $description, date("Y-m-d H:i:s", strtotime($released_date)), $cover_image, $id);
        $this->conn->executePrepared($this->sql, $data);
        $obj = new Fanwires();
        foreach ($dir_array as $val) {
            $p = explode("_", $val);
            $q = explode('.', $p[1]);
            $this->sql = "insert into tbl_photos(`name`,userid,article_id,status,caption) values(?,?,?,?,?)";
            $data = array($val, $_SESSION['adminid'], $id, '1', $q[0]);
            $this->conn->executePrepared($this->sql, $data);
        }

        if (isset($keyword) && $keyword != "") {
            $keys = explode(",", $keyword);
            foreach ($keys as $val) {
                $this->sql = "insert into tbl_keywords(`keyword`,`referer_id`,`type`) values(?,?,?)";
                $data = array($val, $id, '3');
                $this->conn->executePrepared($this->sql, $data) or die(mysql_error());
            }
        }

        if (isset($fanwire) && $fanwire != "") {
            $fans = explode(",", $fanwire);
            foreach ($fans as $value) {
                $fan_id = $obj->getFanwireIdByName($value);
                $this->sql = "insert into tbl_additional_fanwires(`fanwire_id`,`content_id`,`content_type`) values(?,?,?)";
                $data = array($fan_id, $id, '1');
                $this->conn->executePrepared($this->sql, $data) or die(mysql_error());
            }
        }
    }

    /**
     * getFacebookList
     * @param integer $fanwire_id
     * @param integer $pageSize
     * @return object
     */
    public function getFacebookList($fanwire_id, $pageSize = 100) {
        $this->sql = "SELECT * from tbl_articles where belongsTo=2 and source='1' and visible='1' and user_id=? order by id desc";
        $pageObject = new Pagination($pageSize, 9); //params: pagesize, scroll size
        $navigation = $pageObject->showNavigation(sizeof($this->conn->executeQuery($this->sql)));
        $this->sql .= $pageObject->getLimitQry();
        $this->rs = $this->conn->selectSql($this->sql, array($fanwire_id));
        $i = 0;
        $obj = new Fanwires();
        foreach ($this->rs as $val) {
            $this->data[$i]['id'] = $val['id'];
            $this->data[$i]['title'] = $val['title'];
            $this->data[$i]['description'] = $val['description'];
            $this->data[$i]['photo'] = $val['photo'];
            $this->data[$i]['fanwire'] = $obj->getFanwireName($val['user_id']);
            $this->data[$i]['cdate'] = $val['cdate'];
            $this->data[$i]['released_date'] = $val['released_date'];
            $i++;
        }
        $this->data['list'] = $this->data;
        $this->data['navigation'] = $navigation;
        return $this->data;
    }

    /**
     * getTwitterList
     * @param integer $fanwire_id
     * @param integer $pageSize
     * @return object
     */
    public function getTwitterList($fanwire_id, $pageSize = 100) {
        $obj = new Fanwires();
        $this->data = array();
        $this->sql = "SELECT * from tbl_articles where belongsTo=2 and visible='1' and source='2' and user_id=? order by cdate desc";
        $pageObject = new Pagination($pageSize, 9); //params: pagesize, scroll size
        $navigation = $pageObject->showNavigation(sizeof($this->conn->executeQuery($this->sql)));
        $this->sql .= $pageObject->getLimitQry();
        $this->rs = $this->conn->selectSql($this->sql, array($fanwire_id));
        $i = 0;
        foreach ($this->rs as $val) {
            $this->data[$i]['id'] = $val['id'];
            $this->data[$i]['title'] = $val['title'];
            $tt = YoutubeUrl::replace_plain_text_link(($val['description']));
            $this->data[$i]['description'] = preg_replace('/(#\w+)/', '<span class=\'twitcolor\'>\1</span>', $tt);
            $this->data[$i]['photo'] = $val['photo'];
            $this->data[$i]['fanwire'] = $obj->getFanwireName($val['user_id']);
            $this->data[$i]['cdate'] = DatePicker::getTimeStamp($val['cdate']);
            $this->data[$i]['released_date'] = $val['released_date'];
            $i++;
        }
        $this->data['list'] = $this->data;
        $this->data['navigation'] = $navigation;
        return $this->data;
    }

    /**
     * deleteArticles
     * @param integer $aid
     */
    public function deleteArticles($aid) {
        $this->sql = "update tbl_articles set visible='2' where id in($aid)";
        $this->conn->executePrepared($this->sql, array());
    }

    /**
     * releaseArticles
     * @param integer $aid
     */
    public function releaseArticles($aid) {
        $this->sql = "update tbl_articles set released_date=?,visible='1' where id in ($aid)";
        $this->conn->executePrepared($this->sql, array(date("Y-m-d H:i")));
    }

    /**
     * insertStatus
     * @param integer $id
     * @param integer $source
     */
    function insertStatus($id, $source) {
        $this->sql = "insert into  tbl_crawl_history(`crawl_id`,`crawl_source`) values(?,?)";
        $data = array($id, $source);
        $this->conn->executePrepared($this->sql, $data);
    }

    /**
     * checkStatus
     * @param integer $id
     * @param integer $source
     */
    function checkStatus($id, $source) {
        $this->sql = "select count(*) as cou from tbl_crawl_history where crawl_id=$id and crawl_source='$source'";
        $data = array($id, $source);
        $res = $this->conn->executeQuery($this->sql);
        return $res[0]['cou'];
    }

    /**
     * updateStatus
     * @param integer $id
     * @param integer $source
     */
    function updateStatus($id, $source) {
        $date = date('Y-m-d H:i:s');
        $this->sql = "update tbl_crawl_history SET last_crawl='$date' WHERE crawl_id=$id and crawl_source='$source'";
        $this->conn->executeUpdate($this->sql);
    }

    /**
     * curl_get_file_contents
     * @param string $URL url
     * @return object
     */
    public function curl_get_file_contents($URL) {
        $c = curl_init();
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_URL, $URL);
        $contents = curl_exec($c);
        $err = curl_getinfo($c, CURLINFO_HTTP_CODE);
        curl_close($c);
        if ($contents)
            return $contents;
        else
            return FALSE;
    }

    /**
     *
     * @param type $aid
     * @return type
     */
    public function getAllArticles() {
        $this->sql = "SELECT * from tbl_articles where visible='0'";
        $this->rs = $this->conn->selectSql($this->sql);
        $i = 0;
        $objUser = new Users();
        $obj = new Fanwires();
        foreach ($this->rs as $val) {
            $this->data[$i]['id'] = $val['id'];
            $this->data[$i]['user_id'] = $val['user_id'];
            $this->data[$i]['title'] = ($val['title']);
            $this->data[$i]['fanwireName'] = $obj->getFanwireName($val['user_id']);
            //$this->data[$i]['description'] = stripslashes($val['description']);
            //$this->data[$i]['photo'] = $val['photo'];
            //$this->data[$i]['published_date'] = $val['published_date'];
            //$this->data[$i]['article_source'] = $val['article_source'];
            //$this->data[$i]['article_link'] = $val['article_link'];
            //$sub = explode('.', $val['article_link']);
            //$this->data[$i]['article_sub_link'] = $sub['0'] . '.' . $sub['1'] . '.' . end(array_reverse(explode('/', $sub['2'])));
            //$this->data[$i]['type_source'] = $val['type_source'];
            //$this->data[$i]['article_from'] = $val['article_from'];
            //$this->data[$i]['cdate'] = DatePicker::getTimeStamp($val['cdate']);
            //$this->data[$i]['date'] = date("m/d/y",strtotime($val['cdate']));
            //$arr = explode(",", $objUser->getLikesCount($val['id'], ART_TYPE));
            //$this->data[$i]['like'] = $arr[0];
            //$this->data[$i]['source'] = $val['source'];
            //$this->data[$i]['dislike'] = $arr[1];
            $this->data[$i]['released_date'] = $val['released_date'];
            //$this->data[$i]['artstatus'] = $obj->getCollectedStatus($val['id'], ART_TYPE);
            $this->data[$i]['keywords'] = $obj->getKeywords($val['user_id'], '4');
            //$this->data[$i]['additional_fans'] = $obj->getAdditionalFans($val['id'], '1');


            $i++;
        }
        return $this->data;
    }

}
