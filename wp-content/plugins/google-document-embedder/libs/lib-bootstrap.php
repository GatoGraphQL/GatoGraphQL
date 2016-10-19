<?php

// define custom path to wp-load.php (usually not necessary)
$path = '';

// bootstrap for getting ABSPATH constant to wp-load.php outside the admin screen
if ( ! defined( 'WP_LOAD_PATH' ) ) {
	$classic_root = dirname( dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) ) . '/';
	if ( file_exists( $classic_root . 'wp-load.php' ) ) {
		define( 'WP_LOAD_PATH', $classic_root );
	} else {
		if ( file_exists( $path . 'wp-load.php' ) ) {
			define( 'WP_LOAD_PATH', $path );
			
			// standardize current working directory
			@chdir( WP_LOAD_PATH ); 
		} else {
			exit( 'Could not find wp-load.php' );
		}
	}
}

// load wp-load.php
require_once( WP_LOAD_PATH . 'wp-load.php' );

?>
