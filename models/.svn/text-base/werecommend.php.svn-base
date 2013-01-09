<?php

/**
 * Description of Articles
 * @author naresh
 */
class werecommend {

    private $conn = null;
    private $this = null;
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

    /**
     * destroying memory
     */
    function __destruct() {
        unset($this->conn);
        unset($this->rs);
        unset($this->sql);
        unset($this->data);
    }

    /**
     *
     * @param type $aid
     * @return type
     */
    public function getLikesCount($id) {
        $sql = "select sum(`like`) as `like`,sum(`dislike`) as `dislike` from tbl_fanwire_likes where fanwireid=? ";
        $rs = $this->conn->selectSql($sql, array($id));
        return $data = $rs[0]['like'] . "," . $rs[0]['dislike'];
    }

    /**
     * getCelebrity
     * @param integer $aid id  of the fanwire
     * @return array details of fanwire
     */
    public function getCelebrity($aid) {
        $this->sql = "SELECT p.* FROM  tbl_fanwires p, tbl_users u where p.category1=? and u.id=?";
        $this->rs = $this->conn->selectSql($this->sql, array(2, $aid));
        $i = 0;
        $obj = new Users(); //This object is created to fetch commentcount display
        foreach ($this->rs as $value) {
            $data[$i]['id'] = $value['id'];
            $data[$i]['name'] = $value['name'];
            $data[$i]['description'] = $value['description'];
            $data[$i]['photo'] = $this->obj->getAvtharPhoto($value['id']);
            $data[$i]['cover_photo'] = $this->obj->getCoverPhoto($value['id']);
            $data[$i]['commentcnt'] = $obj->getCommentCount($value['id'], 0); //fetch commentcount diplay number
            $data[$i]['comments_for_this_post'] = $obj->getComments($value['id'], 0); //fetch comments display
            //below code this to fetch likes and dislikes display in onload
            $arr = explode(",", $this->getLikesCount($value['id']));
            $data[$i]['like'] = $arr[0];
            $data[$i]['dislike'] = $arr[1];
            $i++;
        }
        return $data;
    }

    /**
     * getFashion
     * @param integer $aid id  of the fanwire
     * @return array details of fanwire
     */
    public function getFashion($aid) {
        $this->sql = "SELECT p.* FROM  tbl_fanwires p, tbl_users u where p.category1=? and u.id=?";
        $this->rs = $this->conn->selectSql($this->sql, array(3, $aid));
        $i = 0;
        $obj = new Users(); //This object is created to fetch commentcount display
        foreach ($this->rs as $value) {
            $data[$i]['id'] = $value['id'];
            $data[$i]['name'] = $value['name'];
            $data[$i]['description'] = $value['description'];
            $data[$i]['photo'] = $this->obj->getAvtharPhoto($value['id']);
            $data[$i]['cover_photo'] = $this->obj->getCoverPhoto($value['id']);
            $data[$i]['commentcnt'] = $obj->getCommentCount($value['id'], 0); //fetch commentcount diplay number
            $data[$i]['comments_for_this_post'] = $obj->getComments($value['id'], 0); //fetch comments display
            //below code this to fetch likes and dislikes display in onload
            $arr = explode(",", $this->getLikesCount($value['id']));
            $data[$i]['like'] = $arr[0];
            $data[$i]['dislike'] = $arr[1];
            $i++;
        }
        //print_r($data);
        return $data;
    }

    /**
     * getNews
     * @param integer $aid id  of the fanwire
     * @return array details of fanwire
     */
    public function getNews($aid) {
        $this->sql = "SELECT p.* FROM  tbl_fanwires p, tbl_users u where p.category1=? and u.id=?";
        $this->rs = $this->conn->selectSql($this->sql, array(4, $aid));
        $i = 0;
        $obj = new Users(); //This object is created to fetch commentcount display
        foreach ($this->rs as $value) {
            $data[$i]['id'] = $value['id'];
            $data[$i]['name'] = $value['name'];
            $data[$i]['description'] = $value['description'];
            $data[$i]['photo'] = $this->obj->getAvtharPhoto($value['id']);
            $data[$i]['cover_photo'] = $this->obj->getCoverPhoto($value['id']);
            $data[$i]['commentcnt'] = $obj->getCommentCount($value['id'], 0); //fetch commentcount diplay number
            $data[$i]['comments_for_this_post'] = $obj->getComments($value['id'], 0); //fetch comments display
            //below code this to fetch likes and dislikes display in onload
            $arr = explode(",", $this->getLikesCount($value['id']));
            $data[$i]['like'] = $arr[0];
            $data[$i]['dislike'] = $arr[1];
            $i++;
        }
        return $data;
    }

