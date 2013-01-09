<?php

/**
 * Description of Company
 * @author gangaraju
 */
class Fanwires {

    private $conn = null;
    private $sql = null;
    private $rs = null;
    private $data = array();

    /**
     * creating dao object
     */
    function __construct() {

        $this->conn = Dao::getInstance();
    }

    /**
     * get fanwires list
     * @param type $pageSize
     * @return type
     */
    public function getFanwiresList($sort = "", $column, $search, $pageSize = 100) {
        $data = array();
        if ($search != "")
            $where = " and name like '$search%' ";
        else
            $where = "";
        if (isset($sort) && $sort != "" && $column == 'name')
            $orderby = "order by name " . $sort;
        else if (isset($sort) && $sort != "" && $column == 'date')
            $orderby = "order by last_updated " . $sort;
        else
            $orderby = "order by name asc";
        $i = 0;
        $this->sql = "SELECT * from tbl_fanwires where status =1 " . $where . $orderby;
        $pageObject = new Pagination($pageSize, 9); //params: pagesize, scroll size
        $navigation = $pageObject->showNavigation(sizeof($this->conn->executeQuery($this->sql)));
        $this->sql .= $pageObject->getLimitQry();
        $this->rs = $this->conn->executeQuery($this->sql);
        foreach ($this->rs as $key => $value) {
            $data[$i]['id'] = $value['id'];
            $data[$i]['name'] = $value['name'];
            $category = $this->getFanwireCategories($value['id']);
            $data[$i]['category1'] = $category[0]['name'];
            $data[$i]['category2'] = $category[1]['name'];
            $data[$i]['keywords'] = $this->getKeywords($value['id'], '4');
            $data[$i]['addedby'] = $this->getUserName($value['addedby']);
            $data[$i]['status'] = $value['video'];
            $data[$i]['released_date'] = $value['released_date'];
            $data[$i]['last_updated'] = $value['last_updated'];
            $i++;
        }
        $data['list'] = $data;
        $data['navigation'] = $navigation;
        return $data;
    }

    //destroying memory
    function __destruct() {
        unset($this->conn);
        unset($this->rs);
        unset($this->sql);
        unset($this->data);
    }

    /**
     * add fanwires
     * @param type $params
     * @param type $photoname
     * @param type $videoname
     * @return type
     */
    public function addFanwires($params) {
        $addedby = $_SESSION['adminid'];
        extract($params);
        // @copy(DOC_ROOT_PATH . "/tmp/" . $photo, DOC_ROOT_PATH . "/photos/" . $photo);
        // @copy(DOC_ROOT_PATH . "/tmp/thumb_" . $photo, DOC_ROOT_PATH . "/photos/thumbs/" . $photo);
        //save images in S3
        $amazonObject = StorageFactory::getObject();
        $amazonObject->saveFile(DOC_ROOT_PATH . "/tmp/" . $photo, "photos/" . $photo);
        @unlink(DOC_ROOT_PATH . "/tmp/" . $photo);
        @unlink(DOC_ROOT_PATH . "/tmp/thumb_" . $photo);

        $this->sql = "insert into tbl_fanwires(`name`,`url`,`description`,`facebook`,`twitter`,`instagram`,`youtube`,`addedby`,`released_date`) values(?,?,?,?,?,?,?,?,?)";
        $data = array($name, $url, $description, $facebook, $twitter, $instagram, $youtube, $addedby, $released);
        $this->conn->executePrepared($this->sql, $data) or die(mysql_error());
        $fanwire_id = $this->conn->getInsertId();

        $cat = explode(",", $categories);
        foreach ($cat as $v) {
            $arr = explode("N", $v);
            $maincat = $arr[0];
            $subcat = $arr[1];
            $this->sql = "insert into tbl_fanwire_categories(fanwire_id,category,subcategory) values(?,?,?)";
            $this->conn->executePrepared($this->sql, array($fanwire_id, $maincat, $subcat));
        }


        $keys = explode(",", $keyword);
        foreach ($keys as $val) {
            $this->sql = "insert into tbl_keywords(`keyword`,`referer_id`,`type`) values(?,?,?)";
            $data = array($val, $fanwire_id, 4);
            $this->conn->executePrepared($this->sql, $data) or die(mysql_error());
        }
        $labels = explode(",", $hiddenlabel);

        foreach ($labels as $labelkey => $labelval) {
            if ($labelval != "") {
                $this->sql = "insert into tbl_fields(`name`,`fanwire_id`,`url`) values(?,?,?)";
                $data = array($labelval, $fanwire_id, $labelfield[$labelkey]);
                $this->conn->executePrepared($this->sql, $data) or die(mysql_error());
            }
        }
        if (isset($avatar)) {
            $this->sql = "insert into tbl_avatar_photos(`url`,`fanwire_id`,`main_avator`) values(?,?,?)";
            $data = array($photo, $fanwire_id, '1');
            $this->conn->executePrepared($this->sql, $data) or die(mysql_error());
        }
    }

    /**
     * get categories
     * @return type
     */
    public function getCategories($parentid = 0) {
        $i = 0;
        $data = array();
        $this->sql = "SELECT * from tbl_category where parentid=?";
        $this->rs = $this->conn->selectSql($this->sql, array($parentid));
        foreach ($this->rs as $key => $value) {
            $data[$i]['id'] = $value['id'];
            $data[$i]['name'] = $value['name'];
            $i++;
        }
        return $data;
    }

    /**
     * get photos list
     * @param type $pageSize
     * @return type
     */
    public function getPhotosList($pageSize = 10) {

        $this->sql = "SELECT * from tbl_photos";
        $pageObject = new Pagination($pageSize, 9); //params: pagesize, scroll size
        $navigation = $pageObject->showNavigation(sizeof($this->conn->executeQuery($this->sql)));
        $this->sql .= $pageObject->getLimitQry();
        $this->rs = $this->conn->executeQuery($this->sql);
        $data['list'] = $this->rs;
        $data['navigation'] = $navigation;
        return $data;
    }

    /**
     * get videos list
     * @param type $pageSize
     * @return type
     */
    public function getVideosList($pageSize = 10) {

        $this->sql = "SELECT * from tbl_videos where visible='1'";
        $pageObject = new Pagination($pageSize, 9); //params: pagesize, scroll size
        $navigation = $pageObject->showNavigation(sizeof($this->conn->executeQuery($this->sql)));
        $this->sql .= $pageObject->getLimitQry();
        $this->rs = $this->conn->executeQuery($this->sql);
        $i = 0;
        foreach ($this->rs as $val) {
            $this->data[$i]['id'] = $val['id'];
            $this->data[$i]['title'] = $val['title'];
            $this->data[$i]['description'] = $val['description'];
            $this->data[$i]['video'] = $val['video'];
            $this->data[$i]['user'] = $this->getUserName($val['userid']);
            $i++;
        }

        $data['list'] = $this->data;
        $data['navigation'] = $navigation;
        return $data;
    }

    /**
     * delete photo
     * @param type $id
     * @return type
     */
    public function deletePhoto($id) {
        $this->sql = "SELECT * from tbl_photos where id=?";
        $this->rs = $this->conn->selectSql($this->sql, array($id));
        $name = $this->rs[0]['name'];

        $s3object = StorageFactory::getObject();
        $s3object->deleteFile(IMAGE_URL . $name);

        @unlink(DOC_ROOT_PATH . "/photos/" . $name);

        $this->sql = "delete from tbl_photos where id=?";
        $data = array($id);
        return $this->conn->executePrepared($this->sql, $data) or die(mysql_error());
    }

    /**
     * delte video
     * @param type $id
     * @return type
     */
    public function deleteVideo($id) {
        $this->sql = "update tbl_videos set visible='0' where id=?";
        $data = array($id);
        return $this->conn->executePrepared($this->sql, $data) or die(mysql_error());
    }

    /**
     * delete fanwire
     * @param type $id
     * @param type $status
     * @return type
     */
    public function deleteFanwire($id, $status) {
        $this->sql = "update tbl_fanwires set status=? where id=?";
        $data = array($status, $id);
        return $this->conn->executePrepared($this->sql, $data) or die(mysql_error());
    }

    /**
     * change the status of fanwire
     * @param type $id
     * @param type $view_status
     * @return type
     */
    public function viewFanwire($id, $view_status) {
        if ($view_status == 1)
            $view_status = 2;
        else
            $view_status = 1;
        $this->sql = "update tbl_fanwires set view_status=? where id=?";
        $data = array($view_status, $id);
        return $this->conn->executePrepared($this->sql, $data) or die(mysql_error());
    }

    /**
     * get data from session and display in history popup
     */
    public function getHistory() {
        $arr = explode("/", $_SERVER['HTTP_REFERER']);
        $page = $arr[4];
        $var = $_SERVER['HTTP_REFERER'];
        $session_array = array();
        foreach ($_SESSION['history'] as $key => $val) {
            if ($key != "" && $val != "")
                $session_array[$key] = $val;
        }

        if ($var != "" && $var != "http://" . $_SERVER['HTTP_HOST'] . "/fanwire/")
            $session_array[$page] = $var;

        $_SESSION['history'] = array_unique($session_array);
    }

    /**
     *
     * @param type $id
     * @param type $album
     * @param type $dir_array
     * @return type
     */
    public function savePhotos($id, $album, $dir_array, $cover_image, $sta, $caption) {
        $this->sql = "insert into tbl_albums(`name`,`album_url`,`pid`,`status`) values(?,?,?,?)";
        $data = array($album, $cover_image, $_SESSION['id'], $sta);
        $this->conn->executePrepared($this->sql, $data) or die(mysql_error());
        $albumid = $this->conn->getInsertId();
        foreach ($dir_array as $val) {
            $p = explode("_", $val);
            $q = explode('.', $p[1]);
            $this->sql = "insert into tbl_photos(`name`,userid,album_id,status,caption) values(?,?,?,?,?)";
            $data = array($val, $id, $albumid, $sta, $q[0]);
            $this->conn->executePrepared($this->sql, $data) or die(mysql_error());
        }
        return $albumid;
    }

    /**
     *
     * @param type $album_id
     * @param type $status
     * @return type
     */
    public function updateAlbumStatus($album_id, $status) {
        $this->sql = "update tbl_albums set status=? where id=?";
        $data = array($status, $album_id);
        $this->conn->executePrepared($this->sql, $data) or die(mysql_error());

        $this->sql = "update tbl_photos set status=? where album_id=?";
        $data = array($status, $album_id);
        $this->conn->executePrepared($this->sql, $data) or die(mysql_error());
    }

    /**
     *
     * @param type $title
     * @param type $description
     * @param type $videoname
     * @param type $thumbnail
     * @param type $type
     * @param type $userid
     */
    public function uploadVideos($title, $description, $videoname, $thumbnail, $type, $userid) {
        $this->sql = "insert into tbl_videos(title,description,video,thumbnail,type,userid) values(?,?,?,?,?,?)";
        $data = array($title, $description, mysql_escape_string($videoname), $thumbnail, $type, $userid);
        $this->conn->executePrepared($this->sql, $data) or die(mysql_error());
        return $this->conn->getInsertId();
    }

    /**
     *
     * @param type $video_id
     * @param type $status
     * @return type
     */
    public function updateVideoStatus($video_id, $status) {
        $this->sql = "update tbl_videos set status=? where id=?";
        $data = array($status, $video_id);
        return $this->conn->executePrepared($this->sql, $data) or die(mysql_error());
    }

    /**
     *
     * @param type $user_id
     * @param type $media_id
     * @param type $type
     * @return type
     */
    public function updateFanNetwork($user_id, $media_id, $type) {
        $this->sql = "insert into tbl_fannetwork(user_id,media_id,type) values(?,?,?)";
        $data = array($user_id, $media_id, $type);
        return $this->conn->executePrepared($this->sql, $data) or die(mysql_error());
    }

    /**
     *
     * @param type $vid
     * @return type
     */
    public function getVideoDetails($vid) {
        $this->sql = "SELECT * from tbl_videos where id=?";
        return $this->rs = $this->conn->selectSql($this->sql, array($vid));
    }

    /**
     *
     * @param type $aid
     * @return type
     */
    public function getAlbumDetails($aid) {
        $this->sql = "SELECT p.id,p.name as image_url,a.id as album_id,a.name FROM  tbl_photos p,tbl_albums a where p.album_id=a.id and a.id=? ";
        return $this->rs = $this->conn->selectSql($this->sql, array($aid));
    }

    /**
     *
     * @param type $aid
     * @return type
     */
    public function getAlbums() {
        $this->sql = "SELECT p.name as image_url,a.name FROM  tbl_photos p,tbl_albums a where p.album_id=a.id ";
        return $this->rs = $this->conn->selectSql($this->sql, array());
    }

    /**
     *
     * @param type $aid
     * @return type
     */
    public function getAlbumsList($pageSize = 50) {
        $this->sql = "select a.id,a.name,p.name as image_url from tbl_albums a left join tbl_photos p on a.id=p.album_id group by a.id";
        $pageObject = new Pagination($pageSize, 9); //params: pagesize, scroll size
        $navigation = $pageObject->showNavigation(sizeof($this->conn->executeQuery($this->sql)));
        $this->sql .= $pageObject->getLimitQry();
        $this->rs = $this->conn->executeQuery($this->sql);
        $data['list'] = $this->rs;
        $data['navigation'] = $navigation;
        return $data;
    }

    /**
     *
     * @param type $aid
     * @return type
     */
    public function getAlbumName($aid) {
        $this->sql = "SELECT name from tbl_albums where id=?";
        $this->rs = $this->conn->selectSql($this->sql, array($aid));
        return $this->rs[0]['name'];
    }

    /**
     *
     * @param type $aid
     */
    public function deleteAlbum($aid) {
        $this->sql = "update tbl_albums set visible='0' where id=?";
        $data = array($aid);
        $this->conn->executePrepared($this->sql, $data) or die(mysql_error());
    }

