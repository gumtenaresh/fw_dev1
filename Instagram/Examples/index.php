<?php

define( 'GITHUB_URL',           'https://github.com/galen/PHP-Instagram-API/blob/master/Examples/' );
define( 'EXAMPLES_DIR',         __DIR__ );
define( 'REDIRECT_AFTER_AUTH',  '' );

// Turn on error reporting
error_reporting( E_ALL );
ini_set( 'display_errors', 'On' );

// Start the session
session_start();

// Authorization configuration
$auth_config = array(
    'client_id' => '6e8d4f01d42c457881847c396a19f489',
    'client_secret' => '6d5d43afb6b445d4b50f967d479e5e08',
    'redirect_uri' => 'http://localhost/fanwire/insta/',
    'scope' => array('likes', 'comments', 'relationships')
);
//echo "<pre>"; print_r($_SESSION); exit;
// Start authorization if an access token session isnt present
if ( !isset( $_SESSION['instagram_access_token'] ) ) {
  //  echo EXAMPLES_DIR . '/_auth.php'; exit;
    require( EXAMPLES_DIR . '/_auth.php' );
    exit;
}
echo $_GET['example']; exit;
// If an example has been chosen, include it and exit
if ( isset( $_GET['example'] ) ) {
    try {
        date_default_timezone_set('America/Los_Angeles');
        require( EXAMPLES_DIR . '/_SplClassLoader.php' );
        $loader = new SplClassLoader( 'Instagram', dirname( EXAMPLES_DIR ) );
        $loader->register();
        require( EXAMPLES_DIR . '/' . $_GET['example'] );
        exit;
    }
    /**
     * Authorization Exception thrown
     * Clear the session and redirect to the auth page
     */
    catch ( \Core\ApiAuthException $e ) {
        unset( $_SESSION );
        session_destroy();
        header( 'Location: ' . $auth_config['redirect_uri'] );
        exit;
    }
    catch ( \Core\ApiException $e ) {
        $error = ucwords( $e->getMessage() );
        require( EXAMPLES_DIR . '/views/_header.php' );
        require( EXAMPLES_DIR . '/views/_error.php' );
        require( EXAMPLES_DIR . '/views/_footer.php' );
        exit;
    }
}

// Get all the examples for display
$files = glob( EXAMPLES_DIR . '/*.php' );

// Unset index file
unset( $files[array_search( EXAMPLES_DIR . '/index.php', $files )] );
unset( $files[array_search( EXAMPLES_DIR . '/test.php', $files )] );

require( EXAMPLES_DIR . '/views/_header.php' );
require( EXAMPLES_DIR . '/views/index.php' );
require( EXAMPLES_DIR . '/views/_footer.php' );