    /**
     * getMusic
     * @param integer $aid id  of the fanwire
     * @return array details of fanwire
     */
    public function getMusic($aid) {
        $this->sql = "SELECT p.* FROM  tbl_fanwires p, tbl_users u where p.category1=? and u.id=?";
        $this->rs = $this->conn->selectSql($this->sql, array(5, $aid));
        $i = 0;
        $obj = new Users(); //This object is created to fetch commentcount display
        foreach ($this->rs as $value) {
            $data[$i]['id'] = $value['id'];
            $data[$i]['name'] = $value['name'];
            $data[$i]['description'] = $value['description'];
            $data[$i]['photo'] = $this->obj->getAvtharPhoto($value['id']);
            $data[$i]['cover_photo'] = $this->obj->getCoverPhoto($value['id']);
            $data[$i]['commentcnt'] = $obj->getCommentCount($value['id'], 0); //fetch commentcount diplay number
            $data[$i]['comments_for_this_post'] = $obj->getComments($value['id'], 0); //fetch comments display
            //below code this to fetch likes and dislikes display in onload
            $arr = explode(",", $this->getLikesCount($value['id']));
            $data[$i]['like'] = $arr[0];
            $data[$i]['dislike'] = $arr[1];
            /* echo "<script>alert($data[$i]['commentcnt'])</script>"; */
            $i++;
        }
        return $data;
    }

    /**
     * getSports
     * @param integer $aid id  of the fanwire
     * @return array details of fanwire
     */
    public function getSports($aid) {
        $this->sql = "SELECT p.* FROM  tbl_fanwires p, tbl_users u where p.category1=? and u.id=?";
        $this->rs = $this->conn->selectSql($this->sql, array(1, $aid));
        $i = 0;
        $obj = new Users(); //This object is created to fetch commentcount display
        foreach ($this->rs as $value) {
            $data[$i]['id'] = $value['id'];
            $data[$i]['name'] = $value['name'];
            $data[$i]['description'] = $value['description'];
            $data[$i]['photo'] = $this->obj->getAvtharPhoto($value['id']);
            $data[$i]['cover_photo'] = $this->obj->getCoverPhoto($value['id']);
            $data[$i]['commentcnt'] = $obj->getCommentCount($value['id'], 0); //fetch commentcount diplay number
            $data[$i]['comments_for_this_post'] = $obj->getComments($value['id'], 0); //fetch comments display
            //below code this to fetch likes and dislikes display in onload
            $arr = explode(",", $this->getLikesCount($value['id']));
            $data[$i]['like'] = $arr[0];
            $data[$i]['dislike'] = $arr[1];
            $i++;
        }
        return $data;
    }

    /**
     * getAvtharPhoto
     * @param integer $fanwireid fanwire id
     * @return string url of the fanwire
     */
    function getAvtharPhoto($fanwireid) {
        $this->sql = "SELECT url from tbl_avatar_photos WHERE fanwire_id = ?";
        $this->rs = $this->conn->selectSql($this->sql, array($fanwireid));
        return $this->rs[0]['url'];
    }

    /**
     * fanwirelikes
     */
    public function fanwirelikesAction() {
        $fanwireid = $_REQUEST['fanwireid'];
        $like = $_REQUEST['like'];
        $dislike = $_REQUEST['dislike'];
        $obj = new Users();
        echo $obj->fanwireLikes($fanwireid, $_SESSION['id'], $like, $dislike);
    }

}