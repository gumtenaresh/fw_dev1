<?php

/**
 * Description of Articles
 * @author naresh
 */
class fannetwork {

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
     * getLikesCount
     * @param type $id
     * @return array
     */
    public function getLikesCount($id) {
        $sql = "select sum(`like`) as `like`,sum(`dislike`) as `dislike` from tbl_fanwire_likes where fanwireid=? ";
        $rs = $this->conn->selectSql($sql, array($id));
        return $data = $rs[0]['like'] . "," . $rs[0]['dislike'];
    }

    /**
     * getfans
     * @param type $aid
     * @return array
     */
    public function getfans($aid) {
        $this->sql = "SELECT tbl_user_friends.*, tbl_users.* FROM  tbl_user_friends inner join tbl_users on tbl_user_friends.accepter_user_id=tbl_users.id where tbl_user_friends.requester_user_id=? and tbl_user_friends.status=1";
        $this->rs = $this->conn->selectSql($this->sql, array($aid));
        $i = 0;
        $obj = new Users(); //This object is created to fetch commentcount display
        foreach ($this->rs as $value) {
            $data[$i]['id'] = $value['id'];
            $data[$i]['name'] = $value['name'];
            $data[$i]['description'] = $value['description'];
            $data[$i]['photo'] = $this->obj->getAvtharPhoto($value['id']);
            $data[$i]['cover_photo'] = $this->obj->getCoverPhoto($value['id']);
            $data[$i]['commentcnt'] = $obj->getCommentCount($value['id'], '3'); //fetch commentcount diplay number
            $data[$i]['comments_for_this_post'] = $obj->getComments($value['id'], 2); //fetch comments display
            //below code this to fetch likes and dislikes display in onload
            $arr = explode(",", $this->getLikesCount($value['id']));
            $data[$i]['like'] = $arr[0];
            $data[$i]['dislike'] = $arr[1];

            $i++;
        }
        return $data;
    }

}