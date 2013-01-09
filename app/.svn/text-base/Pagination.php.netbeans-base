<?php

class Pagination {

    protected $total_records,
            $current_page,
            $total_pages,
            $page_size,
            $scroll_size;

    function __construct($page_size, $scroll_size) {
        $this->page_size = $page_size;
        $this->scroll_size = $scroll_size;

        $this->current_page = empty($_REQUEST['page']) ? 1 : $_REQUEST['page'];
    }

    public function getLimitQry() {
        return " LIMIT " . (($this->current_page - 1) * $this->page_size) . ", " . $this->page_size;
    }

    public function showNavigation($total) {

        //Find page url
        $protocol = (stripos($_SERVER['SERVER_PROTOCOL'], 'https') === false) ? 'http' : 'https';

        $r_url = explode("?", $_SERVER['REQUEST_URI']);

        $requestUrl = $r_url[0];

        if (isset($r_url[1])) {
            $query_string = $r_url[1];    //remove page query string, if it has.
            $t = explode("&", $query_string);
            foreach ($t as $key => $val) {
                $t1 = explode("=", $val);
                if ($t1[0] == 'page') {
                    unset($t[$key]);
                    break;
                }
            }
            $query_string = implode("&", $t);
        }else
            $query_string = "";
        if ($query_string != "")
            $query_string = "&" . $query_string;

        $link_string = $protocol . '://' . $_SERVER['HTTP_HOST'] . $requestUrl;

        //set total_records
        $this->total_records = $total;
        $this->total_pages = ceil(($this->total_records / $this->page_size));

        if ($this->total_pages < 2)
            return false;

        $range = ($this->scroll_size - 1) / 2;

        $r_padding = $range - $this->current_page + 1;
        $r_padding = ($r_padding > 0) ? $r_padding : 0;

        $l_padding = $this->total_pages - $this->current_page;
        $l_padding = ($l_padding >= 0 && $l_padding < $range) ? ($range - $l_padding) : 0;

        $left = $this->current_page - $range - $l_padding;
        $left = ($left > 0) ? $left : 1;
        $right = $this->current_page + $range + $r_padding;
        $right = ($right > $this->total_pages) ? $this->total_pages : $right;

        $str = "<div class='pagenation'>";

        if ($this->current_page == 1)
            $str .= "";
        else
            $str .= "<a href='" . $link_string . '?page=' . ($this->current_page - 1) . $query_string . "'>&laquo; Prev</a>";


        for ($i = $left; $i <= $right; $i++) {
            if ($this->current_page == $i)
                $str .= " <a href='javascript:void(0);' class='active'>$i</a>";
            else
                $str .= " <a href='" . $link_string . '?page=' . $i . $query_string . "'>$i</a>";
        }

        if ($this->current_page == $this->total_pages)
            $str .= "";
        else
            $str .= " <a href='" . $link_string . '?page=' . ($this->current_page + 1) . $query_string . "' class='tweets_next'>Next &raquo;</a>";

        $str .= "</div>";

        return $str;
    }

    public function showNavigationCustomize($total,$id) {
        
        //Find page url
        $protocol = (stripos($_SERVER['SERVER_PROTOCOL'], 'https') === false) ? 'http' : 'https';

        $r_url = explode("?", $_SERVER['REQUEST_URI']);
        //  print_r($_SERVER['REQUEST_URI']);

        $requestUrl = $r_url[0];

        if (isset($r_url[1])) {
            $query_string = $r_url[1];    //remove page query string, if it has.
            $t = explode("&", $query_string);

            foreach ($t as $key => $val) {
                $t1 = explode("=", $val);
                if ($t1[0] == 'page') {

                    unset($t[$key]);
                    break;
                }
            }

            $query_string = implode("&", $t);
        }else
            $query_string = "";
        if ($query_string != "")
            $query_string = "&" . $query_string;
        //echo $requestUrl;
        $link_string = $protocol . '://' . $_SERVER['HTTP_HOST'] . '/' . $_SERVER['PHP_SELF']; //$requestUrl;
        //set total_records
        $this->total_records = $total;
        $this->total_pages = ceil(($this->total_records / $this->page_size));

        if ($this->total_pages < 2)
            return false;

        $range = ($this->scroll_size - 1) / 2;

        $r_padding = $range - $this->current_page + 1;
        $r_padding = ($r_padding > 0) ? $r_padding : 0;

        $l_padding = $this->total_pages - $this->current_page;
        $l_padding = ($l_padding >= 0 && $l_padding < $range) ? ($range - $l_padding) : 0;

        $left = $this->current_page - $range - $l_padding;
        $left = ($left > 0) ? $left : 1;
        $right = $this->current_page + $range + $r_padding;
        $right = ($right > $this->total_pages) ? $this->total_pages : $right;

        $str = "<div class=''>";

        if ($this->current_page == 1)
            $str .= "<a href='javascript:void(0);'><img src='" . SITE_URL . "/views/images/arrow_left.gif' height='13' width='5'/></a>";
        else {
            $str .="<a href='javascript:void(0);' onclick=\"nextPrev('" . ($this->current_page - 1) . $query_string . "','".$id."');\"> <img src='" . SITE_URL . "views/images/arrow_left.gif' height='13' width='5'/></a>";
        }
        if ($this->current_page == $this->total_pages)
            $str .= "<a href='javascript:void(0);'><img src='" . SITE_URL . "/views/images/arrow_right.gif' height='13' width='5'/></a>";
        else {

            $str .="<a href='javascript:void(0);' onclick=\"nextPrev('" . ($this->current_page + 1) . $query_string . "','".$id."');\"> <img src='" . SITE_URL . "/views/images/arrow_right.gif' height='13' width='5'/></a>";
        }
        $str .= "</div ";
        return $str;
    }