    /**
     *
     * @param type $aid
     */
    public function deleteArticle($aid) {
        $this->sql = "SELECT * from tbl_photos where article_id=?";
        $this->rs = $this->conn->selectSql($this->sql, array($aid));
        foreach ($this->rs as $val) {
            $name = $val['name'];
            @unlink(DOC_ROOT_PATH . "/photos/" . $name);
            @unlink(DOC_ROOT_PATH . "/photos/thumbs/" . $name);
        }

        $this->sql = "delete from tbl_photos where article_id=?";
        $data = array($aid);
        $this->conn->executePrepared($this->sql, $data) or die(mysql_error());

        $this->sql = "update tbl_articles set visible='0' where id=?";
        $data = array($aid);
        $this->conn->executePrepared($this->sql, $data) or die(mysql_error());
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
     *
     * @param type $to
     * @param type $from
     * @param type $message
     * @return type
     */
    public function sendMessage($to, $from, $message) {
        $date = date("Y-m-d H:i:s");
        $this->sql = "insert into tbl_messages(`to`,`from`,`message`,`date`) values(?,?,?,?)";
        $data = array($to, $from, $message, $date);
        return $this->conn->executePrepared($this->sql, $data) or die(mysql_error());
    }

    /**
     *
     * @param type $value
     * @param type $width
     * @return type array
     */
    public function saveZoomValues($page, $user_id, $value, $width, $height) {
        $this->sql = "select * from  tbl_zoom_values where user_id=? and page=?";
        $rs = $this->conn->selectSql($this->sql, array($user_id, $page));
        if ($value == '' && $width == '' && $height == '') {
            if (empty($rs)) {
                return 1;
            }
            return $rs;
            exit;
        }
        if (empty($rs)) {
            echo "insert";
            $this->sql1 = "insert into tbl_zoom_values(page,user_id,value,width,height) values(?,?,?,?,?)";
            $data = array($page, $user_id, $value, $width, $height);
            return $this->conn->executePrepared($this->sql1, $data) or die(mysql_error());
        } else {
            echo "update";
            $this->sql2 = "update tbl_zoom_values set page=?,value=?,width=?,height=? where user_id=? and page=?";
            $data = array($page, $value, $width, $height, $user_id, $page);
            return $this->conn->executePrepared($this->sql2, $data) or die(mysql_error());
        }
    }

    public function getMessages($userid, $start = 0) {
        $sql = "select `id`,`to`,`from`,`message`,`date`,`read` from tbl_messages where id in (select max(`id`) as id from tbl_messages where `to`=? group by `from` order by id desc ) order by id desc limit $start,5";
        $rs = $this->conn->selectSql($sql, array($userid));
        $data = array();
        $i = 0;
        foreach ($rs as $value) {
            $data[$i]['id'] = $value['id'];
            $data[$i]['user_id'] = $value['to'];
            $data[$i]['sender_id'] = $value['from'];
            $result = $this->getUserDetails($value['from']);
            $data[$i]['sender'] = $result[0]['fname'] . " " . $result[0]['lname'];
            $data[$i]['image'] = $result[0]['profile_image'];
            $data[$i]['message'] = $value['message'];
            $data[$i]['date'] = $value['date'];
            $data[$i]['status'] = $value['read'];
            $i++;
        }
        return $data;
    }

    /**
     *
     * @param type $userid
     * @return type
     */
    public function getUserDetails($userid) {
        $sql = "select * from tbl_users where id=?";
        $rs = $this->conn->selectSql($sql, array($userid));
        return $rs;
    }

    /**
     *
     * @param type $id
     */
    public function deleteMessage($senderid, $userid) {
        $this->sql = "delete from tbl_messages where (`from`=? and `to`=?) or (`from`=? and `to`=?)";
        $data = array($senderid, $userid, $userid, $senderid);
        return $this->conn->executePrepared($this->sql, $data) or die(mysql_error());
    }

    /**
     *
     * @param type $userid
     * @return type
     */
    public function getMessageCount($userid) {
        $sql = "select count(*) as cnt from tbl_messages where `to`=? group by `from`";
        $rs = $this->conn->selectSql($sql, array($userid));
        return sizeof($rs);
    }

    public function getConversation($userid, $senderid) {
        $sql = "select * from tbl_messages where (`to`=? and `from`=?) or (`to`=? and `from`=?) order by `id`";
        $rs = $this->conn->selectSql($sql, array($userid, $senderid, $senderid, $userid));
        $data = array();
        $i = 0;
        foreach ($rs as $value) {
            $data[$i]['id'] = $value['id'];
            $data[$i]['user_id'] = $value['to'];
            $data[$i]['sender_id'] = $value['from'];
            $result = $this->getUserDetails($value['from']);
            $data[$i]['sender'] = $result[0]['fname'] . " " . $result[0]['lname'];
            $data[$i]['image'] = $result[0]['profile_image'];
            $data[$i]['message'] = $value['message'];
            $data[$i]['date'] = $value['date'];
            $i++;
        }
        return $data;
    }

    /**
     * @param type $senderid
     * @param type $userid
     */
    public function msgRead($senderid, $userid) {
        $sql = "update tbl_messages set `read`='1' where (`to`=? and `from`=?) or (`to`=? and `from`=?)";
        $rs = $this->conn->selectSql($sql, array($userid, $senderid, $senderid, $userid));
    }

    /**
     *
     * @param type $fanwireid
     * @param type $comment
     * @param type $userid
     */
    public function sendComment($receiver, $comment, $userid, $receiver_type) {
        $this->sql1 = "insert into tbl_comments(receiver_id,comment,userid,receiver_type,comment_time) values(?,?,?,?,NOW())";
        $data = array($receiver, $comment, $userid, $receiver_type);
        $this->conn->executePrepared($this->sql1, $data) or die(mysql_error());
    }

    /**
     * checkCommentValidation
     * @param interger $receiver_id 
     * @param interger $userid 
     * @param interger $receiver_type
     * @return object  
     */
    public function checkCommentValidation($receiver_id, $userid, $receiver_type) {
        $sql = "SELECT * FROM `tbl_comments` WHERE `receiver_id`=? and `userid`=? and `receiver_type`=?  and comment_time < now() and comment_time > concat(curdate(),' 00:00:00 AM')";
        $rs = $this->conn->selectSql($sql, array($receiver_id, $userid, $receiver_type));
        return $rs;
    }

    /**
     * getFanwireId
     * @return integer 
     */
    public function getFanwireId() {
        $sql = "select id from tbl_fanwires order by id desc limit 0,1";
        $rs = $this->conn->selectSql($sql, array());
        return $rs[0]['id'] + 1;
    }

    /**
     * getAllFanwireIds
     * @return object 
     */
    public function getAllFanwireIds() {//for cron action
        $sql = "select id,facebook,twitter,youtube from tbl_fanwires order by id desc";
        $rs = $this->conn->selectSql($sql, array());
        return $rs;
    }

    /**
     * getKeywords
     * @param integer $fanwire_id 
     * @param integer $type 
     * @return object 
     */
    public function getKeywords($fanwire_id, $type) {
        $sql = "select id,keyword from tbl_keywords where referer_id=? and type=?";
        $rs = $this->conn->selectSql($sql, array($fanwire_id, $type));
        $data = array();
        $i = 0;
        foreach ($rs as $key => $value) {
            $data[$i]['id'] = $value['id'];
            $data[$i]['keyword'] = $value['keyword'];
            $i++;
        }
        return $data;
    }

    /**
     * getCategoryName
     * @param integer $catid 
     * @return string 
     */
    public function getCategoryName($catid) {
        $sql = "select name from tbl_category where id=?";
        $rs = $this->conn->selectSql($sql, array($catid));
        return $rs[0]['name'];
    }

    /**
     * retrieve fanwires
     * @param type $params
     * @param type $photoname
     * @param type $videoname
     * @return type
     */
    public function getFanwires($fanwireId) {
        $this->sql = "select * from tbl_fanwires where id=?";
        $rs = $this->conn->selectSql($this->sql, array($fanwireId));
        $data['fanwires'] = $rs[0];

        $this->sql = "select * from tbl_keywords where referer_id = ?";
        $rs = $this->conn->selectSql($this->sql, array($fanwireId));
        $data['keywords'] = $rs;

        $this->sql = "select fc.id,c.name from tbl_category c,tbl_fanwire_categories fc where c.id=fc.category and fc.fanwire_id= ? ";
        $rs = $this->conn->selectSql($this->sql, array($fanwireId));
        $data['categories'] = $rs;

        $this->sql = "select * from tbl_avatar_photos where fanwire_id = ? and main_avator like ?";
        //echo $this->sql.'....'.$fanwireId;
        $rs = $this->conn->selectSql($this->sql, array($fanwireId, '1'));
        $data['photos'] = $rs[0];

        $this->sql = "select * from tbl_fields where fanwire_id = ?";
        $rs = $this->conn->selectSql($this->sql, array($fanwireId));
        $data['fields'] = $rs;
        return $data;
    }

    /**
     * edit fanwires
     * @param type $params
     * @param type $photoname
     * @param type $videoname
     * @return type
     */
    public function editFanwires($params, $photoname = "") {
        $addedby = $_SESSION['adminid'];
        extract($params);

        if (isset($photo) && $photo != "") {
            //move edited image to S3
            $amazonObject = StorageFactory::getObject();
            $amazonObject->saveFile(DOC_ROOT_PATH . "/tmp/" . $photo, "photos/" . $photo);
            /*
              @copy(DOC_ROOT_PATH . "/tmp/" . $photo, DOC_ROOT_PATH . "/photos/" . $photo);
              @copy(DOC_ROOT_PATH . "/tmp/thumb_" . $photo, DOC_ROOT_PATH . "/photos/thumbs/" . $photo);
             */
            @unlink(DOC_ROOT_PATH . "/tmp/" . $photo);
            @unlink(DOC_ROOT_PATH . "/tmp/thumb_" . $photo);
            $photoname = $photo;
        }

        $this->sql = "update tbl_fanwires set `name`=?,`url`=?,`description`=?,`facebook`=?,`twitter`=?,`instagram`=?,`youtube`=?,`addedby`=? where id=?";
        $data = array($name, $url, $description, $facebook, $twitter, $instagram, $youtube, $addedby, $id);
        $this->conn->executePrepared($this->sql, $data) or die(mysql_error());

        $cat = explode(",", $categories);
        foreach ($cat as $v) {
            $arr = explode("N", $v);
            $maincat = $arr[0];
            $subcat = $arr[1];
            $this->sql = "insert into tbl_fanwire_categories(fanwire_id,category,subcategory) values(?,?,?)";
            $this->conn->executePrepared($this->sql, array($id, $maincat, $subcat));
        }

        $keys = explode(",", $keyword);
        foreach ($keys as $key => $keysVal) {
            if ($keysVal != "") {
                $this->sql = "select id from tbl_keywords where keyword=? and  referer_id = ?";
                $rs = $this->conn->selectSql($this->sql, array($keysVal, $id));
                if (empty($rs)) {
                    $this->sql = "insert into tbl_keywords(`keyword`,`referer_id`,`type`) values(?,?,?)";
                    $data = array($keysVal, $id, 4);
                    $this->conn->executePrepared($this->sql, $data) or die(mysql_error());
                }
            }
        }


        if ($photoname != "") {
            $this->sql = "insert into tbl_avatar_photos(`url`,`fanwire_id`,`main_avator`) values(?,?,?)";
            $data = array($photoname, $id, '1');
            $this->conn->executePrepared($this->sql, $data) or die(mysql_error());
            $photo_id = $this->conn->getInsertId();
            $this->sql = "update tbl_avatar_photos set main_avator = ? where fanwire_id = ? and id != ?";
            $rs = $this->conn->executePrepared($this->sql, array('0', $id, $photo_id));
        }

        for ($i = 0; $i < count($getfield); $i++) {
            $this->sql = "UPDATE tbl_fields set url=? where id=? and fanwire_id =?";
            $this->data = array($labelfield[$i], $getfield[$i], $id);
            unset($labelfield[$i]);
            $this->conn->executePrepared($this->sql, $this->data);
        }
        $newLabelArr = array();
        $j = 0;
        foreach ($labelfield as $key => $val) {
            $newLabelArr[$j] = $val;
        }
        $labels = explode(",", $hiddenlabel);

        //newly added fields are inserted
        if (!empty($newLabelArr)) {
            foreach ($newLabelArr as $key => $value) {
                if (!empty($value)) {
                    $this->sql = "INSERT INTO tbl_fields(fanwire_id,url,name) values (?,?,?)";
                    $this->data = array($id, $value, $labels[$key]);
                    $this->conn->executePrepared($this->sql, $this->data);
                }
            }
        }
    }

    /**
     * updateProfile
     * @param integer $id 
     * @param string $biography 
     * @return array 
     */
    public function updateProfile($id, $biography) {
        $this->sql = "update tbl_fanwires set description = ? where id = ?";
        $this->conn->executePrepared($this->sql, array($biography, $id));
    }

    /**
     * getBiography
     * @param integer $id 
     * @return array 
     */
    public function getBiography($id) {
        $this->sql = "select f.category1,f.name,f.description,ap.url as avatar_photo,tp.url as timeline_photo
from tbl_fanwires f left join tbl_avatar_photos ap on ap.fanwire_id = f.id left join tbl_timeline_photos tp on tp.fanwire_id = f.id 
 and tp.main_timeline='1' where f.id = ? and ap.main_avator='1'";
        $rs = $this->conn->selectSql($this->sql, array($id));
        $data = array();
        $i = 0;
        foreach ($rs as $value) {
            $data[$i]['name'] = $value['name'];
            $data[$i]['description'] = $value['description'];
            $data[$i]['avatar_photo'] = $value['avatar_photo'];
            list($width, $height) = getimagesize(IMAGE_URL . $data[$i]['avatar_photo']);
            $data[$i]['width'] = 143;
            $data[$i]['height'] = floor(($height / $width) * 143);
            $data[$i]['timeline_photo'] = $value['timeline_photo'];
            $data[$i]['category'] = $this->getCategoryName($value['category1']);
            $i++;
        }
        return $data;
    }

    /**
     * get_data
     */
    public function get_data($url) {
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    /**
     * insertFbPhotos
     * @param integer $fanwire_id 
     * @param string $filename
     * @param string $cover_filename
     */
    public function InsertFbPhotos($fanwire_id, $filename, $cover_filename) {
        $this->sql = "update tbl_avatar_photos set main_avator = ? where fanwire_id = ? ";
        $rs = $this->conn->executePrepared($this->sql, array('0', $fanwire_id));
        $this->sql = "update tbl_timeline_photos set main_timeline = ? where fanwire_id = ? ";
        $rs = $this->conn->executePrepared($this->sql, array('0', $fanwire_id));
        $this->sql = "INSERT INTO tbl_avatar_photos(`url`,`fanwire_id`,`main_avator`) values (?,?,?)";
        $this->conn->executePrepared($this->sql, array($filename, $fanwire_id, '1'));
        $this->sql = "INSERT INTO tbl_timeline_photos(`url`,`fanwire_id`,`main_timeline`) values (?,?,?)";
        $this->conn->executePrepared($this->sql, array($cover_filename, $fanwire_id, '1'));
    }

    /**
     * updateAvatarPhoto
     * @param string $filename 
     * @param integer $fanwire_id 
     */
    public function updateAvatarPhoto($filename, $fanwire_id) {
        $this->sql = "update tbl_avatar_photos set main_avator = ? where fanwire_id = ? ";
        $rs = $this->conn->executePrepared($this->sql, array('0', $fanwire_id));

        $this->sql = "INSERT INTO tbl_avatar_photos(`url`,`fanwire_id`,`main_avator`) values (?,?,?)";
        $this->conn->executePrepared($this->sql, array($filename, $fanwire_id, '1'));
    }

    /**
     * updateTimelinePhoto
     * @param string $cover_filename 
     * @param integer $fanwire_id 
     */
    public function updateTimelinePhoto($cover_filename, $fanwire_id) {
        $this->sql = "update tbl_timeline_photos set main_timeline = ? where fanwire_id = ? ";
        $rs = $this->conn->executePrepared($this->sql, array('0', $fanwire_id));

        $this->sql = "INSERT INTO tbl_timeline_photos(`url`,`fanwire_id`,`main_timeline`) values (?,?,?)";
        $this->conn->executePrepared($this->sql, array($cover_filename, $fanwire_id, '1'));
    }

    /**
     * updateTimelinePhotoUser
     * @param string $cover_filename 
     * @param integer $userid
     * @return object 
     */
    public function updateTimelinePhotoUser($cover_filename, $userid) {

        $this->sql = "update tbl_users set cover_photo = ? where id = ? ";
        $rs = $this->conn->executePrepared($this->sql, array($cover_filename, $userid));

        if ($rs == '1') {
            return $cover_filename;
        } else {
            return '';
        }
    }

    /**
     * updatePhotos
     * @param type $id
     * @param type $album
     * @param type $dir_array
     * @param type $cover_image
     * @param type $sta
     * @param type $caption
     * @param type $albumid
     * @return type
     */
    public function updatePhotos($id, $album, $dir_array, $cover_image, $sta, $caption, $albumid) {
        $this->sql = "update tbl_albums set `album_url`=?,`status`=? whwere id=?";
        $data = array($cover_image, $sta, $albumid);
        $this->conn->executePrepared($this->sql, $data) or die(mysql_error());
        $this->sql = "delete from tbl_photos where album_id = ? ";
        $rs = $this->conn->executePrepared($this->sql, array($albumid));
        foreach ($dir_array as $val) {
            $p = explode("_", $val);
            $q = explode('.', $p[1]);
            $this->sql = "insert into tbl_photos(`name`,userid,album_id,status,caption) values(?,?,?,?,?)";
            $data = array($val, $id, $albumid, $sta, $q[0]);
            $this->conn->executePrepared($this->sql, $data) or die(mysql_error());
            return $list = sortByOneKey($list, 'cdate', false);
        }
        return $albumid;
    }

    /**
     * getMasterDetails
     * @param integer $strlmt 
     * @param integer $endlmt 
     */
    public function getMasterDetails($strlmt = '', $endlmt = "") {
        $i = 0;
        $obj = new Users();
        if (isset($_SESSION['id']))
            $userid = $_SESSION['id'];
        else
            $userid = '';

        if ($userid != '') {
            $this->sql = "select fanid from tbl_fanwire_user where user_id=? and fan_status='1'"; // LIMIT ".$strlmt.",".$endlmt;
            $rs = $this->conn->selectSql($this->sql, array($userid));
        } else {
            $this->sql = "select id as fanid from tbl_fanwires where status='1' ";
            $rs = $this->conn->selectSql($this->sql, array());
        }
        $list = array();
        $i = 0;
        foreach ($rs as $val) {
            $fan_id = $val['fanid'];

            $this->sql0 = "select f.id,f.name,f.last_updated,f.released_date,f.description,a.url from tbl_fanwires f,tbl_avatar_photos a where f.id=? and f.status='1' and f.id=a.fanwire_id and a.main_avator='1' and f.released_date<=NOW()";
            $rs0 = $this->conn->selectSql($this->sql0, array($fan_id));
            foreach ($rs0 as $val0) {
                $list[$i]['id'] = $val0['id'];
                $list[$i]['name'] = $val0['name'];
                $list[$i]['title'] = "";
                $list[$i]['photo'] = $val0['url'];
                list($width, $height) = getimagesize(DOC_ROOT_PATH . "/photos/" . $val0['url']);
                $list[$i]['width'] = $width;
                $list[$i]['height'] = $height;
                $list[$i]['description'] = $val0['description'];
                $list[$i]['link'] = SITE_URL . "phots?fwrid=" . $val0['id'] . "&ac=1";
                $list[$i]['title_link'] = "";
                $list[$i]['type'] = '0';
                $arr = explode(",", $obj->getLikesCount($val0['id'], $list[$i]['type']));
                $list[$i]['like'] = $arr[0];
                $list[$i]['dislike'] = $arr[1];
                $list[$i]['commentcnt'] = $obj->getCommentCount($val0['id'], $list[$i]['type']);
                $list[$i]['status'] = $this->getCollectedStatus($val0['id'], $list[$i]['type']);
                $list[$i]['source'] = '';
                $list[$i]['comments_for_this_post'] = $obj->getComments($val0['id'], $list[$i]['type']);
                $list[$i]['cdate'] = $val0['released_date'];
                $time = DatePicker::getTimeStamp($val0['last_updated']);
                $list[$i]['time'] = $time;
                $i++;
            }
            $this->sql1 = "select id,cdate,released_date,name,album_url,status,pid from tbl_albums where belongsTo='2' and pid=? and visible='1' and released_date<=NOW()";
            $rs1 = $this->conn->selectSql($this->sql1, array($fan_id));
            foreach ($rs1 as $val1) {
                $list[$i]['id'] = $val1['id'];
                $list[$i]['name'] = $this->getFanwireName($val1['pid']);
                //$list[$i]['link'] = SITE_URL."profileInfo?fwrid=".$val1['pid']."&ac=1";
                $list[$i]['link'] = SITE_URL . "phots?fwrid=" . $val1['pid'] . "&ac=1";
                $list[$i]['title'] = $val1['name'];
                $list[$i]['photo'] = $val1['album_url'];
                list($width, $height) = getimagesize(DOC_ROOT_PATH . "/photos/" . $val1['album_url']);
                $list[$i]['width'] = $width;
                $list[$i]['height'] = $height;
                $list[$i]['description'] = '';
                $list[$i]['type'] = '1';
                $list[$i]['title_link'] = SITE_URL . "viewAlbum?aid=" . $val1['id'];
                $arr = explode(",", $obj->getLikesCount($val1['id'], $list[$i]['type']));
                $list[$i]['like'] = $arr[0];
                $list[$i]['dislike'] = $arr[1];
                $list[$i]['commentcnt'] = $obj->getCommentCount($val1['id'], $list[$i]['type']);
                $list[$i]['status'] = $this->getCollectedStatus($val1['id'], $list[$i]['type']);

                $list[$i]['source'] = '';
                $list[$i]['comments_for_this_post'] = $obj->getComments($val1['id'], $list[$i]['type']);
                $list[$i]['cdate'] = $val1['released_date'];
                $time = DatePicker::getTimeStamp($val1['cdate']);
                $list[$i]['time'] = $time;
                $i++;
            }
            $this->sql2 = "select id,cdate,released_date,article_from,title,description,photo,source,user_id from tbl_articles where visible='1' released_date!='0000-00-00 00:00:00' and  belongsTo='2' and user_id=? and visible='1' and released_date<=NOW()";
            $rs2 = $this->conn->selectSql($this->sql2, array($fan_id));
            foreach ($rs2 as $val2) {
                $list[$i]['id'] = $val2['id'];
                $list[$i]['name'] = $this->getFanwireName($val2['user_id']);
                $list[$i]['title'] = $val2['title'];
                if ($val2['source'] == '1') {

                    $list[$i]['title_link'] = SITE_URL . $this->getFanwireUrl($val2['user_id']) . "/Facebook/" . $val2['id'];
                } else {

                    $list[$i]['title_link'] = SITE_URL . $this->getFanwireUrl($val2['user_id']) . "/Articles/" . $title;
                }
                $list[$i]['photo'] = $val2['photo'];
                if ($val2['photo'] == "") {
                    $list[$i]['fanwire_profile_img'] = $this->getFanwireProfileImage($val2['user_id']);
                }
                list($width, $height) = getimagesize(DOC_ROOT_PATH . "/photos/" . $val2['photo']);
                $list[$i]['width'] = $width;
                $list[$i]['height'] = $height;
                $list[$i]['source'] = $val2['source'];
                $list[$i]['article_from'] = $val2['article_from'];
                $list[$i]['type'] = '2';
                if ($val2['source'] == 0)
                    $list[$i]['description'] = $val2['description'];
                else
                    $list[$i]['description'] = YoutubeUrl::replace_plain_text_link(($val2['description']));
                if ($list[$i]['article_from'] == "Twitter") {
                    $list[$i]['description'] = preg_replace('/(#\w+)/', '<span class=\'twitcolor\'>\1</span>', $list[$i]['description']);
                }
                $list[$i]['link'] = SITE_URL . "phots?fwrid=" . $val2['user_id'] . "&ac=1";
                $arr = explode(",", $obj->getLikesCount($val2['id'], $list[$i]['type']));
                $list[$i]['like'] = $arr[0];
                $list[$i]['dislike'] = $arr[1];
                $list[$i]['commentcnt'] = $obj->getCommentCount($val2['id'], $list[$i]['type']);
                $list[$i]['status'] = $this->getCollectedStatus($val2['id'], $list[$i]['type']);
                $list[$i]['comments_for_this_post'] = $obj->getComments($val2['id'], $list[$i]['type']);
                $list[$i]['cdate'] = $val2['cdate'];
                $time = DatePicker::getTimeStamp($val2['cdate']);
                $list[$i]['time'] = $time;
                $i++;
            }
            $this->sql3 = "select id,cdate,released_date,title,thumbnail,description,whomItBelongsTo from tbl_videos where released_date!='0000-00-00 00:00:00' and belongsTo='2' and whomItBelongsTo=? and visible='1' and released_date<=NOW()";
            $rs3 = $this->conn->selectSql($this->sql3, array($fan_id));
            foreach ($rs3 as $val3) {
                $list[$i]['id'] = $val3['id'];
                $list[$i]['name'] = $this->getFanwireName($val3['whomItBelongsTo']);
                $list[$i]['link'] = SITE_URL . "phots?fwrid=" . $val3['whomItBelongsTo'] . "&ac=1";
                $list[$i]['title'] = $val3['title'];
                $list[$i]['photo'] = $val3['thumbnail'];
                list($width, $height) = getimagesize(DOC_ROOT_PATH . "/photos/" . $val3['thumbnail']);
                $list[$i]['width'] = $width;
                $list[$i]['height'] = $height;
                $list[$i]['type'] = '3';
                $list[$i]['description'] = $val3['description'];
                $list[$i]['title_link'] = SITE_URL . "viewVideos?vid=" . $val3['id'];
                $arr = explode(",", $obj->getLikesCount($val3['id'], $list[$i]['type']));
                $list[$i]['like'] = $arr[0];
                $list[$i]['dislike'] = $arr[1];
                $list[$i]['commentcnt'] = $obj->getCommentCount($val3['id'], $list[$i]['type']);
                $list[$i]['status'] = $this->getCollectedStatus($val3['id'], $list[$i]['type']);
                $list[$i]['source'] = '';
                $list[$i]['comments_for_this_post'] = $obj->getComments($val3['id'], $list[$i]['type']);
                $list[$i]['cdate'] = $val3['cdate'];
                $time = DatePicker::getTimeStamp($val3['cdate']);
                $list[$i]['time'] = $time;
                $i++;
            }
        }

        function sortByOneKey(array $array, $key, $asc = true) {
            $result = array();

            $values = array();
            foreach ($array as $id => $value) {
                $values[$id] = isset($value[$key]) ? $value[$key] : '';
            }

            if ($asc) {
                asort($values);
            } else {
                arsort($values);
            }
            $i = 0;
            foreach ($values as $key => $value) {
                $result[$i] = $array[$key];
                $i++;
            }

            return $result;
        }

        return $list = sortByOneKey($list, 'time', false);
    }

    public function getMasterDetailsMore() {
        $i = 0;
        $obj = new Users();
        if (isset($_SESSION['id']))
            $userid = $_SESSION['id'];
        else
            $userid = '';

        if ($userid != '') {
            $this->sql = "select fanid from tbl_fanwire_user where user_id=? and fan_status='1'";
            $rs = $this->conn->selectSql($this->sql, array($userid));
        } else {
            $this->sql = "select id as fanid from tbl_fanwires where status='1'";
            $rs = $this->conn->selectSql($this->sql, array());
        }
        $list = array();
        $i = 0;
        foreach ($rs as $val) {
            $fan_id = $val['fanid'];
            $this->sql0 = "select f.id,f.name,f.last_updated,f.released_date,f.description,f.url as fanwire,a.url from tbl_fanwires f,tbl_avatar_photos a where f.id=? and f.status='1' and f.id=a.fanwire_id and a.main_avator='1'";
            $rs0 = $this->conn->selectSql($this->sql0, array($fan_id));
            foreach ($rs0 as $val0) {
                $list[$i]['id'] = $val0['id'];
                $list[$i]['name'] = $val0['name'];
                $list[$i]['fanwire'] = $val0['fanwire'];
                $list[$i]['title'] = "";
                $list[$i]['photo'] = $val0['url'];
                list($width, $height) = getimagesize(DOC_ROOT_PATH . "/photos/" . $val0['url']);
                $list[$i]['width'] = $width;
                $list[$i]['height'] = $height;
                $list[$i]['description'] = $val0['description'];
                // $list[$i]['link'] = SITE_URL."profileInfo?fwrid=".$val0['id']."&ac=1";
                $list[$i]['link'] = SITE_URL . "phots?fwrid=" . $val1['pid'] . "&ac=1";
                $list[$i]['title_link'] = "";
                $arr = explode(",", $obj->getLikesCount($val0['id'], '0'));
                $list[$i]['like'] = $arr[0];
                $list[$i]['dislike'] = $arr[1];
                $list[$i]['commentcnt'] = $obj->getCommentCount($val0['id'], '0');
                $list[$i]['status'] = $this->getCollectedStatus($val0['id'], '0');
                $list[$i]['type'] = '0';
                $list[$i]['source'] = '';
                $list[$i]['comments_for_this_post'] = $obj->getComments($val0['id'], '0');
                $list[$i]['cdate'] = $val0['last_updated'];
                $time = DatePicker::getTimeStamp($val0['last_updated']);
                $list[$i]['time'] = $time;
                $i++;
            }
        }

        function sortByOneKey1(array $array, $key, $asc = true) {
            $result = array();

            $values = array();
            foreach ($array as $id => $value) {
                $values[$id] = isset($value[$key]) ? $value[$key] : '';
            }

            if ($asc) {
                asort($values);
            } else {
                arsort($values);
            }
            $i = 0;
            foreach ($values as $key => $value) {
                $result[$i] = $array[$key];
                $i++;
            }

            return $result;
        }

        return $list = sortByOneKey1($list, 'name', true);
    }

    /**
     * InsertTwitterPhotos
     * @param integer $fanwire_id 
     * @param string $filename 
     */
    public function InsertTwitterPhotos($fanwire_id, $filename) {
        $this->sql = "update tbl_avatar_photos set main_avator = ? where fanwire_id = ? ";
        $rs = $this->conn->executePrepared($this->sql, array('0', $fanwire_id));
        $this->sql = "INSERT INTO tbl_avatar_photos(`url`,`fanwire_id`,`main_avator`) values (?,?,?)";
        $this->conn->executePrepared($this->sql, array($filename, $fanwire_id, '1'));
    }

    /**
     * getCollectedStatus
     * @param integer $id 
     * @param integer $type
     * @return integer count
     */
    public function getCollectedStatus($id, $type) {

        $this->sql = "select count(*) as count from tbl_collections where user_id=? and fanwire_id=? and type='$type' and status='1'";
        $this->rs = $this->conn->selectSql($this->sql, array($_SESSION['id'], $id));
        return $this->rs[0]['count'];
    }

    /**
     * getFanwireName
     * @param integer $fanwire_id
     * @return string  name
     */
    public function getFanwireName($fanwire_id) {
        $sql = "select name from tbl_fanwires where id=?";
        $rs = $this->conn->selectSql($sql, array($fanwire_id));
        return $rs[0]['name'];
    }

    /**
     * getFanwireProfileImage
     * @param integer $fanwire_id
     * @return string  url
     */
    public function getFanwireProfileImage($fanwire_id) {
        $sql = "select url from tbl_avatar_photos where fanwire_id=? and main_avator='1'";
        $rs = $this->conn->selectSql($sql, array($fanwire_id));
        return $rs[0]['url'];
    }

    /**
     * updateDate
     * @param object $params
     */
    function updateDate($params) {
        extract($params);
        $this->sql = "update tbl_fanwires set last_updated=? where id=?";
        $data = array(date("Y-m-d H:i:s", strtotime($cdate)), $id);
        $this->conn->executePrepared($this->sql, $data);
    }

    /**
     * updateReleasedDate
     * @param object $params 
     */
    function updateReleasedDate($params) {
        extract($params);
        $this->sql = "update tbl_fanwires set released_date=? where id=?";
        $data = array(date("Y-m-d H:i:s", strtotime($released_date)), $id);
        $this->conn->executePrepared($this->sql, $data);
    }

    /**
     * getAbout
     * @return string 
     */
    public function getAbout() {
        $sql = "select about from tbl_about limit 0,1";
        $rs = $this->conn->selectSql($sql, array());
        return $rs[0]['about'];
    }

    /**
     * updateAbout
     * @param string $about 
     */
    public function updateAbout($about) {
        $sql = "select count(*) as cnt from tbl_about";
        $rs = $this->conn->selectSql($sql, array());
        $cnt = $rs[0]['cnt'];

        if ($cnt > 0) {
            $this->sql = "update tbl_about set about=?";
            $data = array($about);
            $this->conn->executePrepared($this->sql, $data);
        } else {
            $this->sql = "INSERT INTO tbl_about(about) values (?)";
            $this->conn->executePrepared($this->sql, array($about));
        }
    }

    /**
     * getTerms
     * @return String 
     */
    public function getTerms() {
        $sql = "select terms from tbl_terms limit 0,1";
        $rs = $this->conn->selectSql($sql, array());
        return $rs[0]['terms'];
    }

    /**
     * updateTerms
     * @param String $terms
     */
    public function updateTerms($terms) {
        $sql = "select count(*) as cnt from tbl_terms";
        $rs = $this->conn->selectSql($sql, array());
        $cnt = $rs[0]['cnt'];

        if ($cnt > 0) {
            $this->sql = "update tbl_terms set terms=?";
            $data = array($terms);
            $this->conn->executePrepared($this->sql, $data);
        } else {
            $this->sql = "INSERT INTO tbl_terms(terms) values (?)";
            $this->conn->executePrepared($this->sql, array($terms));
        }
    }

    /**
     * getPrivacy
     * @return string 
     */
    public function getPrivacy() {
        $sql = "select privacy from tbl_privacy limit 0,1";
        $rs = $this->conn->selectSql($sql, array());
        return $rs[0]['privacy'];
    }

    /**
     * updatePrivacy
     * @param string $privacy
     */
    public function updatePrivacy($privacy) {
        $sql = "select count(*) as cnt from tbl_privacy";
        $rs = $this->conn->selectSql($sql, array());
        $cnt = $rs[0]['cnt'];

        if ($cnt > 0) {
            $this->sql = "update tbl_privacy set privacy=?";
            $data = array($privacy);
            $this->conn->executePrepared($this->sql, $data);
        } else {
            $this->sql = "INSERT INTO tbl_privacy(privacy) values (?)";
            $this->conn->executePrepared($this->sql, array($privacy));
        }
    }

    /**
     * getHomeDetails
     * @param integer $start 
     * @param integer $limit 
     * @param array $filters 
     * @return array 
     */
    public function getHomeDetails($start = 0, $limit = 30, $filters = null) {

        $obj = new Users();
        if (isset($_SESSION['id']))
            $userid = $_SESSION['id'];
        else
            $userid = '';

        if ($userid != '') {
            $this->sql = "select fanid from tbl_fanwire_user where user_id=? and fan_status='1'"; // LIMIT ".$strlmt.",".$endlmt;
            $rs = $this->conn->selectSql($this->sql, array($userid));
        } else {
            $this->sql = "select id as fanid from tbl_fanwires where status='1' ";
            $rs = $this->conn->selectSql($this->sql, array());
        }

        foreach ($rs as $k => $v) {
            $arr[] = $v['fanid'];
        }
        $fanids = implode(",", $arr);

        $sql = "(select id,id as fanwire_id,last_updated as cdate, 0 as type from tbl_fanwires where id in (" . $fanids . ")) union
                (select id,pid as fanwire_id,cdate,1 as type  from tbl_albums where belongsTo='2' and pid in (" . $fanids . ")) union
                (select id,user_id as fanwire_id,cdate,2 as type  from tbl_articles where visible='1' and released_date!='0000-00-00 00:00:00' and belongsTo='2' and user_id in (" . $fanids . ")) union
                (select id,whomItBelongsTo as fanwire_id,cdate,3 as type  from tbl_videos where released_date!='0000-00-00 00:00:00' and belongsTo='2' and whomItBelongsTo in (" . $fanids . ")) order by cdate desc limit " . $start . "," . $limit;
        $rs = $this->conn->selectSql($sql, array());

        $date = date("Y-m-d H:i:s");
        $list = array();
        $i = 0;
        foreach ($rs as $val) {

            $fan_id = $val['id'];


            if ($val['type'] == 0) {
                $this->sql0 = "select f.id,f.name,f.last_updated,f.released_date,f.description,f.url as fanwire,a.url from tbl_fanwires f,tbl_avatar_photos a where f.id=? and f.status='1' and f.id=a.fanwire_id and a.main_avator='1' and f.released_date<='$date'";
                $rs0 = $this->conn->selectSql($this->sql0, array($fan_id));
                foreach ($rs0 as $val0) {
                    $list[$i]['id'] = $val0['id'];
                    $list[$i]['name'] = $val0['name'];
                    $list[$i]['title'] = "";
                    $list[$i]['photo'] = $val0['url'];
                    list($width, $height) = getimagesize(DOC_ROOT_PATH . "/photos/" . $val0['url']);
                    $list[$i]['width'] = $width;
                    $list[$i]['height'] = $height;
                    $list[$i]['description'] = $val0['description'];
                    // $list[$i]['link'] = SITE_URL."profileInfo?fwrid=".$val0['id']."&ac=1";
                    $list[$i]['link'] = SITE_URL . $val0['fanwire']; //"phots?fwrid=" . $val0['id'] . "&ac=1";
                    $list[$i]['title_link'] = "";
                    $arr = explode(",", $obj->getLikesCount($val0['id'], '0'));
                    $list[$i]['like'] = $arr[0];
                    $list[$i]['dislike'] = $arr[1];
                    $list[$i]['commentcnt'] = $obj->getCommentCount($val0['id'], '0');
                    $list[$i]['status'] = $this->getCollectedStatus($val0['id'], '0');
                    $list[$i]['type'] = '0';
                    $list[$i]['source'] = '';
                    $list[$i]['comments_for_this_post'] = $obj->getComments($val0['id'], '0');
                    $list[$i]['cdate'] = $val0['last_updated'];
                    $time = DatePicker::getTimeStamp($val0['last_updated']);
                    $list[$i]['time'] = $time;

                    $i++;
                }
            }

            if ($val['type'] == 1) {
                $this->sql1 = "select a.id,a.cdate,a.released_date,a.name,a.album_url,a.status,a.pid
                                from tbl_albums a,tbl_fanwires f
                                where a.belongsTo='2' and a.id=? and a.visible='1' and a.released_date<='$date' and f.released_date<='$date' and a.pid=f.id";
                $rs1 = $this->conn->selectSql($this->sql1, array($fan_id));
                foreach ($rs1 as $val1) {
                    $list[$i]['id'] = $val1['id'];
                    $list[$i]['name'] = $this->getFanwireName($val1['pid']);
                    //$list[$i]['link'] = SITE_URL."profileInfo?fwrid=".$val1['pid']."&ac=1";
                    $list[$i]['link'] = SITE_URL . $this->getFanwireUrl($val1['pid']); //"phots?fwrid=" . $val1['pid'] . "&ac=1";
                    $list[$i]['title'] = $val1['name'];
                    $list[$i]['photo'] = $val1['album_url'];
                    list($width, $height) = getimagesize(DOC_ROOT_PATH . "/photos/" . $val1['album_url']);
                    $list[$i]['width'] = $width;
                    $list[$i]['height'] = $height;
                    $list[$i]['description'] = '';
                    $title = str_replace("'", "", $val1['name']);
                    $title = str_replace(" ", "-", $title);
                    $title = str_replace(",", "-", $title);
                    $title = str_replace(".", "-", $title);
                    $title = str_replace("(", "-", $title);
                    $title = str_replace(")", "-", $title);
                    $title = str_replace('"', '-', $title);
                    $title = str_replace('#', '', $title);
                    $title = str_replace('&', '', $title);
                    $list[$i]['title_link'] = SITE_URL . $this->getFanwireUrl($val1['pid']) . "/Gallery/" . $title;
                    $arr = explode(",", $obj->getLikesCount($val1['id'], '1'));
                    $list[$i]['like'] = $arr[0];
                    $list[$i]['dislike'] = $arr[1];
                    $list[$i]['commentcnt'] = $obj->getCommentCount($val1['id'], '1');
                    $list[$i]['status'] = $this->getCollectedStatus($val1['id'], '1');
                    $list[$i]['type'] = '1';
                    $list[$i]['source'] = '';
                    $list[$i]['comments_for_this_post'] = $obj->getComments($val1['id'], '1');
                    $list[$i]['cdate'] = $val1['cdate'];
                    $time = DatePicker::getTimeStamp($val1['cdate']);
                    $list[$i]['time'] = $time;

                    $i++;
                }
            }
            if ($val['type'] == 2) {
                $this->sql2 = "select a.id,a.cdate,a.released_date,a.article_from,a.title,a.description,a.photo,a.source,a.user_id
                                from tbl_articles a,tbl_fanwires f
                                where a.released_date!='0000-00-00 00:00:00' and  a.belongsTo='2' and a.id=? and a.visible='1' and a.released_date<='$date' and f.released_date<='$date' and a.user_id=f.id";
                $rs2 = $this->conn->selectSql($this->sql2, array($fan_id));
                foreach ($rs2 as $val2) {
                    $list[$i]['id'] = $val2['id'];
                    $list[$i]['name'] = $this->getFanwireName($val2['user_id']);
                    $list[$i]['title'] = $val2['title'];
                    $title = str_replace("'", "", $val2['title']);
                    $title = str_replace(" ", "-", $title);
                    $title = str_replace(",", "-", $title);
                    $title = str_replace(".", "-", $title);
                    $title = str_replace("(", "-", $title);
                    $title = str_replace(")", "-", $title);
                    $title = str_replace('"', '-', $title);
                    $title = str_replace('#', '', $title);
                    $title = str_replace('&', '', $title);

                    if ($val2['source'] == '1')
                        $list[$i]['title_link'] = SITE_URL . $this->getFanwireUrl($val2['user_id']) . "/FB/" . $val2['id'];
                    else
                        $list[$i]['title_link'] = SITE_URL . $this->getFanwireUrl($val2['user_id']) . "/Articles/" . $title;

                    $list[$i]['photo'] = $val2['photo'];
                    if ($val2['photo'] == "") {
                        $list[$i]['fanwire_profile_img'] = $this->getFanwireProfileImage($val2['user_id']);
                    }
                    list($width, $height) = getimagesize(DOC_ROOT_PATH . "/photos/" . $val2['photo']);
                    $list[$i]['width'] = $width;
                    $list[$i]['height'] = $height;
                    $list[$i]['source'] = $val2['source'];
                    $list[$i]['article_from'] = $val2['article_from'];

                    if ($val2['source'] == 0)
                        $list[$i]['description'] = $val2['description'];
                    else
                        $list[$i]['description'] = YoutubeUrl::replace_plain_text_link(($val2['description']));
                    //  $list[$i]['link'] = SITE_URL."profileInfo?fwrid=".$val2['user_id']."&ac=1";
                    if ($list[$i]['article_from'] == "Twitter") {
                        $list[$i]['description'] = preg_replace('/(#\w+)/', '<span class=\'twitcolor\'>\1</span>', $list[$i]['description']);
                    }
                    $list[$i]['link'] = SITE_URL . $this->getFanwireUrl($val2['user_id']); // "phots?fwrid=" . $val2['user_id'] . "&ac=1";
                    $arr = explode(",", $obj->getLikesCount($val2['id'], '2'));
                    $list[$i]['like'] = $arr[0];
                    $list[$i]['dislike'] = $arr[1];
                    $list[$i]['type'] = '2';
                    $list[$i]['commentcnt'] = $obj->getCommentCount($val2['id'], $list[$i]['type']);
                    $list[$i]['status'] = $this->getCollectedStatus($val2['id'], $list[$i]['type']);


                    $list[$i]['comments_for_this_post'] = $obj->getComments($val2['id'], $list[$i]['type']);
                    $list[$i]['cdate'] = $val2['cdate'];
                    $time = DatePicker::getTimeStamp($val2['cdate']);
                    $list[$i]['time'] = $time;

                    $i++;
                }
            }
            if ($val['type'] == 3) {
                $this->sql3 = "select v.id,v.cdate,v.released_date,v.title,v.thumbnail,v.description,v.whomItBelongsTo,v.released_date
                        from tbl_videos v,tbl_fanwires f  
                        where v.released_date!='0000-00-00 00:00:00' and v.belongsTo='2' and v.id=? and v.visible='1' and v.released_date<='$date' and f.released_date<='$date' and v.whomItBelongsTo=f.id";
                $rs3 = $this->conn->selectSql($this->sql3, array($fan_id));
                foreach ($rs3 as $val3) {
                    $list[$i]['id'] = $val3['id'];
                    $list[$i]['name'] = $this->getFanwireName($val3['whomItBelongsTo']);
                    // $list[$i]['link'] = SITE_URL."profileInfo?fwrid=".$val3['whomItBelongsTo']."&ac=1";
                    $list[$i]['link'] = SITE_URL . $this->getFanwireUrl($val3['whomItBelongsTo']); // "phots?fwrid=" . $val3['whomItBelongsTo'] . "&ac=1";
                    $list[$i]['title'] = $val3['title'];
                    $list[$i]['photo'] = $val3['thumbnail'];
                    list($width, $height) = getimagesize(DOC_ROOT_PATH . "/photos/" . $val3['thumbnail']);
                    $list[$i]['width'] = $width;
                    $list[$i]['height'] = $height;
                    $list[$i]['description'] = $val3['description'];
                    $title = str_replace("'", "", $val3['title']);
                    $title = str_replace(" ", "-", $title);
                    $title = str_replace(",", "-", $title);
                    $title = str_replace(".", "-", $title);
                    $title = str_replace("(", "-", $title);
                    $title = str_replace(")", "-", $title);
                    $title = str_replace('"', '-', $title);
                    $title = str_replace('#', '', $title);
                    $title = str_replace('&', '', $title);
                    $list[$i]['title_link'] = SITE_URL . $this->getFanwireUrl($val3['whomItBelongsTo']) . "/Videos/" . $title;
                    $arr = explode(",", $obj->getLikesCount($val3['id'], '3'));
                    $list[$i]['like'] = $arr[0];
                    $list[$i]['dislike'] = $arr[1];
                    $list[$i]['commentcnt'] = $obj->getCommentCount($val3['id'], '3');
                    $list[$i]['status'] = $this->getCollectedStatus($val3['id'], '3');
                    $list[$i]['type'] = '3';
                    $list[$i]['source'] = '';
                    $list[$i]['comments_for_this_post'] = $obj->getComments($val3['id'], '3');
                    $list[$i]['cdate'] = $val3['cdate'];
                    $time = DatePicker::getTimeStamp($val3['cdate']);
                    $list[$i]['time'] = $time;
                    $i++;
                }
            }
        }

        return $list;
    }

    /**
     * getFanwireNames
     * @param string $key
     */
    public function getFanwireNames($key) {
        $this->sql = "select name from tbl_fanwires where status='1' and name like '$key%' order by name ";
        $this->result = $this->conn->selectSql($this->sql, array());
        foreach ($this->result as $key => $value) {
            echo $value['name'] . "\n";
        }
    }

    /**
     * deleteKeyword
     * @param integer $id 
     * @return array
     */
    public function deleteKeyword($id) {
        $this->sql = "delete from tbl_keywords where id=?";
        $data = array($id);
        return $this->conn->executePrepared($this->sql, $data) or die(mysql_error());
    }

    /**
     * deleteFanwireCategory
     * @param integer $id 
     * @return array
     */
    public function deleteFanwireCategory($id) {
        $this->sql = "delete from tbl_fanwire_categories where id=?";
        $data = array($id);
        return $this->conn->executePrepared($this->sql, $data) or die(mysql_error());
    }

    /**
     * getAdditionalFans
     * @param integer $id 
     * @param integer $type
     * @return array 
     */
    public function getAdditionalFans($id, $type) {
        $sql = "select f.id,f.name from tbl_fanwires f,tbl_additional_fanwires a where a.fanwire_id=f.id and a.content_id=? and content_type=?";
        $rs = $this->conn->selectSql($sql, array($id, $type));
        $data = array();
        $i = 0;
        foreach ($rs as $key => $value) {
            $data[$i]['id'] = $value['id'];
            $data[$i]['name'] = $value['name'];
            $i++;
        }
        return $data;
    }

    /**
     * getFanwireUrl
     * @param integer $fanid 
     * @return string url
     */
    public function getFanwireUrl($fanid) {
        $this->sql = "select `url` from tbl_fanwires where id=? ";
        $this->result = $this->conn->selectSql($this->sql, array($fanid));
        return $this->result[0]['url'];
    }

    /**
     * getProfiledetails
     * @param integer $fanids 
     * @param string $page 
     * @param object $belongsTo 
     * @param integer $start 
     * @param integer $limit 
     * @return array 
     */
    public function getProfileDetails($fanids, $page, $belongsTo, $start = 0, $limit = 10) {

        $obj = new Users();
        if ($page == 'media') {
            $link = " and source in('0','3')";
        } else {
            $link = " and source not in('0','3')";
        }
        $sql = "(select id,id as fanwire_id,released_date as released_date, 0 as type,last_updated as cdate  from tbl_fanwires where id =" . $fanids . ") union
                (select af.content_id as id,af.fanwire_id,a.released_date,1 as type,a.cdate from tbl_additional_fanwires af,tbl_albums a where af.content_type='0' and a.id=af.content_id and af.fanwire_id =" . $fanids . ") union
                (select af.content_id as id,af.fanwire_id,a.released_date,2 as type,a.cdate from tbl_additional_fanwires af,tbl_articles a where a.released_date!='0000-00-00 00:00:00' and af.content_type='1' and a.id=af.content_id and af.fanwire_id =" . $fanids . ") union
                (select af.content_id as id,af.fanwire_id,v.released_date,3 as type,v.cdate from tbl_additional_fanwires af,tbl_videos v where v.released_date!='0000-00-00 00:00:00' and af.content_type='2' and v.id=af.content_id and af.fanwire_id =" . $fanids . ") union
                (select id,pid as fanwire_id,released_date,1 as type,cdate   from tbl_albums where belongsTo='" . $belongsTo . "' and pid =" . $fanids . ") union
                (select id,user_id as fanwire_id,released_date,2 as type,cdate   from tbl_articles where visible='1' and released_date!='0000-00-00 00:00:00' and belongsTo='" . $belongsTo . "' and user_id =" . $fanids . $link . ") union
                (select id,whomItBelongsTo as fanwire_id,released_date,3 as type,cdate from tbl_videos where released_date!='0000-00-00 00:00:00' and belongsTo='" . $belongsTo . "' and whomItBelongsTo =" . $fanids . ") order by cdate desc limit " . $start . "," . $limit;
        $rs = $this->conn->selectSql($sql, array());

        $date = date("Y-m-d H:i:s");
        $list = array();
        $i = 0;
        foreach ($rs as $val) {
            $fan_id = $val['id'];
            if ($val['type'] == 0) {
                $this->sql0 = "select f.id,f.name,f.last_updated.released_date,f.description,f.url as fanwire,a.url from tbl_fanwires f,tbl_avatar_photos a where f.id=? and f.status='1' and f.id=a.fanwire_id and a.main_avator='1' and f.released_date<='$date'";
                $rs0 = $this->conn->selectSql($this->sql0, array($fan_id));
                foreach ($rs0 as $val0) {
                    $list[$i]['id'] = $val0['id'];
                    $list[$i]['name'] = $val0['name'];
                    $list[$i]['title'] = "";
                    $list[$i]['photo'] = $val0['url'];
                    list($width, $height) = getimagesize(DOC_ROOT_PATH . "/photos/" . $val0['url']);
                    $list[$i]['width'] = $width;
                    $list[$i]['height'] = $height;
                    $list[$i]['description'] = $val0['description'];
                    // $list[$i]['link'] = SITE_URL."profileInfo?fwrid=".$val0['id']."&ac=1";
                    $list[$i]['link'] = SITE_URL . $val0['fanwire']; //"phots?fwrid=" . $val0['id'] . "&ac=1";
                    $list[$i]['title_link'] = "";
                    $arr = explode(",", $obj->getLikesCount($val0['id'], '0'));
                    $list[$i]['like'] = $arr[0];
                    $list[$i]['dislike'] = $arr[1];
                    $list[$i]['commentcnt'] = $obj->getCommentCount($val0['id'], '0');
                    $list[$i]['status'] = $this->getCollectedStatus($val0['id'], '0');
                    $list[$i]['type'] = '0';
                    $list[$i]['source'] = '';
                    $list[$i]['comments_for_this_post'] = $obj->getComments($val0['id'], '0');
                    $list[$i]['cdate'] = $val0['last_updated'];
                    $time = DatePicker::getTimeStamp($val0['last_updated']);
                    $list[$i]['time'] = $time;
                    $i++;
                }
            }
            if ($val['type'] == 1) {
                $this->sql1 = "select id,cdate,released_date,name,album_url,status,pid,source from tbl_albums where belongsTo='2' and id=? and visible='1' and released_date<='$date'";
                $rs1 = $this->conn->selectSql($this->sql1, array($fan_id));
                foreach ($rs1 as $val1) {
                    $list[$i]['id'] = $val1['id'];
                    // echo $val1['id'] . '<br>';
                    if ($val['type'] == 1)
                        $fanwire = $val1['pid'];
                    else
                        $fanwire = $val['fanwire_id'];
                    $list[$i]['name'] = $this->getFanwireName($fanwire);
                    //$list[$i]['link'] = SITE_URL."profileInfo?fwrid=".$val1['pid']."&ac=1";
                    $list[$i]['link'] = SITE_URL . $this->getFanwireUrl($fanwire); //"phots?fwrid=" . $val1['pid'] . "&ac=1";
                    $list[$i]['title'] = $val1['name'];
                    $list[$i]['photo'] = $val1['album_url'];
                    list($width, $height) = getimagesize(DOC_ROOT_PATH . "/photos/" . $val1['album_url']);
                    $list[$i]['width'] = $width;
                    $list[$i]['height'] = $height;
                    $list[$i]['description'] = '';
                    if ($val1['name'] == '') {
                        $title = $val1['id'];
                        $list[$i]['title_link'] = SITE_URL . $this->getFanwireUrl($val1['pid']) . "/Instagram/" . $title;
                    } else {
                        $title = str_replace("'", "", $val1['name']);
                        $title = str_replace(" ", "-", $title);
                        $title = str_replace(",", "-", $title);
                        $title = str_replace(".", "-", $title);
                        $title = str_replace("(", "-", $title);
                        $title = str_replace(")", "-", $title);
                        $title = str_replace('"', '-', $title);
                        $title = str_replace('#', '', $title);
                        $title = str_replace('&', '', $title);
                        $list[$i]['title_link'] = SITE_URL . $this->getFanwireUrl($val1['pid']) . "/Gallery/" . $title;
                    }
//                    $title = str_replace("'", "", $val1['name']);
//                    $title = str_replace(" ", "-", $title);
//                    $title = str_replace(",", "-", $title);
//                    $title = str_replace(".", "-", $title);
//                    $title = str_replace("(", "-", $title);
//                    $title = str_replace(")", "-", $title);
//                    $title = str_replace('"', '-', $title);
//                    $title = str_replace('#', '', $title);
//                    $title = str_replace('&', '', $title);
//                    // $list[$i]['type'] = '1';
                    if ($val1['source'] == 'Instagram') {
                        $list[$i]['type'] = '4';
                    } else {
                        $list[$i]['type'] = '1';
                    }
                    //$list[$i]['title_link'] = SITE_URL . $this->getFanwireUrl($fanwire) . "/Gallery/" . $title;
                    $arr = explode(",", $obj->getLikesCount($val1['id'], $list[$i]['type']));
                    //print_r($arr);
                    $list[$i]['like'] = $arr[0];
                    $list[$i]['dislike'] = $arr[1];
                    $list[$i]['commentcnt'] = $obj->getCommentCount($val1['id'], $list[$i]['type']);
                    $list[$i]['status'] = $this->getCollectedStatus($val1['id'], $list[$i]['type']);

                    $list[$i]['source'] = '';
                    $list[$i]['comments_for_this_post'] = $obj->getComments($val1['id'], $list[$i]['type']);
                    $list[$i]['cdate'] = $val1['cdate'];
                    $time = DatePicker::getTimeStamp($val1['cdate']);
                    $list[$i]['time'] = $time;
                    $i++;
                }
            }

            if ($val['type'] == 2) {

                $this->sql2 = "select id,cdate,released_date,article_from,title,description,photo,source,user_id from tbl_articles where released_date!='0000-00-00 00:00:00' and  belongsTo='2' and id=? and visible='1'  and released_date<='$date'";
                $rs2 = $this->conn->selectSql($this->sql2, array($fan_id));
                foreach ($rs2 as $val2) {
                    $list[$i]['id'] = $val2['id'];
                    if ($val['type'] == 2)
                        $fanwire = $val2['user_id'];
                    else
                        $fanwire = $val['fanwire_id'];
                    $list[$i]['name'] = $this->getFanwireName($fanwire);
                    $list[$i]['title'] = $val2['title'];
                    $title = str_replace("'", "", $val2['title']);
                    $title = str_replace(" ", "-", $title);
                    $title = str_replace(",", "-", $title);
                    $title = str_replace(".", "-", $title);
                    $title = str_replace("(", "-", $title);
                    $title = str_replace(")", "-", $title);
                    $title = str_replace('"', '-', $title);
                    $title = str_replace('#', '', $title);
                    $title = str_replace('&', '', $title);

                    //$list[$i]['title_link'] = SITE_URL . $this->getFanwireUrl($fanwire) . "/Articles/" . $title;
                    if ($val2['source'] == '1') {

                        $list[$i]['title_link'] = SITE_URL . $this->getFanwireUrl($val2['user_id']) . "/Facebook/" . $val2['id'];
                    } else {

                        $list[$i]['title_link'] = SITE_URL . $this->getFanwireUrl($val2['user_id']) . "/Articles/" . $title;
                    }
                    $list[$i]['photo'] = $val2['photo'];
                    if ($val2['photo'] == "") {
                        $list[$i]['fanwire_profile_img'] = $this->getFanwireProfileImage($fanwire);
                    }
                    list($width, $height) = getimagesize(DOC_ROOT_PATH . "/photos/" . $val2['photo']);
                    $list[$i]['width'] = $width;
                    $list[$i]['height'] = $height;
                    $list[$i]['source'] = $val2['source'];
                    $list[$i]['article_from'] = $val2['article_from'];

                    if ($val2['source'] == 0)
                        $list[$i]['description'] = $val2['description'];
                    else
                        $list[$i]['description'] = $val2['description']; //YoutubeUrl::replace_plain_text_link(($val2['description']));










































                        
//remove iframe tag from the description
                    $strStr = strpos($list[$i]['description'], "<iframe>");
                    $strEnd = strpos($list[$i]['description'], "</iframe>");
                    $list[$i]['description'] = substr_replace($list[$i]['description'], '', $strStr, $strEnd);


                    //  $list[$i]['link'] = SITE_URL."profileInfo?fwrid=".$val2['user_id']."&ac=1";
                    if ($list[$i]['article_from'] == "Twitter") {
                        $list[$i]['description'] = preg_replace('/(#\w+)/', '<span class=\'twitcolor\'>\1</span>', $list[$i]['description']);
                    }
                    $list[$i]['link'] = SITE_URL . $this->getFanwireUrl($fanwire); // "phots?fwrid=" . $val2['user_id'] . "&ac=1";
                    $arr = explode(",", $obj->getLikesCount($val2['id'], '2'));
                    $list[$i]['like'] = $arr[0];
                    $list[$i]['dislike'] = $arr[1];
                    $list[$i]['commentcnt'] = $obj->getCommentCount($val2['id'], '2');
                    $list[$i]['status'] = $this->getCollectedStatus($val2['id'], '2');
                    $list[$i]['type'] = '2';

                    $list[$i]['comments_for_this_post'] = $obj->getComments($val2['id'], '2');
                    $list[$i]['cdate'] = $val2['cdate'];
                    $time = DatePicker::getTimeStamp($val2['cdate']);
                    $list[$i]['time'] = $time;
                    $i++;
                }
            }
            if ($val['type'] == 3) {
                $this->sql3 = "select id,cdate,released_date,title,thumbnail,description,whomItBelongsTo from tbl_videos where visible='1' and released_date!='0000-00-00 00:00:00' and belongsTo='2' and id=? and visible='1' and released_date<='$date'";
                $rs3 = $this->conn->selectSql($this->sql3, array($fan_id));
                foreach ($rs3 as $val3) {
                    $list[$i]['id'] = $val3['id'];
                    if ($val['type'] == 3)
                        $fanwire = $val3['whomItBelongsTo'];
                    else
                        $fanwire = $val['fanwire_id'];

                    $list[$i]['name'] = $this->getFanwireName($fanwire);
                    // $list[$i]['link'] = SITE_URL."profileInfo?fwrid=".$val3['whomItBelongsTo']."&ac=1";
                    $list[$i]['link'] = SITE_URL . $this->getFanwireUrl($fanwire); // "phots?fwrid=" . $val3['whomItBelongsTo'] . "&ac=1";
                    $list[$i]['title'] = $val3['title'];
                    $list[$i]['photo'] = $val3['thumbnail'];
                    list($width, $height) = getimagesize(DOC_ROOT_PATH . "/photos/" . $val3['thumbnail']);
                    $list[$i]['width'] = $width;
                    $list[$i]['height'] = $height;
                    $list[$i]['description'] = $val3['description'];
                    $title = str_replace("'", "", $val3['title']);
                    $title = str_replace(" ", "-", $title);
                    $title = str_replace(",", "-", $title);
                    $title = str_replace(".", "-", $title);
                    $title = str_replace("(", "-", $title);
                    $title = str_replace(")", "-", $title);
                    $title = str_replace('"', '-', $title);
                    $title = str_replace('#', '', $title);
                    $title = str_replace('&', '', $title);
                    $list[$i]['title_link'] = SITE_URL . $this->getFanwireUrl($fanwire) . "/Videos/" . $title;
                    $arr = explode(",", $obj->getLikesCount($val3['id'], '3'));
                    $list[$i]['like'] = $arr[0];
                    $list[$i]['dislike'] = $arr[1];
                    $list[$i]['commentcnt'] = $obj->getCommentCount($val3['id'], '3');
                    $list[$i]['status'] = $this->getCollectedStatus($val3['id'], '3');
                    $list[$i]['type'] = '3';
                    $list[$i]['source'] = '';
                    $list[$i]['comments_for_this_post'] = $obj->getComments($val3['id'], '3');
                    $list[$i]['cdate'] = $val3['cdate'];
                    $time = DatePicker::getTimeStamp($val3['cdate']);
                    $list[$i]['time'] = $time;
                    $i++;
                }
            }
        }
        return $list;
    }

    /**
     * deleteAdditionalFans
     * @param integer $id
     * @return array 
     */
    public function deleteAdditionalFans($id) {
        $this->sql = "delete from tbl_additional_fanwires where fanwire_id=?";
        $data = array($id);
        return $this->conn->executePrepared($this->sql, $data) or die(mysql_error());
    }

    /**
     * getFanwireIdByName
     * @param string  $name
     * @return integer 
     */
    public function getFanwireIdByName($name) {
        $sql = "select id from tbl_fanwires where name=?";
        $rs = $this->conn->selectSql($sql, array($name));
        return $rs[0]['id'];
    }

    /**
     * getFanwireCategories
     * @param string  $fanid
     * @return array 
     */
    public function getFanwireCategories($fanid) {
        $sql = "select c.id,c.name from tbl_fanwire_categories fc,tbl_category c where c.id=fc.category and fc.fanwire_id=?";
        return $result = $this->conn->selectSql($sql, array($fanid));
    }

    /**
     * getFiltersPostsOnly
     * @param inateger  $start
     * @param inateger  $limit
     * @param array  $filters
     * @param inateger  $video
     * @param inateger  $photo
     * @return array 
     */
    public function getFiltersPostsOnly($start = 0, $limit = 30, $filters, $video = 0, $photo) {
        $obj = new Users();
        if (isset($_SESSION['id']))
            $userid = $_SESSION['id'];
        else
            $userid = '';
        if ($userid != '') {
            $this->sql = "select fanid from tbl_fanwire_user where user_id=? and fan_status='1'"; // LIMIT ".$strlmt.",".$endlmt;
            $rs = $this->conn->selectSql($this->sql, array($userid));
        } else {
            $this->sql = "select id as fanid from tbl_fanwires where status='1' ";
            $rs = $this->conn->selectSql($this->sql, array());
        }
        foreach ($rs as $k => $v) {
            $arr[] = $v['fanid'];
        }
        $fanids = implode(",", $arr);

        if ($filters == '' && $video == '' && $photo == '') {
            $sql = '(select id,id as fanwire_id,last_updated as cdate, 0 as type from tbl_fanwires where id in (' . $fanids . '))';
        } else {
            $sql = '(select id,id as fanwire_id,last_updated as cdate, 0 as type from tbl_fanwires where id in (\'\'))';
            if ($photo != '') {
                $sql .= " union (select id,pid as fanwire_id,cdate,1 as type  from tbl_albums where belongsTo='2' and pid in (" . $fanids . ")  and source in(" . $photo . ")) ";
            }
            if ($filters) {
                $sql .= " union
                (select id,user_id as fanwire_id,cdate,2 as type  from tbl_articles where visible='1' and released_date!='0000-00-00 00:00:00' and belongsTo='2' and user_id in (" . $fanids . ") and source in(" . $filters . "))";
            }
            if ($video == 4) {
                $sql .= " union (select id,whomItBelongsTo as fanwire_id,cdate,3 as type  from tbl_videos where released_date!='0000-00-00 00:00:00' and belongsTo='2' and whomItBelongsTo in (" . $fanids . "))";
            }
        }
        $sql .=" order by cdate desc limit " . $start . ", " . $limit;
        error_log("naresh" . $sql);
        //echo $sql;
        $rs = $this->conn->selectSql($sql, array());
        $date = date("Y-m-d H:i:s");
        $list = array();
        $i = 0;
        foreach ($rs as $val) {

            $fan_id = $val['id'];
            if ($val['type'] == 2) {
                if (true) {

                    $this->sql2 = "select a.id, a.cdate, a.released_date, a.article_from, a.title, a.description, a.photo, a.source, a.user_id
from tbl_articles a, tbl_fanwires f where a.released_date!='0000-00-00 00:00:00' and a.belongsTo = '2' and a.id = ? and a.visible = '1' and a.released_date<='$date' and f.released_date<='$date' and a.user_id = f.id and a.source in(" . $filters . ")";
                    $rs2 = $this->conn->selectSql($this->sql2, array($fan_id));
                    foreach ($rs2 as $val2) {
                        $list[$i]['id'] = $val2['id'];
                        $list[$i]['name'] = $this->getFanwireName($val2['user_id']);
                        $list[$i]['title'] = $val2['title'];
                        if ($val2['source'] == '1')
                            $list[$i]['title_link'] = SITE_URL . $this->getFanwireUrl($val2['user_id']) . "/FB/" . $val2['id'];
                        else
                            $list[$i]['title_link'] = SITE_URL . $this->getFanwireUrl($val2['user_id']) . "/Articles/" . YoutubeUrl::fixurl($val2['title']);

                        $list[$i]['photo'] = $val2['photo'];
                        if ($val2['photo'] == "") {
                            $list[$i]['fanwire_profile_img'] = $this->getFanwireProfileImage($val2['user_id']);
                        }
                        // list($width, $height) = getimagesize(DOC_ROOT_PATH . "/photos/" . $val2['photo']);
                        list($width, $height) = getimagesize(IMAGE_URL . $val2['photo']);
                        $list[$i]['width'] = $width;
                        $list[$i]['height'] = $height;
                        $list[$i]['source'] = $val2['source'];
                        $list[$i]['article_from'] = $val2['article_from'];

                        if ($val2['source'] == 0)
                            $list[$i]['description'] = $val2['description'];
                        else
                            $list[$i]['description'] = YoutubeUrl::replace_plain_text_link(($val2['description']));
                        if ($list[$i]['article_from'] == "Twitter") {
                            $list[$i]['description'] = preg_replace('/(#\w+)/', '<span class=\'twitcolor\'>\1</span>', $list[$i]['description']);
                        }
                        if ($val2['source'] == 1) {
                            $title = substr($val2['description'], 0, 10);
                        }
                        $list[$i]['type'] = '2';
                        $list[$i]['link'] = SITE_URL . $this->getFanwireUrl($val2['user_id']); // "phots?fwrid = " . $val2['user_id'] . "&ac = 1";
                        $arr = explode(",", $obj->getLikesCount($val2['id'], $list[$i]['type']));
                        $list[$i]['like'] = $arr[0];
                        $list[$i]['dislike'] = $arr[1];
                        $list[$i]['commentcnt'] = $obj->getCommentCount($val2['id'], $list[$i]['type']);
                        $list[$i]['status'] = $this->getCollectedStatus($val2['id'], $list[$i]['type']);


                        $list[$i]['comments_for_this_post'] = $obj->getComments($val2['id'], $list[$i]['type']);
                        $list[$i]['cdate'] = $val2['cdate'];
                        $time = DatePicker::getTimeStamp($val2['cdate']);
                        $list[$i]['time'] = $time;

                        $i++;
                    }
                }
            }

            if ($val['type'] == 1) {
                $this->sql1 = "select a.id, a.cdate, a.released_date, a.name, a.album_url, a.status, a.pid,a.source
from tbl_albums a, tbl_fanwires f
where a.belongsTo = '2' and a.id = ? and a.visible = '1' and a.released_date<='$date' and f.released_date<='$date' and a.pid = f.id";
                $rs1 = $this->conn->selectSql($this->sql1, array($fan_id));
                // echo "<pre>";print_r($rs1);
                foreach ($rs1 as $val1) {
                    $list[$i]['id'] = $val1['id'];
                    $list[$i]['name'] = $this->getFanwireName($val1['pid']);
                    $list[$i]['link'] = SITE_URL . $this->getFanwireUrl($val1['pid']); //"phots?fwrid = " . $val1['pid'] . "&ac = 1";
                    $list[$i]['title'] = $val1['name'];
                    $list[$i]['photo'] = $val1['album_url'];
                    list($width, $height) = getimagesize(DOC_ROOT_PATH . "/photos/" . $val1['album_url']);
                    $list[$i]['width'] = $width;
                    $list[$i]['height'] = $height;
                    $list[$i]['description'] = '';
                    if ($val1['name'] == '') {
                        $title = $val1['id'];
                        $list[$i]['title_link'] = SITE_URL . $this->getFanwireUrl($val1['pid']) . "/Instagram/" . $title;
                    } else {
                        $list[$i]['title_link'] = SITE_URL . $this->getFanwireUrl($val1['pid']) . "/Gallery/" . YoutubeUrl::fixurl($val1['name']);
                    }

                    if ($val1['source'] == 'Instagram') {
                        $list[$i]['type'] = '4';
                    } else {
                        $list[$i]['type'] = '1';
                    }

                    $arr = explode(",", $obj->getLikesCount($val1['id'], $list[$i]['type']));
                    $list[$i]['like'] = $arr[0];
                    $list[$i]['dislike'] = $arr[1];
                    $list[$i]['commentcnt'] = $obj->getCommentCount($val1['id'], $list[$i]['type']);
                    $list[$i]['status'] = $this->getCollectedStatus($val1['id'], $list[$i]['type']);

                    $list[$i]['source'] = '';

                    $list[$i]['comments_for_this_post'] = $obj->getComments($val1['id'], $list[$i]['type']);
                    $list[$i]['cdate'] = $val1['cdate'];
                    $time = DatePicker::getTimeStamp($val1['cdate']);
                    $list[$i]['time'] = $time;

                    $i++;
                }
            }
            if ($val['type'] == 3) {
                if ($video == '4') {
                    $this->sql3 = "select v.id, v.cdate, v.released_date, v.title, v.thumbnail, v.description, v.whomItBelongsTo, v.released_date
from tbl_videos v, tbl_fanwires f
where v.released_date!='0000-00-00 00:00:00' and v.belongsTo = '2' and v.id = ? and v.visible = '1' and v.released_date<='$date' and f.released_date<='$date' and v.whomItBelongsTo = f.id";

                    $rs3 = $this->conn->selectSql($this->sql3, array($fan_id));
                    foreach ($rs3 as $val3) {
                        $list[$i]['id'] = $val3['id'];
                        $list[$i]['name'] = $this->getFanwireName($val3['whomItBelongsTo']);
                        // $list[$i]['link'] = SITE_URL."profileInfo?fwrid = ".$val3['whomItBelongsTo']."&ac = 1";
                        $list[$i]['link'] = SITE_URL . $this->getFanwireUrl($val3['whomItBelongsTo']); // "phots?fwrid = " . $val3['whomItBelongsTo'] . "&ac = 1";
                        $list[$i]['title'] = $val3['title'];
                        $list[$i]['photo'] = $val3['thumbnail'];
                        list($width, $height) = getimagesize(DOC_ROOT_PATH . "/photos/" . $val3['thumbnail']);
                        $list[$i]['width'] = $width;
                        $list[$i]['height'] = $height;
                        $list[$i]['description'] = $val3['description'];
                        $list[$i]['type'] = '3';
                        $list[$i]['title_link'] = SITE_URL . $this->getFanwireUrl($val3['whomItBelongsTo']) . "/Videos/" . YoutubeUrl::fixurl($val3['title']);
                        $arr = explode(",", $obj->getLikesCount($val3['id'], $list[$i]['type']));
                        $list[$i]['like'] = $arr[0];
                        $list[$i]['dislike'] = $arr[1];
                        $list[$i]['commentcnt'] = $obj->getCommentCount($val3['id'], $list[$i]['type']);
                        $list[$i]['status'] = $this->getCollectedStatus($val3['id'], $list[$i]['type']);
                        $list[$i]['source'] = '';
                        $list[$i]['comments_for_this_post'] = $obj->getComments($val3['id'], $list[$i]['type']);
                        $list[$i]['cdate'] = $val3['cdate'];
                        $time = DatePicker::getTimeStamp($val3['cdate']);
                        $list[$i]['time'] = $time;
                        $i++;
                    }
                }
            }
            if ($filters == '' && $video == '' && $photo == '') {
                if ($val['type'] == 0) {
                    $this->sql0 = "select f.id,f.name,f.last_updated,f.released_date,f.description,f.url as fanwire,a.url from tbl_fanwires f,tbl_avatar_photos a where f.id=? and f.status='1' and f.id=a.fanwire_id and a.main_avator='1' and f.released_date<='$date'";
                    $rs0 = $this->conn->selectSql($this->sql0, array($fan_id));
                    foreach ($rs0 as $val0) {
                        $list[$i]['id'] = $val0['id'];
                        $list[$i]['name'] = $val0['name'];
                        $list[$i]['title'] = "";
                        $list[$i]['photo'] = $val0['url'];
                        list($width, $height) = getimagesize(DOC_ROOT_PATH . "/photos/" . $val0['url']);
                        $list[$i]['width'] = $width;
                        $list[$i]['height'] = $height;
                        $list[$i]['description'] = $val0['description'];
                        $list[$i]['link'] = SITE_URL . $val0['fanwire']; //"phots?fwrid=" . $val0['id'] . "&ac=1";
                        $list[$i]['title_link'] = "";
                        $list[$i]['type'] = '0';
                        $arr = explode(",", $obj->getLikesCount($val0['id'], $list[$i]['type']));
                        $list[$i]['like'] = $arr[0];
                        $list[$i]['dislike'] = $arr[1];
                        $list[$i]['commentcnt'] = $obj->getCommentCount($val0['id'], $list[$i]['type']);
                        $list[$i]['status'] = $this->getCollectedStatus($val0['id'], $list[$i]['type']);

                        $list[$i]['source'] = '';
                        $list[$i]['comments_for_this_post'] = $obj->getComments($val0['id'], $list[$i]['type']);
                        $list[$i]['cdate'] = $val0['last_updated'];
                        $time = DatePicker::getTimeStamp($val0['last_updated']);
                        $list[$i]['time'] = $time;
                        $i++;
                    }
                }
            }
        }
        return $list;
    }

    //search implementation--ilker


    public function getSearchedPosts($start = 0, $limit = 30, $filters, $video = 0, $photo = 0, $searchterm, $searchcriteria) {
        $obj = new Users();
        if (isset($_SESSION['id']))
            $userid = $_SESSION['id'];
        else
            $userid = '';
        //also check keywords
        $keyword_fanwire = '';
        $sql_keyword_fanwire = "SELECT tbl_fanwires.name FROM tbl_fanwires";
        $sql_keyword_fanwire .= " INNER JOIN tbl_keywords ON (tbl_fanwires.id = tbl_keywords.referer_id)";
        $sql_keyword_fanwire .= " WHERE keyword = '" . $searchterm . "' LIMIT 1";
        $result_keyword_fanwire = $this->conn->selectSql($sql_keyword_fanwire, array());
        foreach ($result_keyword_fanwire as $valkeyw) {
            $searchterm = $valkeyw['name'];
        }
        $against = "('" . $searchterm . "' IN BOOLEAN MODE)";
        $sqlprofiles = "select id,id as fanwire_id,last_updated as cdate, 0 as type,MATCH(name,description) AGAINST $against as rank from tbl_fanwires where MATCH (name,description) AGAINST $against ORDER BY rank DESC";
        $rsprofiles = $this->conn->selectSql($sqlprofiles, array());
        $search_profiles_cnt = count($rsprofiles);
        $sqlmedia = "select id,pid as fanwire_id,cdate,1 as type,MATCH(name,description,source) AGAINST $against as rank  from tbl_albums where belongsTo='2' AND MATCH(name,description,source) AGAINST $against";
        $sqlmedia .= " union select id,whomItBelongsTo as fanwire_id,cdate,3 as type,MATCH(title,description) AGAINST $against as rank from tbl_videos where released_date!='0000-00-00 00:00:00' and belongsTo='2' AND MATCH(title,description) AGAINST $against ORDER BY rank DESC";
        $rsmedia = $this->conn->selectSql($sqlmedia, array());
        $search_media_cnt = count($rsmedia);
        $sqlsocial = "(select id,user_id as fanwire_id,cdate,2 as type,MATCH(title,description,article_source,article_link,article_from) AGAINST $against as rank from tbl_articles where visible='1' and released_date!='0000-00-00 00:00:00' and belongsTo='2' and source in('1','2') AND MATCH(title,description,article_source,article_link,article_from) AGAINST $against ORDER BY rank DESC)";
        $rssocial = $this->conn->selectSql($sqlsocial, array());
        $search_social_cnt = count($rssocial);
        $sqlall = "select id,id as fanwire_id,last_updated as cdate, 0 as type,MATCH(name,description) AGAINST $against as rank from tbl_fanwires where MATCH (name,description) AGAINST $against";
        $sqlall .= " union select id,pid as fanwire_id,cdate,1 as type,MATCH(name,description,source) AGAINST $against as rank  from tbl_albums where belongsTo='2' AND MATCH(name,description,source) AGAINST $against";
        $sqlall .= " union select id,whomItBelongsTo as fanwire_id,cdate,3 as type,MATCH(title,description) AGAINST $against as rank from tbl_videos where released_date!='0000-00-00 00:00:00' and belongsTo='2' AND MATCH(title,description) AGAINST $against";
        $sqlall .= " union (select id,user_id as fanwire_id,cdate,2 as type,MATCH(title,description,article_source,article_link,article_from) AGAINST $against as rank from tbl_articles where visible='1' and released_date!='0000-00-00 00:00:00' and belongsTo='2' and source in('1','2') AND MATCH(title,description,article_source,article_link,article_from) AGAINST $against ORDER BY rank DESC)";
        $rsall = $this->conn->selectSql($sqlall, array());
        $search_all_cnt = count($rsall);
        $sqlmycollection = "select id,id as fanwire_id,last_updated as cdate, 0 as type,MATCH(name,description) AGAINST $against as rank from tbl_fanwires where MATCH (name,description) AGAINST $against AND id IN (select fanwire_id from tbl_collections where user_id=" . $userid . " and status='1' and type='0')";
        $sqlmycollection .= " union select id,pid as fanwire_id,cdate,1 as type,MATCH(name,description,source) AGAINST $against as rank  from tbl_albums where belongsTo='2' AND MATCH(name,description,source) AGAINST $against AND id IN (select fanwire_id from tbl_collections where user_id=" . $userid . " and status='1' AND type='1')";
        $sqlmycollection .= " union select id,whomItBelongsTo as fanwire_id,cdate,3 as type,MATCH(title,description) AGAINST $against as rank from tbl_videos where released_date!='0000-00-00 00:00:00' and belongsTo='2' AND MATCH(title,description) AGAINST $against AND id IN (select fanwire_id from tbl_collections where user_id=" . $userid . " and status='1' AND type='3')";
        $sqlmycollection .= " union (select id,user_id as fanwire_id,cdate,2 as type,MATCH(title,description,article_source,article_link,article_from) AGAINST $against as rank from tbl_articles where visible='1' and released_date!='0000-00-00 00:00:00' and belongsTo='2' and source in('1','2') AND MATCH(title,description,article_source,article_link,article_from) AGAINST $against AND id IN (select fanwire_id from tbl_collections where user_id=" . $userid . " and status='1' AND type='2') ORDER BY rank DESC)";
        $rsmycollection = $this->conn->selectSql($sqlmycollection, array());
        $search_mycollection_cnt = count($rsmycollection);
        $search_all_cnt = $search_all_cnt + $search_mycollection_cnt;
        $_SESSION['searchcounts'] = $search_all_cnt . ',' . $search_profiles_cnt . ',' . $search_media_cnt . ',' . $search_social_cnt . ',' . $search_mycollection_cnt;
        switch ($searchcriteria) {
            case 'profiles':
                $rs = $rsprofiles;
                break;
            case 'media': //photo and video
                $rs = $rsmedia;
                break;
            case 'social': //facebook and twitter in articles
                $rs = $rssocial;
                break;
            case 'mycollection':
                $rs = $rsmycollection;
                break;
            case 'all':
                $rs = $rsall;
                break;
        }
        //echo $sql;
        $date = date("Y-m-d H:i:s");
        $list = array();
        $i = 0;
        foreach ($rs as $val) {
            $fan_id = $val['id'];
            if ($val['type'] == 2) {
                //article
                if (true) {
                    $this->sql2 = "select a.id, a.cdate, a.released_date, a.article_from, a.title, a.description, a.photo, a.source, a.user_id
from tbl_articles a, tbl_fanwires f where a.released_date!='0000-00-00 00:00:00' and a.belongsTo = '2' and a.id = ? and a.visible = '1' and a.released_date<='$date' and f.released_date<='$date' and a.user_id = f.id and a.source in(" . $filters . ")";

                    $rs2 = $this->conn->selectSql($this->sql2, array($fan_id));

                    foreach ($rs2 as $val2) {
                        $list[$i]['id'] = $val2['id'];
                        $list[$i]['name'] = $this->getFanwireName($val2['user_id']);
                        $list[$i]['title'] = $val2['title'];
                        $title = str_replace("'", "", $val2['title']);
                        $title = str_replace(" ", "-", $title);
                        $title = str_replace(",", "-", $title);
                        $title = str_replace(".", "-", $title);
                        $title = str_replace("(", "-", $title);
                        $title = str_replace(")", "-", $title);
                        $title = str_replace('"', '-', $title);
                        $title = str_replace('#', '', $title);
                        $title = str_replace('&', '', $title);
                        $list[$i]['title_link'] = SITE_URL . $this->getFanwireUrl($val2['user_id']) . "/Articles/" . $title;
                        $list[$i]['photo'] = $val2['photo'];
                        if ($val2['photo'] == "") {
                            $list[$i]['fanwire_profile_img'] = $this->getFanwireProfileImage($val2['user_id']);
                        }
                        list($width, $height) = getimagesize(DOC_ROOT_PATH . "/photos/" . $val2['photo']);
                        $list[$i]['width'] = $width;
                        $list[$i]['height'] = $height;
                        $list[$i]['source'] = $val2['source'];
                        $list[$i]['article_from'] = $val2['article_from'];

                        if ($val2['source'] == 0)
                            $list[$i]['description'] = $val2['description'];
                        else
                            $list[$i]['description'] = YoutubeUrl::replace_plain_text_link(($val2['description']));
                        //  $list[$i]['link'] = SITE_URL."profileInfo?fwrid = ".$val2['user_id']."&ac = 1";
                        if ($list[$i]['article_from'] == "Twitter") {
                            $list[$i]['description'] = preg_replace('/(#\w+)/', '<span class=\'twitcolor\'>\1</span>', $list[$i]['description']);
                        }
                        $list[$i]['link'] = SITE_URL . $this->getFanwireUrl($val2['user_id']); // "phots?fwrid = " . $val2['user_id'] . "&ac = 1";
                        $arr = explode(",", $obj->getLikesCount($val2['id'], '2'));
                        $list[$i]['like'] = $arr[0];
                        $list[$i]['dislike'] = $arr[1];
                        $list[$i]['commentcnt'] = $obj->getCommentCount($val2['id'], '2');
                        $list[$i]['status'] = $this->getCollectedStatus($val2['id'], '2');
                        $list[$i]['type'] = '2';

                        $list[$i]['comments_for_this_post'] = $obj->getComments($val2['id'], '2');
                        $list[$i]['cdate'] = $val2['cdate'];
                        $time = DatePicker::getTimeStamp($val2['cdate']);
                        $list[$i]['time'] = $time;

                        $i++;
                    }
                }
                //echo "<pre>";print_r($list); 
            }

            if ($val['type'] == 1) {
                //photo
                if ($photo == 5) {
                    $this->sql1 = "select a.id, a.cdate, a.released_date, a.name, a.album_url, a.status, a.pid
from tbl_albums a, tbl_fanwires f
where a.belongsTo = '2' and a.id = ? and a.visible = '1' and a.released_date<='$date' and f.released_date<='$date' and a.pid = f.id";
                    $rs1 = $this->conn->selectSql($this->sql1, array($fan_id));
                    foreach ($rs1 as $val1) {
                        $list[$i]['id'] = $val1['id'];
                        $list[$i]['name'] = $this->getFanwireName($val1['pid']);
                        //$list[$i]['link'] = SITE_URL."profileInfo?fwrid = ".$val1['pid']."&ac = 1";
                        $list[$i]['link'] = SITE_URL . $this->getFanwireUrl($val1['pid']); //"phots?fwrid = " . $val1['pid'] . "&ac = 1";
                        $list[$i]['title'] = $val1['name'];
                        $list[$i]['photo'] = $val1['album_url'];
                        list($width, $height) = getimagesize(DOC_ROOT_PATH . "/photos/" . $val1['album_url']);
                        $list[$i]['width'] = $width;
                        $list[$i]['height'] = $height;
                        $list[$i]['description'] = '';
                        $title = str_replace("'", "", $val1['name']);
                        $title = str_replace(" ", "-", $title);
                        $title = str_replace(",", "-", $title);
                        $title = str_replace(".", "-", $title);
                        $title = str_replace("(", "-", $title);
                        $title = str_replace(")", "-", $title);
                        $title = str_replace('"', '-', $title);
                        $title = str_replace('#', '', $title);
                        $title = str_replace('&', '', $title);
                        $list[$i]['title_link'] = SITE_URL . $this->getFanwireUrl($val1['pid']) . "/Gallery/" . $title;
                        $arr = explode(",", $obj->getLikesCount($val1['id'], '1'));
                        $list[$i]['like'] = $arr[0];
                        $list[$i]['dislike'] = $arr[1];
                        $list[$i]['commentcnt'] = $obj->getCommentCount($val1['id'], '1');
                        $list[$i]['status'] = $this->getCollectedStatus($val1['id'], '1');
                        $list[$i]['type'] = '1';
                        $list[$i]['source'] = '';
                        $list[$i]['comments_for_this_post'] = $obj->getComments($val1['id'], '1');
                        $list[$i]['cdate'] = $val1['cdate'];
                        $time = DatePicker::getTimeStamp($val1['cdate']);
                        $list[$i]['time'] = $time;

                        $i++;
                    }
                }
            }


            if ($val['type'] == 3) {
                if ($video == '4') {
                    //video
                    $this->sql3 = "select v.id, v.cdate, v.released_date, v.title, v.thumbnail, v.description, v.whomItBelongsTo, v.released_date
from tbl_videos v, tbl_fanwires f
where v.released_date!='0000-00-00 00:00:00' and v.belongsTo = '2' and v.id = ? and v.visible = '1' and v.released_date<='$date' and f.released_date<='$date' and v.whomItBelongsTo = f.id";

                    $rs3 = $this->conn->selectSql($this->sql3, array($fan_id));
                    foreach ($rs3 as $val3) {
                        $list[$i]['id'] = $val3['id'];
                        $list[$i]['name'] = $this->getFanwireName($val3['whomItBelongsTo']);
                        // $list[$i]['link'] = SITE_URL."profileInfo?fwrid = ".$val3['whomItBelongsTo']."&ac = 1";
                        $list[$i]['link'] = SITE_URL . $this->getFanwireUrl($val3['whomItBelongsTo']); // "phots?fwrid = " . $val3['whomItBelongsTo'] . "&ac = 1";
                        $list[$i]['title'] = $val3['title'];
                        $list[$i]['photo'] = $val3['thumbnail'];
                        list($width, $height) = getimagesize(DOC_ROOT_PATH . "/photos/" . $val3['thumbnail']);
                        $list[$i]['width'] = $width;
                        $list[$i]['height'] = $height;
                        $list[$i]['description'] = $val3['description'];
                        $title = str_replace("'", "", $val3['title']);
                        $title = str_replace(" ", "-", $title);
                        $title = str_replace(",", "-", $title);
                        $title = str_replace(".", "-", $title);
                        $title = str_replace("(", "-", $title);
                        $title = str_replace(")", "-", $title);
                        $title = str_replace('"', '-', $title);
                        $title = str_replace('#', '', $title);
                        $title = str_replace('&', '', $title);
                        $list[$i]['title_link'] = SITE_URL . $this->getFanwireUrl($val3['whomItBelongsTo']) . "/Videos/" . $title;
                        $arr = explode(",", $obj->getLikesCount($val3['id'], '3'));
                        $list[$i]['like'] = $arr[0];
                        $list[$i]['dislike'] = $arr[1];
                        $list[$i]['commentcnt'] = $obj->getCommentCount($val3['id'], '3');
                        $list[$i]['status'] = $this->getCollectedStatus($val3['id'], '3');
                        $list[$i]['type'] = '3';
                        $list[$i]['source'] = '';
                        $list[$i]['comments_for_this_post'] = $obj->getComments($val3['id'], '3');
                        $list[$i]['cdate'] = $val3['cdate'];
                        $time = DatePicker::getTimeStamp($val3['cdate']);
                        $list[$i]['time'] = $time;
                        $i++;
                    }
                }
            }
            if ($val['type'] == 0) {

                //profil sayfasi
                $this->sql0 = "select f.id,f.name,f.last_updated,f.released_date,f.description,f.url as fanwire,a.url from tbl_fanwires f,tbl_avatar_photos a where f.id=? and f.status='1' and f.id=a.fanwire_id and a.main_avator='1' and f.released_date<='$date'";
                $rs0 = $this->conn->selectSql($this->sql0, array($fan_id));
                foreach ($rs0 as $val0) {
                    $list[$i]['id'] = $val0['id'];
                    $list[$i]['name'] = $val0['name'];
                    $list[$i]['title'] = "";
                    $list[$i]['photo'] = $val0['url'];
                    list($width, $height) = getimagesize(DOC_ROOT_PATH . "/photos/" . $val0['url']);
                    $list[$i]['width'] = $width;
                    $list[$i]['height'] = $height;
                    $list[$i]['description'] = $val0['description'];
// $list[$i]['link'] = SITE_URL."profileInfo?fwrid=".$val0['id']."&ac=1";
                    $list[$i]['link'] = SITE_URL . $val0['fanwire']; //"phots?fwrid=" . $val0['id'] . "&ac=1";
                    $list[$i]['title_link'] = "";
                    $arr = explode(",", $obj->getLikesCount($val0['id'], '0'));
                    $list[$i]['like'] = $arr[0];
                    $list[$i]['dislike'] = $arr[1];
                    $list[$i]['commentcnt'] = $obj->getCommentCount($val0['id'], '0');
                    $list[$i]['status'] = $this->getCollectedStatus($val0['id'], '0');
                    $list[$i]['type'] = '0';
                    $list[$i]['source'] = '';
                    $list[$i]['comments_for_this_post'] = $obj->getComments($val0['id'], '0');
                    $list[$i]['cdate'] = $val0['last_updated'];
                    $time = DatePicker::getTimeStamp($val0['last_updated']);
                    $list[$i]['time'] = $time;

                    $i++;
                }
            }
        }
        //echo "<pre>";print_r($list);exit;
        return $list;
    }

    public function getOtherNewsAboutFanwire($fanwireid) {
        $obj = new Users();
        $sql = '(select id,id as fanwire_id,last_updated as cdate, 0 as type from tbl_fanwires where id in (\'' . $fanwireid . '\'))';
        $sql .= " union (select id,pid as fanwire_id,cdate,1 as type  from tbl_albums where belongsTo='2' and pid in (" . $fanwireid . ")) ";
        $sql .= " union (select id,user_id as fanwire_id,cdate,2 as type  from tbl_articles where visible='1' and released_date!='0000-00-00 00:00:00' and belongsTo='2' and user_id in (" . $fanwireid . "))";
        $sql .= " union (select id,whomItBelongsTo as fanwire_id,cdate,3 as type  from tbl_videos where released_date!='0000-00-00 00:00:00' and belongsTo='2' and whomItBelongsTo in (" . $fanwireid . "))";
        $sql .=" order by cdate desc";
        $rs = $this->conn->selectSql($sql, array());
        $date = date("Y-m-d H:i:s");
        $list = array();
        $i = 0;
        foreach ($rs as $val) {
            $fan_id = $val['id'];
            if ($val['type'] == 2) {
                $this->sql2 = "select a.id, a.cdate, a.released_date, a.article_from, a.title, a.description, a.photo, a.source, a.user_id
from tbl_articles a, tbl_fanwires f where a.released_date!='0000-00-00 00:00:00' and a.belongsTo = '2' and a.id = ? and a.visible = '1' and a.released_date<='$date' and f.released_date<='$date' and a.user_id = f.id";
                $rs2 = $this->conn->selectSql($this->sql2, array($fan_id));
                foreach ($rs2 as $val2) {
                    $list[$i]['id'] = $val2['id'];
                    $list[$i]['name'] = $this->getFanwireName($val2['user_id']);
                    $list[$i]['title'] = $val2['title'];
                    if ($val2['source'] == '1')
                        $list[$i]['title_link'] = SITE_URL . $this->getFanwireUrl($val2['user_id']) . "/FB/" . $val2['id'];
                    else
                        $list[$i]['title_link'] = SITE_URL . $this->getFanwireUrl($val2['user_id']) . "/Articles/" . YoutubeUrl::fixurl($list[$i]['title']);
                    $list[$i]['photo'] = $val2['photo'];
                    if ($val2['photo'] == "") {
                        $list[$i]['fanwire_profile_img'] = $this->getFanwireProfileImage($val2['user_id']);
                    }
                    list($width, $height) = getimagesize(DOC_ROOT_PATH . "/photos/" . $val2['photo']);
                    $list[$i]['width'] = $width;
                    $list[$i]['height'] = $height;
                    $list[$i]['source'] = $val2['source'];
                    $list[$i]['article_from'] = $val2['article_from'];
                    if ($val2['source'] == 0)
                        $list[$i]['description'] = $val2['description'];
                    else
                        $list[$i]['description'] = YoutubeUrl::replace_plain_text_link(($val2['description']));
                    if ($list[$i]['article_from'] == "Twitter") {
                        $list[$i]['description'] = preg_replace('/(#\w+)/', '<span class=\'twitcolor\'>\1</span>', $list[$i]['description']);
                    }
                    if ($val2['source'] == 1) {
                        $title = substr($val2['description'], 0, 10);
                    }
                    $list[$i]['type'] = '2';
                    $list[$i]['link'] = SITE_URL . $this->getFanwireUrl($val2['user_id']); // "phots?fwrid = " . $val2['user_id'] . "&ac = 1";
                    $list[$i]['cdate'] = $val2['cdate'];
                    $time = DatePicker::getTimeStamp($val2['cdate']);
                    $list[$i]['time'] = $time;

                    $i++;
                }
            }

            if ($val['type'] == 1) {
                $this->sql1 = "select a.id, a.cdate, a.released_date, a.name, a.album_url, a.status, a.pid,a.source
from tbl_albums a, tbl_fanwires f
where a.belongsTo = '2' and a.id = ? and a.visible = '1' and a.released_date<='$date' and f.released_date<='$date' and a.pid = f.id";
                $rs1 = $this->conn->selectSql($this->sql1, array($fan_id));
                // echo "<pre>";print_r($rs1);
                foreach ($rs1 as $val1) {
                    $list[$i]['id'] = $val1['id'];
                    $list[$i]['name'] = $this->getFanwireName($val1['pid']);
                    $list[$i]['link'] = SITE_URL . $this->getFanwireUrl($val1['pid']); //"phots?fwrid = " . $val1['pid'] . "&ac = 1";
                    $list[$i]['title'] = $val1['name'];
                    $list[$i]['photo'] = $val1['album_url'];
                    list($width, $height) = getimagesize(DOC_ROOT_PATH . "/photos/" . $val1['album_url']);
                    $list[$i]['width'] = $width;
                    $list[$i]['height'] = $height;
                    $list[$i]['description'] = '';
                    if ($val1['name'] == '') {
                        $title = $val1['id'];
                        $list[$i]['title_link'] = SITE_URL . $this->getFanwireUrl($val1['pid']) . "/Instagram/" . $title;
                    } else {
                        $list[$i]['title_link'] = SITE_URL . $this->getFanwireUrl($val1['pid']) . "/Gallery/" . YoutubeUrl::fixurl($list[$i]['title']);
                    }

                    if ($val1['source'] == 'Instagram') {
                        $list[$i]['type'] = '4';
                    } else {
                        $list[$i]['type'] = '1';
                    }
                    $list[$i]['source'] = '';
                    $list[$i]['cdate'] = $val1['cdate'];
                    $time = DatePicker::getTimeStamp($val1['cdate']);
                    $list[$i]['time'] = $time;

                    $i++;
                }
            }
            if ($val['type'] == 3) {

                $this->sql3 = "select v.id, v.cdate, v.released_date, v.title, v.thumbnail, v.description, v.whomItBelongsTo, v.released_date
from tbl_videos v, tbl_fanwires f
where v.released_date!='0000-00-00 00:00:00' and v.belongsTo = '2' and v.id = ? and v.visible = '1' and v.released_date<='$date' and f.released_date<='$date' and v.whomItBelongsTo = f.id";

                $rs3 = $this->conn->selectSql($this->sql3, array($fan_id));
                foreach ($rs3 as $val3) {
                    $list[$i]['id'] = $val3['id'];
                    $list[$i]['name'] = $this->getFanwireName($val3['whomItBelongsTo']);
                    // $list[$i]['link'] = SITE_URL."profileInfo?fwrid = ".$val3['whomItBelongsTo']."&ac = 1";
                    $list[$i]['link'] = SITE_URL . $this->getFanwireUrl($val3['whomItBelongsTo']); // "phots?fwrid = " . $val3['whomItBelongsTo'] . "&ac = 1";
                    $list[$i]['title'] = $val3['title'];
                    $list[$i]['photo'] = $val3['thumbnail'];
                    list($width, $height) = getimagesize(DOC_ROOT_PATH . "/photos/" . $val3['thumbnail']);
                    $list[$i]['width'] = $width;
                    $list[$i]['height'] = $height;
                    $list[$i]['description'] = $val3['description'];
                    $list[$i]['type'] = '3';
                    $list[$i]['title_link'] = SITE_URL . $this->getFanwireUrl($val3['whomItBelongsTo']) . "/Videos/" . YoutubeUrl::fixurl($list[$i]['title']);
                    $list[$i]['source'] = '';
                    $list[$i]['cdate'] = $val3['cdate'];
                    $time = DatePicker::getTimeStamp($val3['cdate']);
                    $list[$i]['time'] = $time;
                    $i++;
                }
            }

            if ($val['type'] == 0) {
                $this->sql0 = "select f.id,f.name,f.last_updated,f.released_date,f.description,f.url as fanwire,a.url from tbl_fanwires f,tbl_avatar_photos a where f.id=? and f.status='1' and f.id=a.fanwire_id and a.main_avator='1' and f.released_date<='$date'";
                $rs0 = $this->conn->selectSql($this->sql0, array($fan_id));
                foreach ($rs0 as $val0) {
                    $list[$i]['id'] = $val0['id'];
                    $list[$i]['name'] = $val0['name'];
                    $list[$i]['title'] = "";
                    $list[$i]['title_link'] = "";
                    $list[$i]['photo'] = $val0['url'];
                    list($width, $height) = getimagesize(DOC_ROOT_PATH . "/photos/" . $val0['url']);
                    $list[$i]['width'] = $width;
                    $list[$i]['height'] = $height;
                    $list[$i]['description'] = $val0['description'];
                    $list[$i]['link'] = SITE_URL . $val0['fanwire']; //"phots?fwrid=" . $val0['id'] . "&ac=1";
                    $list[$i]['type'] = '0';
                    $list[$i]['source'] = '';
                    $list[$i]['cdate'] = $val0['last_updated'];
                    $time = DatePicker::getTimeStamp($val0['last_updated']);
                    $list[$i]['time'] = $time;
                    $i++;
                }
            }
        }
        // echo "<pre>";
        //print_r($list);
        return $list;
    }

}

?>
