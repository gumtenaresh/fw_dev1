<?php

/**
 * Description of articles  *
 * @author gangaraju
 */
class articlesController {

    public function indexAction() {
        // authentication
        if (empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE and $_SESSION['admintype'] != SUBADMIN_TYPE)) {
            header("Location:" . ADMIN_URL);
            exit;
        }
    }

    /**
     *displaying articles list
     * @global type $fc 
     */

    public function articlesListAction() {
        global $fc;
        $data = array();
        // authontication
        if (empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE and $_SESSION['admintype'] != SUBADMIN_TYPE)) {
            header("Location:" . ADMIN_URL);
            exit();
        }
        $sort = $_REQUEST['sort']; 
        if($sort == "desc")
            $data['sort'] = "asc";
        else 
            $data['sort']  = "desc";
        $fanwire_id = $_REQUEST['id'];
        $refid = $_REQUEST['refid'];
        $Obj = new Articles();
        $fan = new Fanwires();
        if(isset($_REQUEST['submit']))
            {
                $cdate = $fan->dateformat($_REQUEST['cdate']);
                $Obj->updateDate($_REQUEST,$cdate);   
                header("Location:" . ADMIN_URL."articles/articlesList?id=".$fanwire_id."#".$refid);
            }
         if(isset($_REQUEST['release']))
            {
                $dt_release = $fan->dateformat($_REQUEST['released_date']);
                $Obj->updateReleasedDate($_REQUEST,$dt_release);   
                header("Location:" . ADMIN_URL."articles/articlesList?id=".$fanwire_id."#".$refid);
            }   
            
        if(isset($_REQUEST['deletebtn']))
        {
            extract($_REQUEST);
            if(isset($articlechk))
            {
                $articleids = implode(",",$articlechk);
                $Obj->deleteArticles($articleids);
            }
            
        }
        if(isset($_REQUEST['releasebtn']))
        {
            extract($_REQUEST);
            if(isset($articlechk))
            {
                $articleids = implode(",",$articlechk);
                $Obj->releaseArticles($articleids);
            }
        }
        
        $data['date']=date("Y-m-d H:i:s");    
        $cat = $Obj->getArticlesList($fanwire_id,$sort);
        $data['list'] = $cat['list'];
        $data['navigation'] = $cat['navigation'];
        $data['ADMIN'] = ADMIN;
        $data['fanwire_id'] = $fanwire_id;
        $view = new View();
        $view->loadView($data, ADMIN . "articles/articlesList");
    } 
    
    /**
     *delete article 
     * @global type $fc 
     */
     public function deleteArticleAction() {
        global $fc;        
        // authontication
        if (empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE and $_SESSION['admintype'] != SUBADMIN_TYPE)) {
            header("Location:" . ADMIN_URL);
            exit();
        }
        
        $id = $_REQUEST['id'];
        $fanwire_id = $_REQUEST['fanwire_id'];
        $Obj = new Articles();
        $Obj->deleteArticle($id);
        header("Location:".ADMIN_URL."articles/articlesList?id=".$fanwire_id);
        
    }
    
     public function addArticlesAction() {
        global $fc;        
        // authontication
        if (empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE and $_SESSION['admintype'] != SUBADMIN_TYPE)) {
            header("Location:" . ADMIN_URL);
            exit();
        }
        $usr = new Users();
        $obj = new Photos();
        $object = new Articles();
        $fan = new Fanwires();
        $data['fanwires'] = $obj->getFanwires();
        $fan_id = $_REQUEST['id'];
        if(isset($fan_id))
        {
            $fan_name = $fan->getFanwireName($fan_id);
        }
        $data['fan_id'] = $fan_id; 
        $data['fan_name'] = $fan_name;
        if(isset($_REQUEST['add']))
        {
           if ($handle = opendir('../tmp/')) {
                $dir_array = array();
                while (false !== ($file = readdir($handle))) {
                    if ($file != "." && $file != ".." && preg_match("/^" . $_SESSION['adminid'] . "_/", $file)) { {
                        $dir_array[] = $file;
                        copy("../tmp/" . $file, "../photos/" . $file);
                        @unlink("../tmp/" . $file);
                        $usr->createThumbs($file, DOC_ROOT_PATH . "../photos/", DOC_ROOT_PATH . "../photos/thumbs/", '250');
                    }
                }
                }
            $object->insertArticle($_REQUEST, $dir_array);
           }
        }
        
        $data['ADMIN'] = ADMIN;
        $data['class'] = "fanwires";
        $data['menu'] = "media";
        $data['item'] = "articles";
        $view = new View();
        $view->loadView($data, ADMIN . "articles/addArticles");      
    }
    
     public function editArticleAction() {
        global $fc;        
        // authontication
        if (empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE and $_SESSION['admintype'] != SUBADMIN_TYPE)) {
            header("Location:" . ADMIN_URL);
            exit();
        }
        $usr = new Users();
        $obj = new Photos();
        $object = new Articles();
        $data['fanwires'] = $obj->getFanwires();
        $article_id = $_REQUEST['id'];
        $fanwire_id = $_REQUEST['fanwire_id'];
        
        if(isset($_REQUEST['add']))
        {
           if ($handle = opendir('../tmp/')) {
                $dir_array = array();
                while (false !== ($file = readdir($handle))) {
                    if ($file != "." && $file != ".." && preg_match("/^" . $_SESSION['adminid'] . "_/", $file)) { {
                        $dir_array[] = $file;
                        copy("../tmp/" . $file, "../photos/" . $file);
                        @unlink("../tmp/" . $file);
                        $usr->createThumbs($file, DOC_ROOT_PATH . "../photos/", DOC_ROOT_PATH . "../photos/thumbs/", '250');
                    }
                }
                }
            $object->editArticle($_REQUEST, $dir_array);
           }
        }        
        $data['article'] = $object->getArticleDetails($article_id);
        $data['article_images'] = $object->getArticleImages($article_id);
        $data['keywords'] = $data['article'][0]['keywords'];
        $data['additional_fans'] = $data['article'][0]['additional_fans'];        
//        echo "<pre>";
//        print_r($data['article_images']);
//        echo "</pre>";
        $data['ADMIN'] = ADMIN;
        $data['class'] = "fanwires";
        $data['menu'] = "media";
        $data['item'] = "articles";
        $data['fanwire_id'] = $fanwire_id;
        $view = new View();
        $view->loadView($data, ADMIN . "articles/editArticle");      
    }
    
  public function deleteFacebookPostAction() {
        global $fc;        
        // authontication
        if (empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE and $_SESSION['admintype'] != SUBADMIN_TYPE)) {
            header("Location:" . ADMIN_URL);
            exit();
        }
        
        $id = $_REQUEST['id'];
        $fanwire_id = $_REQUEST['fanwire_id'];
        $Obj = new Articles();
        $Obj->deleteArticle($id);
        header("Location:".ADMIN_URL."fanwires/facebookList?id=".$fanwire_id);
        
    }
    public function deleteTwitterPostAction() {
        global $fc;        
        // authontication
        if (empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE and $_SESSION['admintype'] != SUBADMIN_TYPE)) {
            header("Location:" . ADMIN_URL);
            exit();
        }
        
        $id = $_REQUEST['id'];
        $fanwire_id = $_REQUEST['fanwire_id'];
        $Obj = new Articles();
        $Obj->deleteArticle($id);
        header("Location:".ADMIN_URL."fanwires/twitterList?id=".$fanwire_id);
        
    }
}
?>