    public function showNavigationCustomize2($total) {
        //Find page url
        $protocol = (stripos($_SERVER['SERVER_PROTOCOL'], 'https') === false) ? 'http' : 'https';

        $r_url = explode("?", $_SERVER['REQUEST_URI']);
        //  print_r($_SERVER['REQUEST_URI']);

        $requestUrl = $r_url[0];

        if (isset($r_url[1])) {
            $query_string = $r_url[1];    //remove page query string, if it has.
            $t = explode("&", $query_string);

            foreach ($t as $key => $val) {
                $t1 = explode("=", $val);
                if ($t1[0] == 'page') {

                    unset($t[$key]);
                    break;
                }
            }

            $query_string = implode("&", $t);
        }else
            $query_string = "";
        if ($query_string != "")
            $query_string = "&" . $query_string;
        //echo $requestUrl;
        $link_string = $protocol . '://' . $_SERVER['HTTP_HOST'] . '/' . $_SERVER['PHP_SELF']; //$requestUrl;
        //set total_records
        $this->total_records = $total;
        $this->total_pages = ceil(($this->total_records / $this->page_size));

        if ($this->total_pages < 2)
            return false;

        $range = ($this->scroll_size - 1) / 2;

        $r_padding = $range - $this->current_page + 1;
        $r_padding = ($r_padding > 0) ? $r_padding : 0;

        $l_padding = $this->total_pages - $this->current_page;
        $l_padding = ($l_padding >= 0 && $l_padding < $range) ? ($range - $l_padding) : 0;

        $left = $this->current_page - $range - $l_padding;
        $left = ($left > 0) ? $left : 1;
        $right = $this->current_page + $range + $r_padding;
        $right = ($right > $this->total_pages) ? $this->total_pages : $right;

        $left = '';
        $right = '';
        if ($this->current_page == 1)
            $left .= "";
        else {
            $left .=($this->current_page - 1) . $query_string;
        }
        if ($this->current_page == $this->total_pages)
            $right .= "";
        else {
            $right .=($this->current_page + 1) . $query_string;
        }
        $tot = $left . "**" . $right;

        return $tot;
    }

