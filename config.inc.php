<?php

error_reporting(E_ERROR);

define('DOC_ROOT_PATH', dirname(__FILE__));

//config-setting to use rewrite module or index.php/path
define('REWRITE_MODULE_FLAG', 1);

//get the request url and pass it to router.
$path = REWRITE_MODULE_FLAG ? '/' . @$_GET['path'] : $_SERVER['PATH_INFO'];


// Ensure app/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
            realpath(DOC_ROOT_PATH . '/app'),
            realpath(DOC_ROOT_PATH . '/models'),
            get_include_path(),
        )));

/**
 *  Enable __autoload
 */
function __autoload($className) {
    //error_log("Class Loaded: " . $className);
    include_once($className . '.php');
}

//*
if (get_magic_quotes_gpc()) {
    $_GET = stripslashesDeep($_GET);
    $_POST = stripslashesDeep($_POST);
    $_COOKIE = stripslashesDeep($_COOKIE);
}

function stripslashesDeep($value) {
    $value = is_array($value) ? array_map('stripslashesDeep', $value) : stripslashes($value);
    return $value;
}

/**
 *  Log paths
 */
define('ERROR_LOG_PATH', DOC_ROOT_PATH . '/logs/error_log');
define('QUERY_LOG_PATH', DOC_ROOT_PATH . '/logs/error_log');

//Log errors to log file
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', ERROR_LOG_PATH);
// https site url
define('SITE_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/fanwire/');
//define('SITE_URL', 'http://localhost/fanwire/');
define('ADMIN_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/fanwire/admin/');
define('ADMIN', 'admin/');

//define('IMAGE_URL', 'https://s3.amazonaws.com/FanWire/photos/');
//define('IMAGE_URL', SITE_URL . 'photos/');
define('IMAGE_URL','http://202.65.136.24/fanwire/photos/');
define('VIDEO_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/fanwire/videos/');
// Error xml file path (This file contains user defined error messages)
Define("Error_XML", DOC_ROOT_PATH . "/utils/Errors.xml");
//profile images
Define("PROFILE_IMAGES_SAVE_PATH", DOC_ROOT_PATH . "/views/images/profile_images/");
Define("PROFILE_IMAGES_VIEW_PATH", SITE_URL . "/profile_images/");

/**
 *  Database details    
 */
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'purpletalk');
define('DB_DATABASE', 'fanwire');
define('DB_DRIVER', 'mysql');

define('DB_SERVER1', '202.65.136.24');
define('DB_USERNAME1', 'root');
define('DB_PASSWORD1', 'padnet');
define('DB_DATABASE1', 'fanwire');
define('DB_DRIVER1', 'mysql');


/*
  define('DB_SERVER1', '74.121.238.160');
  define('DB_USERNAME1', 'dev1admin');
  define('DB_PASSWORD1', 'padnet');
  define('DB_DATABASE1', 'fanwire_dev1');
  define('DB_DRIVER1', 'mysql');
 */

// starting session
session_start();
// user type constant values

define('ADMIN_TYPE', '2');
define('USER_TYPE', '1');

/**
 *  Site properties
 */
define('SITE_NAME', 'fanwire');
define('SUPPORT_EMAIL', 'Team FanWire <noreply@fanwire.com>');
define('SUPPORT_INFO_EMAIL', 'Information <info@fanwire.com>');
define('CC_EMAIL', '');


/**
 *  Set smtp details
 */
define('MAIL_HOST', 'smtp.sendgrid.net');
define('SMTP_PORT', 465);
define('MAIL_AUTHENTICATION_EMAIL', 'fanwire');
define('MAIL_AUTHENTICATION_PASSWORD', 'gobills3');
define('USE_SECURE_CONNECTION', true);
/**
 *  Define smarty conf file
 */
define('SMARTY_CONF_FILE', DOC_ROOT_PATH . '/data/smarty/configs/site.conf');

