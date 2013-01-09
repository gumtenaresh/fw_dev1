<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include(realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config.inc.php'));
error_log("<br>Youtube crawl started at:" . date("m/d/Y H:i:s"));
$objectFanwire = new Fanwires();
$objectVedios = new Videos();
$res = $objectFanwire->getAllFanwireIds();
foreach ($res as $val) {
    $fanwireid = $val['id'];
    if ($val['youtube'] != "") {
        $channelname = end(explode('/', $val['youtube']));
        $feedURL1 = 'http://gdata.youtube.com/feeds/api/videos?author=' . $channelname;
        $sxml1 = simplexml_load_file($feedURL1);
        $counts = $sxml1->children('http://a9.com/-/spec/opensearchrss/1.0/');
        $total_records = $counts->totalResults;
        $start_index = $counts->startIndex;
        $max_results = 25;
        $a = 0;
        $p = 1; //$total_records / 25;
        $remaining = $total_records;
        $i = 0;
        for ($k = 0; $k <= $p; $k++) {
            $feedURL = 'http://gdata.youtube.com/feeds/api/users/' . $channelname . '/uploads?orderby=updated&v=2&start-index=' . $start_index . '&max_results=' . $max_results;
            $sxml = simplexml_load_file($feedURL);
            // iterate over entries in category
            // print each entry's details
            foreach ($sxml->entry as $entry) {
                if (empty($entry)) {
                    error_log("error");
                    exit;
                }
                $feedURL = 'http://gdata.youtube.com/feeds/api/videos/' . end(explode(':', $entry->id));
                $items[$i]['youtubeid'] = end(explode(':', $entry->id));
                $entry = simplexml_load_file($feedURL);
                // get nodes in media: namespace for media information
                $media = $entry->children('http://search.yahoo.com/mrss/');
                $attrs = $media->group->thumbnail[0]->attributes();
                //$items[$i]['video'] = substr($media->group->title, 0, 300);
                $items[$i]['video'] = str_replace('&', '--', $media->group->title); //substr($media->group->title, 0, 300);
                //  if (preg_match("/" . $item['websiteCrawlname'] . "/i", $items[$i]['video'])) {

                $items[$i]['description'] = substr($media->group->description, 0, 300);
                $items[$i]['keywords'] = substr($media->group->keywords, 0, 300);
                $items[$i]['released'] = substr($entry->published, 0, 30);
                $items[$i]['embedcode'] = "http://youtu.be/" . end(explode(':', $entry->id));

                $items[$i]['fanwireName'] = $fanwireid;
                $items[$i]['iframe'] = $embedcode = YoutubeUrl::parse_youtube_url($items[$i]['embedcode'], 'embed', '', '', 0, 1);
                $items[$i]['thumbnail'] = trim($attrs['url']);
                $items[$i]['source'] = VDO_TYPE;
                $i++;
            }
            $start_index += $max_results;
        }
        $objectVedios->insertVideosCrawl($items);
    } else {
        error_log($val['facebook'] . "This fanwire doesnot have the Youtube url");
    }
}
?>