    public function showNavigationBiography($total) {
        $protocol = (stripos($_SERVER['SERVER_PROTOCOL'], 'https') === false) ? 'http' : 'https';
        $r_url = explode("?", $_SERVER['REQUEST_URI']);
        $requestUrl = $r_url[0];

        if (isset($r_url[1])) {
            $query_string = $r_url[1];    //remove page query string, if it has.
            $t = explode("&", $query_string);

            foreach ($t as $key => $val) {
                $t1 = explode("=", $val);
                if ($t1[0] == 'page') {

                    unset($t[$key]);
                    break;
                }
            }
            $query_string = implode("&", $t);
        }else
            $query_string = "";
        if ($query_string != "")
            $query_string = "&" . $query_string;
        //echo $requestUrl;
        $link_string = $protocol . '://' . $_SERVER['HTTP_HOST'] . '/' . $_SERVER['PHP_SELF']; //$requestUrl;
        //set total_records
        $this->total_records = $total;
        $this->total_pages = ceil(($this->total_records / $this->page_size));

        if ($this->total_pages < 2)
            return false;

        $range = ($this->scroll_size - 1) / 2;

        $r_padding = $range - $this->current_page + 1;
        $r_padding = ($r_padding > 0) ? $r_padding : 0;

        $l_padding = $this->total_pages - $this->current_page;
        $l_padding = ($l_padding >= 0 && $l_padding < $range) ? ($range - $l_padding) : 0;

        $left = $this->current_page - $range - $l_padding;
        $left = ($left > 0) ? $left : 1;
        $right = $this->current_page + $range + $r_padding;
        $right = ($right > $this->total_pages) ? $this->total_pages : $right;

        $str = "<div class=''>";

        if ($this->current_page == 1)
            $str .= "<a href='javascript:void(0);'><img src='" . SITE_URL . "/views/images/arrow_left.gif' height='13' width='5'/></a>";
        else {
            $str .="<a href='javascript:void(0);' onclick=\"nextBiography('" . ($this->current_page - 1) . $query_string . "');\"> <img src='" . SITE_URL . "views/images/arrow_left.gif' height='13' width='5'/></a>";
        }
        if ($this->current_page == $this->total_pages)
            $str .= "<a href='javascript:void(0);'><img src='" . SITE_URL . "/views/images/arrow_right.gif' height='13' width='5'/></a>";
        else {

            $str .="<a href='javascript:void(0);' onclick=\"nextBiography('" . ($this->current_page + 1) . $query_string . "');\"> <img src='" . SITE_URL . "/views/images/arrow_right.gif' height='13' width='5'/></a>";
        }
        $str .= "</div>";

        return $str;
    }
    public function showNavigationVideo($total,$id) {
        
        //Find page url
        $protocol = (stripos($_SERVER['SERVER_PROTOCOL'], 'https') === false) ? 'http' : 'https';

        $r_url = explode("?", $_SERVER['REQUEST_URI']);
        //  print_r($_SERVER['REQUEST_URI']);

        $requestUrl = $r_url[0];

        if (isset($r_url[1])) {
            $query_string = $r_url[1];    //remove page query string, if it has.
            $t = explode("&", $query_string);

            foreach ($t as $key => $val) {
                $t1 = explode("=", $val);
                if ($t1[0] == 'page') {

                    unset($t[$key]);
                    break;
                }
            }

            $query_string = implode("&", $t);
        }else
            $query_string = "";
        if ($query_string != "")
            $query_string = "&" . $query_string;
        //echo $requestUrl;
        $link_string = $protocol . '://' . $_SERVER['HTTP_HOST'] . '/' . $_SERVER['PHP_SELF']; //$requestUrl;
        //set total_records
        $this->total_records = $total;
        $this->total_pages = ceil(($this->total_records / $this->page_size));

        if ($this->total_pages < 2)
            return false;

        $range = ($this->scroll_size - 1) / 2;

        $r_padding = $range - $this->current_page + 1;
        $r_padding = ($r_padding > 0) ? $r_padding : 0;

        $l_padding = $this->total_pages - $this->current_page;
        $l_padding = ($l_padding >= 0 && $l_padding < $range) ? ($range - $l_padding) : 0;

        $left = $this->current_page - $range - $l_padding;
        $left = ($left > 0) ? $left : 1;
        $right = $this->current_page + $range + $r_padding;
        $right = ($right > $this->total_pages) ? $this->total_pages : $right;

        $str = "<div class=''>";

        if ($this->current_page == 1)
            $str .= "<a href='javascript:void(0);'><img src='" . SITE_URL . "/views/images/arrow_left.gif' height='13' width='5'/></a>";
        else {
            $str .="<a href='javascript:void(0);' onclick=\"nextVideo('" . ($this->current_page - 1) . $query_string . "','".$id."');\"> <img src='" . SITE_URL . "views/images/arrow_left.gif' height='13' width='5'/></a>";
        }
        if ($this->current_page == $this->total_pages)
            $str .= "<a href='javascript:void(0);'><img src='" . SITE_URL . "/views/images/arrow_right.gif' height='13' width='5'/></a>";
        else {

            $str .="<a href='javascript:void(0);' onclick=\"nextVideo('" . ($this->current_page + 1) . $query_string . "','".$id."');\"> <img src='" . SITE_URL . "/views/images/arrow_right.gif' height='13' width='5'/></a>";
        }
        $str .= "</div ";
        return $str;
    }

}

?>