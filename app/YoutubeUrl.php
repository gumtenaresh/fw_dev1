<?php

class YoutubeUrl {

    public function parse_youtube_url($urls, $return = 'embed', $width = '', $height = '', $rel = 0, $frame = 0) {

        //url is http://youtu.be/xxxx
        if ($urls['host'] == 'youtu.be' || $urls['host'] == 'youtube.com') {
            $id = ltrim($urls['path'], '/');
        }
        //url is http://www.youtube.com/embed/xxxx
        else if (strpos($urls['path'], 'embed') == 1) {
            $id = end(explode('/', $urls['path']));
        }
        //url is xxxx only
        else if (strpos($url, '/') === false) {
            $id = $url;
        }
        //http://www.youtube.com/watch?feature=player_embedded&v=m-t4pcO99gI
        //url is http://www.youtube.com/watch?v=xxxx
        else {
            parse_str($urls['query']);
            $id = $v;
            if (!empty($feature)) {
                $id = end(explode('v=', $urls['query']));
            }
        }
        if ($frame == 1) {
            $id = end(explode('/', $urls));
        }
        //return embed iframe
        if ($return == 'embed') {
            return '<iframe  width="' . ($width ? $width : 560) . '" height="' . ($height ? $height : 349) . '" src="http://www.youtube.com/embed/' . $id . '?rel=' . $rel . '" frameborder="0" ></iframe>';
        }
        //return normal thumb
        else if ($return == 'thumb') {
            return 'http://i1.ytimg.com/vi/' . $id . '/default.jpg';
        }
        //return hqthumb
        else if ($return == 'hqthumb') {
            return 'http://i1.ytimg.com/vi/' . $id . '/hqdefault.jpg';
        }
        // else return id
        else {
            return $id;
        }
    }

    function replace_plain_text_link($plain_text) {
        $url_html = preg_replace('/(?<!S)((http(s?):\/\/)|(www.))+([\w.1-9\&=#?\-~%;\/]+)/', '<a href="http://$4$5" target="_blank">http$3://$4$5</a>', $plain_text);
        return ($url_html);
    }

    function truncate_chars($text, $limit, $ellipsis = '...') {
        if (strlen($text) > $limit)
            $text = trim(substr($text, 0, $limit)) . $ellipsis;
        return $text;
    }

    function strip_tags_content($text, $tags = '', $invert = FALSE) {

        preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($tags), $tags);
        $tags = array_unique($tags[1]);

        if (is_array($tags) AND count($tags) > 0) {
            if ($invert == FALSE) {
                return preg_replace('@<(?!(?:' . implode('|', $tags) . ')\b)(\w+)\b.*?>.*?</\1>@si', '', $text);
            } else {
                return preg_replace('@<(' . implode('|', $tags) . ')\b.*?>.*?</\1>@si', '', $text);
            }
        } elseif ($invert == FALSE) {
            return preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $text);
        }
        return $text;
    }

    public function fixurl($title) {
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

    public function thumbnail_sizefix($target, $mheight, $mwidth) {
        //  echo IMAGE_URL.$target."--".$mheight."==".$mwidth."<br>";
        $img = getimagesize(IMAGE_URL . $target);
        //$img = getimagesize($target);
        //print_r($img);
        $max_width = $mwidth;
        $max_height = $mheight;
        $old_width = $img[0];
        $old_height = $img[1];
        $scale = min($max_width / $old_width, $max_height / $old_height);
        $new_width = ceil($scale * $old_width);
        $new_height = ceil($scale * $old_height);
        return "width=\"$new_width\" height=\"$new_height\"";
    }

    

}
