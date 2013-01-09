<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include(realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config.inc.php'));
$obj = new Photos();
$object = new Users();
$objectArticle = new Articles();
$objectFanwire = new Fanwires();
$crawl = new WebsiteCrawl();
$websitedet = $obj->getFanSites();
foreach ($websitedet as $val) {
    $data['request_url'] = $val['source'];
    $res = $objectFanwire->getAllFanwireIds();
    foreach ($res as $val1) {
        error_log("<br> website crawl for " . $val1['name'] . " started at: " . date("m/d/Y H:i:s") . " for site -- " . $val['source']);
        $data['fanwireid'] = $val1['id'];
        $data['keyword'] = $val1['name'];
        $item['searchkeyword'] = $data['keyword'];
        $item['source'] = $val['source'];
        $data['source'] = $val['source'];
        $data['classes'] = $obj->getSourceClasselements($val['id']);
        $ret = $crawl->CrawlMainAction($data, "Crawl");
    }
}

class WebsiteCrawl {

    function file_get_html($url, $use_include_path = false, $context = null, $offset = -1, $maxLen = -1, $lowercase = true, $forceTagsClosed = true, $target_charset = DEFAULT_TARGET_CHARSET, $stripRN = true, $defaultBRText = DEFAULT_BR_TEXT) {
        $objectArticle = new Articles();
        // We DO force the tags to be terminated.
        $dom = new simple_html_dom(null, $lowercase, $forceTagsClosed, $target_charset, $defaultBRText);
        // For sourceforge users: uncomment the next line and comment the retreive_url_contents line 2 lines down if it is not already done.
        $contents = $objectArticle->curl_get_file_contents($url);
        // Paperg - use our own mechanism for getting the contents as we want to control the timeout.
        if (empty($contents)) {
            return false;
        }
        // The second parameter can force the selectors to all be lowercase.
        $dom->load($contents, $lowercase, $stripRN);
        return $dom;
    }

    function CrawlMainAction($data, $type) {

        $objectArticle = new Articles();
        $html = $this->file_get_html(str_replace("###", str_replace(" ", $data['classes']['search_seperator'], $data['keyword']), trim($data['classes']['search_url'])));

        if (($data['classes']['websitename'] != "")) {
            // echo $_REQUEST['search_list_repeat_element'] . '.' . $_REQUEST['search_list_repeat_class']; 

            if ($data['classes']['search_list_repeat_element'] != "" && $data['classes']['searchlist_repeat_element_class'] == "") {
                $cls = $data['classes']['search_list_repeat_element'];
            } else {
                $cls = $data['classes']['search_list_repeat_element'] . '.' . $data['classes']['searchlist_repeat_element_class'];
            }

            if (count($html->find($cls)) > 0) {

                foreach ($html->find($cls) as $key => $article) {
                    $item = array();
                    if ($key <= 10) {
                        $item['url'] = trim($article->find($data['classes']['searchlist_title_class'], 0)->href);
                        $item['tit'] = trim($article->find($data['classes']['searchlist_title_class'], 0)->plaintext);
                        $item['author'] = trim($article->find($data['classes']['searchlist_author_class'], 0)->plaintext);
                        $item['desc'] = trim($article->find('p', 0)->plaintext);
                        $item['date'] = trim($article->find($data['classes']['search_list_date_element'], 0)->plaintext);
                        $item['fanwireid'] = $data['fanwireid'];
                        if ($item['date'] == "") {
                            $item['date'] = trim($article->find('.' . $data['classes']['search_list_date_class'], 0)->plaintext);
                        }
                        preg_match('@^(?:http://)?([^/]+)@i', $data['request_url'], $matches);

                        $item['articleFrom'] = str_replace('www.', '', $matches[1]);
                        $item['source'] = $matches[0];
                        if ($data['classes']['searchlist_image_class'] != "") {
                            if ($data['classes']['searchlist_image_class_type'] == 0)
                                $sep_item = '.';
                            else
                                $sep_item = '#';
                            $cls = $data['classes']['searchlist_image_class_element'] . $sep_item . $data['classes']['searchlist_image_class'];
                            $item['image'] = trim($article->find($cls . ' img', 0)->src);
                            if ($data['Wsourceid'] == 6) {
                                $item['image'] = str_replace("width=70&height=53", "width=281&height=211", $item['image']);
                            }
                        }

                        // get last two segments of host name
                        $pos = strpos($item['url'], "ew.com");
                        if ($pos !== false) {
                            
                        } else {
                            preg_match('@^(?:http://)?([^/]+)@i', $data['request_url'], $matches);
                            $pos = strpos($item['url'], $matches[1]);
                            // print_r($matches);
                            if ($pos !== false) {
                                
                            } else {
                                $item['url'] = $data['request_url'] . ltrim($item['url'], '/');
                            }
                        }

                        $html_sub = $this->file_get_html(trim($item['url']));

                        if (!empty($html_sub)) {

                            if ($data['classes']['description_content_class_type'] == 0)
                                $sep_item = '.';
                            else
                                $sep_item = '#';
                            // echo 'div' . $sep_item . $_REQUEST['description_div_class']; exit;
                            if ($data['classes']['description_div_class_element'] != "") {
                                $divelem = $data['classes']['description_div_class_element'];
                            } else {
                                $divelem = 'div';
                            }
                            $findelem = $divelem . $sep_item . $data['classes']['description_div_class']; //exit;
                            $res_sub = $html_sub->find($findelem);
                            //echo"<pre>"; print_r($res_sub); echo'</pre>'; exit;
                            if (empty($res_sub)) {
                                // echo ' <br/> Not Found Item Url not exists<br/>'; //exit;
                            } else {
                                foreach ($html_sub->find($findelem) as $key => $detail_artic) {

                                    $ite = "";
                                    if ($key == 0) {
                                        if ($data['classes']['description_image_class_type'] == 0)
                                            $sep_item = '.';
                                        else
                                            $sep_item = '#';

                                        // echo "<br/>".$item['getreviewImage'] = trim($detail_artic->find('div.' . $sep_item . $_REQUEST['description_image_class'] . ' img', 0)->src);exit;
                                        if ($item['image'] == '') {
                                            $item['getreviewImage'] = trim($detail_artic->find('div.' . $sep_item . $data['classes']['description_image_class'] . ' img', 0)->src);
                                        } else {
                                            $item['getreviewImage'] = $item['image'];
                                        }
                                        if ($item['author'] == '') {
                                            if ($item['author'] == "") {
                                                $item['getAuthor'] = trim($html_sub->find('div.' . $data['classes']['description_author_class'], 0)->plaintext);
                                            }
                                            if ($data['Wsourceid'] == 6) {
                                                $item['getAuthor'] = trim($html_sub->find('.' . $data['classes']['description_author_class'], 0)->plaintext);
                                            }
                                        } else {
                                            $item['getAuthor'] = $item['author'];
                                        }
                                        //echo $item['date'];exit;
                                        if ($item['date'] == "") {
                                            $item['getDate'] = trim($detail_artic->find('.' . $data['classes']['description_date_class'], 0)->plaintext);
                                        } else if ($item['date'] == "") {
                                            $item['getDate'] = trim($detail_artic->find('div.' . $data['classes']['description_date_class'], 0)->plaintext);
                                        } else {
                                            $item['getDate'] = trim($item['date']);
                                        }
                                        $ite = "";
                                        if (count($detail_artic->find('p')) > 0) {
                                            foreach ($detail_artic->find('p') as $pr) {
                                                $ite = $ite . $pr;
                                            }
                                        }
                                        $item['ItemDesc'] .= $ite; //strip_tags($ite, '<p><a><b><strong>');
                                    }
                                }

                                $checkArticleTitle = $objectArticle->checkArticle($item['tit'], 'website');
                                if ($checkArticleTitle == 0) {


                                    $pos = strpos($mystring, $findme);

                                    $pos = strpos($data['keyword'], strtolower($item['ItemDesc']));
                                    //var_dump($pos);
                                    //$img = file_get_contents($item['getreviewImage']);
                                    $filename = strtotime(date("Y-m-d H:i:s")) . rand(2, 900) . "_artImg.jpg"; //$username.'.jpg';

                                    $fs = StorageFactory::getObject();
                                    $img = file_get_contents($item['getreviewImage']);
                                    $fs->saveFileFromContents($img, "photos/" . $filename);
                                    // if ($img) {
                                    //   $file = DOC_ROOT_PATH . '/photos/' . $filename;
                                    //   file_put_contents($file, $img);
                                    $item['getreviewImageL'] = $filename;
                                    // }
                                    if ($item['ItemDesc'] != '') {
                                        //echo $item['tit']. "  inserting ";
                                        $objectArticle->addArticleFromWebsite($item, 'website');
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

}

?>