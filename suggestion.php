<?php
header('Content-Type: text/html; charset=utf-8');
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>
    <body>
        <?php
        if (!isset($_SESSION['name'])) {
            $searchactionurl = 'http://' . $_SERVER['HTTP_HOST'] . '/fanwire';
        } else {
            $searchactionurl = 'http://' . $_SERVER['HTTP_HOST'] . '/fanwire/myFanwire';
        }
        ?>
        <form method="POST" action="<?php echo $searchactionurl ?>" id="quicksearch_form">
            <input type="hidden" name="searchterm" name="searchterm" value="">
            <input type="hidden" name="searchcriteria" name="searchcriteria" value="">
        </form>
        <script>
            function do_quicksearch(searchterm,searchcriteria){
                var frm_element = document.getElementById('quicksearch_form');
                frm_element.searchterm.value = searchterm;
                frm_element.searchcriteria.value = searchcriteria;
                frm_element.submit();
            }
        </script>
        <?php
        $searchterm = $_POST["search"];
        if (strlen($searchterm) < 3)
            die();
        require('config.inc.php');
        $con = mysql_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD) or die('Could not connect to the server!');
        mysql_select_db(DB_DATABASE) or die('Could not select a database!');
        $searchterm = mysql_real_escape_string($searchterm);
        //also check keywords
        $sql_keyword_fanwire = "SELECT tbl_fanwires.name FROM tbl_fanwires";
        $sql_keyword_fanwire .= " INNER JOIN tbl_keywords ON (tbl_fanwires.id = tbl_keywords.referer_id)";
        $sql_keyword_fanwire .= " WHERE keyword = '" . $searchterm . "' LIMIT 1";
        $result_keyword_fanwire = mysql_query($sql_keyword_fanwire);
        if (mysql_num_rows($result_keyword_fanwire) == 1) {
            $row_keyword_fanwire = mysql_fetch_array($result_keyword_fanwire);
            $searchterm = $row_keyword_fanwire['name'];
        }
        mysql_free_result($result_keyword_fanwire);
        //FANWIRES
        $max_fanwire_count = 3;
        $sql_fanwires = "SELECT tbl_fanwires.id as fid,name,tbl_fanwires.url as link,description,tbl_avatar_photos.url as imageurl FROM tbl_fanwires";
        $sql_fanwires .= " LEFT JOIN tbl_avatar_photos ON (tbl_fanwires.id = tbl_avatar_photos.fanwire_id)";
        //$sql_fanwires .= " LEFT JOIN tbl_keywords ON (tbl_fanwires.id = tbl_keywords.referer_id)";
        //$sql_fanwires .= " WHERE name LIKE '%".$searchterm."%' OR tbl_keywords.keyword LIKE '%".$searchterm."%'";
        $sql_fanwires .= " WHERE name LIKE '%" . $searchterm . "%'";
        $sql_fanwires .= " GROUP BY tbl_fanwires.id";
        $sql_fanwires .= " LIMIT 0,$max_fanwire_count";
        $result_fanwires = mysql_query($sql_fanwires);
        $count_fanwires = mysql_num_rows($result_fanwires);
        //PHOTOS
        $max_photos_count = 3;
        $sql_photos = "SELECT tbl_albums.name,tbl_albums.description,album_url as imageurl,tbl_fanwires.url as fw_url FROM tbl_albums";
        $sql_photos .= " LEFT JOIN tbl_fanwires ON (tbl_albums.pid = tbl_fanwires.id)";
        $sql_photos .= " WHERE tbl_albums.belongsTo='2' AND (tbl_albums.name LIKE '%" . $searchterm . "%' OR tbl_fanwires.name LIKE '%" . $searchterm . "%')";
        $sql_photos .= " GROUP BY tbl_albums.id";
        $sql_photos .= " LIMIT 0,$max_photos_count";
        $result_photos = mysql_query($sql_photos);
        $count_photos = mysql_num_rows($result_photos);
        //ARTICLES
        $max_articles_count = 3;
        $sql_articles = "SELECT title,tbl_articles.description,article_from,photo as imageurl,tbl_fanwires.url as fw_url,tbl_avatar_photos.url as avatarimg FROM tbl_articles";
        $sql_articles .= " LEFT JOIN tbl_fanwires ON (tbl_articles.user_id = tbl_fanwires.id)";
        $sql_articles .= " LEFT JOIN tbl_avatar_photos ON (tbl_articles.user_id = tbl_avatar_photos.fanwire_id)";
        $sql_articles .= " WHERE tbl_articles.visible='1' and tbl_articles.released_date!='0000-00-00 00:00:00' and tbl_articles.belongsTo='2' AND (title LIKE '%" . $searchterm . "%' OR tbl_fanwires.name LIKE '%" . $searchterm . "%')";
        $sql_articles .= " AND source NOT IN('1','2')";
        $sql_articles .= " GROUP BY tbl_articles.id";
        $sql_articles .= " LIMIT 0,$max_articles_count";
        $result_articles = mysql_query($sql_articles);
        $count_articles = mysql_num_rows($result_articles);
        //VIDEOS
        $max_videos_count = 3;
        $sql_videos = "SELECT title,tbl_videos.description,thumbnail as imageurl,tbl_fanwires.url as fw_url FROM tbl_videos";
        $sql_videos .= " LEFT JOIN tbl_fanwires ON (tbl_videos.WhomItBelongsTo = tbl_fanwires.id)";
        $sql_videos .= " WHERE tbl_videos.released_date!='0000-00-00 00:00:00' and tbl_videos.belongsTo='2' AND (title LIKE '%" . $searchterm . "%' OR tbl_fanwires.name LIKE '%" . $searchterm . "%')";
        $sql_videos .= " GROUP BY tbl_videos.id";
        $sql_videos .= " LIMIT 0,$max_videos_count";
        $result_videos = mysql_query($sql_videos);
        $count_videos = mysql_num_rows($result_videos);
        $total_result = $count_fanwires + $count_photos + $count_articles + $count_videos;
        if ($total_result > 0) {
            ?>
            <!--FANWIRES-->
            <?php
            if ($count_fanwires > 0) {
                ?>
                <p id="searchresults">
                    <span class="category">PROFILES</span>
                    <?php
                    while ($row_fanwires = mysql_fetch_array($result_fanwires)) {
                        $description = strip_tags($row_fanwires['description']);
                        if (strlen($description) > 90)
                            $description = trim(substr($description, 0, 90)) . '..';
                        if ($row_fanwires['imageurl'] != '') {
                            $image = IMAGE_URL . $row_fanwires['imageurl'];
                        } else {
                            $image = SITE_URL . "/views/images/your_fanwire_profile_normal.png";
                        }
                        ?>  
                        <a href="<?php echo $row_fanwires['link'] ?>">
                            <img alt="<?php echo strip_tags($row_fanwires['name']) ?>" src="<?php echo $image ?>" <?php echo thumbnail_sizefix($image) ?> />
                            <span class="searchheading"><?php echo strip_tags($row_fanwires['name']) ?></span>
                            <span><?php echo $description ?></span>
                        </a>
                    <?php } ?>	   
                </p>
                <?php
            } //if($count_fanwires>0)
            ?>	
            <!--FANWIRES ENDS -->	
            <!--PHOTOS-->
            <?php
            if ($count_photos > 0) {
                ?>
                <p id="searchresults">
                    <span class="category">PHOTOS</span>
                    <?php
                    while ($row_photos = mysql_fetch_array($result_photos)) {

                        $title = strip_tags($row_photos['name']);
                        if (strlen($title) > 40)
                            $title = trim(substr($title, 0, 40)) . '..';
                        $description = strip_tags($row_photos['description']);
                        if (strlen($description) > 90)
                            $description = trim(substr($description, 0, 90)) . '..';
                        if ($row_photos['imageurl'] != '') {
                            $image = IMAGE_URL . $row_photos['imageurl'];
                        } else {
                            $image = SITE_URL . "/views/images/your_fanwire_profile_normal.png";
                        }
                        ?>  
                        <a href="<?php echo $row_photos['fw_url'] ?>/Gallery/<?php echo fixurl($title) ?>">
                            <img alt="" src="<?php echo $image ?>" <?php echo thumbnail_sizefix($image) ?>/>
                            <span class="searchheading"><?php echo $title ?></span>
                            <span><?php echo $description ?></span>
                        </a>
                    <?php } ?>	   
                </p>
                <?php
            } //if($count_photos>0)
            ?>	
            <!--PHOTOS ENDS -->		
            <!--ARTICLES-->
            <?php
            if ($count_articles > 0) {
                ?>
                <p id="searchresults">
                    <span class="category">ARTICLES</span>
                    <?php
                    while ($row_articles = mysql_fetch_array($result_articles)) {

                        $title = strip_tags($row_articles['title']);
                        if (strlen($title) > 60)
                            $title = trim(substr($title, 0, 60)) . '..';

                        $description = str_replace("&#039;", "'", $row_articles['description']);
                        $description = strip_tags($description);


                        if (strlen($description) > 90)
                            $description = trim(substr($description, 0, 90)) . '..';

                        if ($row_articles['imageurl'] != '') {
                            $image = IMAGE_URL . $row_articles['imageurl'];
                        } else {
                            $image = IMAGE_URL . $row_articles['avatarimg'];
                        }
                        ?>  
                        <a href="<?php echo $row_articles['fw_url'] ?>/Articles/<?php echo fixurl($title) ?>">
                            <img alt="<?php echo $title ?>" src="<?php echo $image ?>" <?php echo thumbnail_sizefix($image) ?> />
                            <span class="searchheading"><?php echo $title ?></span>
                            <span><?php echo $description ?></span>
                        </a>
                    <?php } ?>	   
                </p>
                <?php
            } //if($count_articles>0)
            ?>	
            <!--ARTICLES ENDS -->		



            <!--VIDEOS-->
            <?php
            if ($count_videos > 0) {
                ?>
                <p id="searchresults">
                    <span class="category">VIDEOS</span>
                    <?php
                    while ($row_videos = mysql_fetch_array($result_videos)) {

                        $title = strip_tags($row_videos['title']);
                        if (strlen($title) > 40)
                            $title = trim(substr($title, 0, 40)) . '..';

                        $description = strip_tags($row_videos['description']);
                        if (strlen($description) > 90)
                            $description = trim(substr($description, 0, 90)) . '..';

                        if ($row_videos['imageurl'] != '') {
                            $image = IMAGE_URL . $row_videos['imageurl'];
                        } else {
                            $image = SITE_URL . "/views/images/your_fanwire_profile_normal.png";
                        }
                        ?>  
                        <a href="<?php echo $row_videos['fw_url'] ?>/Videos/<?php echo fixurl($title) ?>">
                            <img alt="<?php echo $title ?>" src="<?php echo $image ?>" <?php echo thumbnail_sizefix($image) ?> />
                            <span class="searchheading"><?php echo $title ?></span>
                            <span><?php echo $description ?></span>
                        </a>
                    <?php } ?>	   
                </p>
                <?php
            } //if($count_videos>0)
            ?>	
            <!--VIDEOS ENDS -->		
            <?php
        } //if($total_result>0)
        $showsearched = $searchterm;
        if (strlen($showsearched) > 20)
            $showsearched = trim(substr($showsearched, 0, 20)) . '..';
        ?>

        <?php if (strlen($showsearched) > 3) { ?>
            <p id="searchresults" style="background-color:#02A0BF;width:320px;font-size:12px;">
                <span class="seperator">
                    <a href="#" onclick="javascript:document.getElementById('searchform').submit();">
                        <strong>Click for more results about "<?php echo $showsearched ?>"</strong>
                    </a>
                </span>
                <br class="break" />
            </p>
            <?
        }
        ?>

        <?
        mysql_free_result($result_fanwires);
        mysql_free_result($result_photos);
        mysql_free_result($result_articles);
        mysql_free_result($result_videos);
        mysql_close($con);

        function fixurl($title) {
            $title = str_replace("'", "", $title);
            $title = str_replace(" ", "-", $title);
            $title = str_replace(",", "-", $title);
            $title = str_replace(".", "-", $title);
            $title = str_replace("(", "-", $title);
            $title = str_replace(")", "-", $title);
            $title = str_replace('"', '-', $title);
            $title = str_replace('#', '', $title);
            $title = str_replace('&', '', $title);
            return $title;
        }

        function thumbnail_sizefix($target) {
            //$img = getimagesize("http://www.fanwire.com/" . $target);
            $img = getimagesize($target);
            $max_width = 80;
            $max_height = 46;
            $old_width = $img[0];
            $old_height = $img[1];
            $scale = min($max_width / $old_width, $max_height / $old_height);
            $new_width = ceil($scale * $old_width);
            $new_height = ceil($scale * $old_height);
            return "width=\"$new_width\" height=\"$new_height\"";
        }
        ?>
    </body>
</html>











