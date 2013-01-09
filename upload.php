<?php

include('config.inc.php');
$fn = (isset($_SERVER['HTTP_X_FILENAME']) ? $_SERVER['HTTP_X_FILENAME'] : false);
if ($fn) {
    // AJAX call
    file_put_contents(
            'tmp/' . $_SESSION['id'] . '_' . $fn, file_get_contents('php://input')
    );
    // echo "$fn uploaded";
    //  exit();
} else {
    // form submit
    $files = $_FILES['filesUpload'];
    foreach ($files['error'] as $id => $err) {
        if ($err == UPLOAD_ERR_OK) {
            $fn = $files['name'][$id];
            move_uploaded_file(
                    $files['tmp_name'][$id], 'tmp/' . $_SESSION['id'] . '_' . $fn
            );
            //  echo "<p>File $fn uploaded.</p>";
        }
    }
}
?>
