<?php

$text = trim(nl2br($_POST['text']));
if (isset($text) && strlen($text) > 0) {
    echo $text;
} else {
    echo "No text was entered";
}
?>