/**
 *  end defining smarty
 */
/* Facebook Credentials */

define("FB_APP_ID", "327963737297629");
define("FB_APP_SECRET", "f8a694c90e7df3b55c6e6a4e1734e3a8");
/* * Twitter Credentials */
define('YOUR_CONSUMER_KEY', 'ZnkUoW0QsHUUyR1HCR3OXg');
define('YOUR_CONSUMER_SECRET', 'Ijzgq6iTGDGNxKZhCRHwrhZSaUOEmuNcOwcHvB2JQ');

/*
 * These Details for getting gmail contacts
 */
define('GOOGLE_CONSUMER_KEY', 'test.mobiledevelopersdirectory.com');
define('GOOGLE_CONSUMER_SECRET', 'COdnKAPm394GXadw6OEKqvJE');
define('GOOGLE_CALL_BACK_URL', 'http://test.mobiledevelopersdirectory.com/fanwire/testContact2');
define('GOOGLE_EMAIL_COUNT', '500');
/*
 * End gmail contact information
 */

$openinviter_settings = array(
    'username' => "nareshgumte", 'private_key' => "0623beac53850d48fa661505cf536cbc", 'cookie_path' => "/tmp", 'message_body' => "You are invited to http://www.test.com", 'message_subject' => " is inviting you to http://www.test.com", 'transport' => "curl", 'local_debug' => "on_error", 'remote_debug' => "", 'hosted' => "", 'proxies' => array(),
    'stats' => "", 'plugins_cache_time' => "1800", 'plugins_cache_file' => "oi_plugins.php", 'update_files' => "1", 'stats_user' => "", 'stats_password' => "");

/* * ***
 * Definitions for WebSite Crawling
 */

define('HDOM_TYPE_ELEMENT', 1);
define('HDOM_TYPE_COMMENT', 2);
define('HDOM_TYPE_TEXT', 3);
define('HDOM_TYPE_ENDTAG', 4);
define('HDOM_TYPE_ROOT', 5);
define('HDOM_TYPE_UNKNOWN', 6);
define('HDOM_QUOTE_DOUBLE', 0);
define('HDOM_QUOTE_SINGLE', 1);
define('HDOM_QUOTE_NO', 3);
define('HDOM_INFO_BEGIN', 0);
define('HDOM_INFO_END', 1);
define('HDOM_INFO_QUOTE', 2);
define('HDOM_INFO_SPACE', 3);
define('HDOM_INFO_TEXT', 4);
define('HDOM_INFO_INNER', 5);
define('HDOM_INFO_OUTER', 6);
define('HDOM_INFO_ENDSPACE', 7);
define('DEFAULT_TARGET_CHARSET', 'UTF-8');
define('DEFAULT_BR_TEXT', "\r\n");

define('ART_TYPE', 2);
define('CMT_TYPE', 4);
define('PHOTO_TYPE', 1);
define('VDO_TYPE', 3);
define('TWT_TYPE', 2);
define('FACE_TYPE', 1);
define('OTHER_TYPE', 3);

define('STRLIMIT', 0);
define('ENDLIMIT', 3);
define('ACTIVATE', 0); //1 for activation rollovers
/* * ***
 * End of Website Crawling Definitions
 */
/*
 * Instagram credentials
 */
define('INST_CLIENT_ID', 'd10d061e392b4e1fb869a8c73c730abe');
define('INST_CLIENT_SECRET', '3fbb3edca6644b2495a7739daea9b845');
define('INST_REDIRECT_URI', 'http://localhost/fanwire/admin/crawl/');
define('INSTAGRAM_DIR', DOC_ROOT_PATH . '/Instagram');

/*
 * end instagram credentials
 */
/*
 *  amazon bukket credentials
 */
define('AMAZON_KEY', 'AKIAIIM74YQTYHO7UY6A');
define('AMAZON_SECRET_KEY', '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');
define('AMAZON_BUCKET', 'FanWire');
