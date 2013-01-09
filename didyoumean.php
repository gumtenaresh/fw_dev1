<?php

class GoogleDidYouMean {

    private function curl_fetch($url) {
        $options = array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "uft-8",
            CURLOPT_USERAGENT => "Mozilla/5.0 (Linux; U; Android 2.1-update1; en-gb; HTC Desire Build/ERE27) AppleWebKit/530.17 (KHTML, like Gecko) Version/4.0 Mobile Safari/530.17", // who am i 
            CURLOPT_AUTOREFERER => true,
            CURLOPT_CONNECTTIMEOUT => 120,
            CURLOPT_TIMEOUT => 120,
            CURLOPT_MAXREDIRS => 4,
        );

        $ch = curl_init($url);
        curl_setopt_array($ch, $options);
        $content = curl_exec($ch);
        $err = curl_errno($ch);
        $errmsg = curl_error($ch);
        $header = curl_getinfo($ch);
        curl_close($ch);

        return array($content, $header, $err, $errmsg);
    }

    public function doSpellingSuggestion($phrase, $lang = null) {

        $lang = 'en';
        $url = "http://www.google.com/m?dc=gorganic&source=mobileproducts&q=";

        list($content, $header, $err, $errmsg) = self::curl_fetch($url . $phrase);

        if (!$content || $header['http_code'] != 200) {
            return '';
        }

        //$pattern = '/<span[^>]*>[a-zA-Z\s]+:<\/span> <a[^>]+><b><i>(.+?)<\/i><\/b><\/a>/';
        $pattern = '/<a[^>]+><b><i>(.+?)<\/i><\/b><\/a>/';
        preg_match($pattern, $content, $matches);

        return $matches[1];
    }

}

  $phrase = $_GET['phrase'];
 
$google = new GoogleDidYouMean();
$result = $google->doSpellingSuggestion(urlencode($phrase), $lang);
echo $result;
?>
