<?php

// access wp functions externally
require_once('libs/lib-bootstrap.php');

// no access if parent plugin is disabled
if ( ! function_exists('gde_activate') ) {
	wp_die('<p>'.__('You do not have sufficient permissions to access this page.').'</p>');
}

if ( isset( $_GET['d'] ) ) {
	// non-secured docs (replaces old pdf.php)
	$allowed = true;
	$data['url'] = urldecode( $_GET['d'] );
} else {
	// secured docs
	$allowed = false;
	
	global $wpdb;
	$table = $wpdb->prefix . 'gde_secure';

	if ( isset( $_SERVER['HTTP_USER_AGENT'] ) ) {
		$ua = $_SERVER['HTTP_USER_AGENT'];
	} else {
		$ua = '';
	}

	$allowed_ua = array (
		'via docs.google.com/viewer',
		'WordPress',
		//'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:17.0) Gecko/17.0 Firefox/17.0'
	);
	
	foreach ( $allowed_ua as $str ) {
		if ( strstr( $ua, $str ) !== false ) {
			$allowed = true;
			break;
		} else {
			//gde_dx_log("Disallowed secure doc to UA: $ua"); //test
		}
	}
	
	if ( ! $allowed || ! isset( $_GET['s'] ) ) {
		// file blocked or not defined
		wp_die( __('Direct access to this file is not permitted.', 'google-document-embedder') );
	} else {
		$code = $_GET['s'];
	}
	
	$data = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table WHERE code = %s", $code ), ARRAY_A );
	$data = $data[0];
}

// don't cache this, whatever the response
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");

// attempt to fix filenames with spaces (hatred and loathing)
$url = $data['url'];
if ( strstr( $url, " " ) !== false ) {
	$url = str_replace(" ", "%20", $url);
}

if ( ! empty( $data ) ) {
		$contents = wp_remote_fopen( $url );
	} else {
		// code doesn't exist
		wp_die( __('The requested file was not found.', 'google-document-embedder') );
	}

if ( ! $contents || empty( $contents ) ) {
	// got nothing of merit
	wp_die( __('Unable to open the requested file. ', 'google-document-embedder') );
} else {
	global $gdetypes;
	
	// filename (without query string, in case of non-cached doc)
	$fn = strtok( basename( $data['url'] ), '?' );
	// get ext
	$ext = end( explode( ".", $fn ) );
	
	if ( isset( $ext ) ) {
		$ext = strtolower( $ext );
		
		// get mime type
		if ( array_key_exists( $ext, $gdetypes ) ) {
			$type = $gdetypes[$ext];
		} else {
			wp_die( __('The document file type is not supported.', 'google-document-embedder') );
		}
	} else {
		// file has no extension
		wp_die( __('The document file type is not supported.', 'google-document-embedder') );
	}
	
	// output document
	header('Content-type: '.$type.'; charset=utf-8');
	header('Content-Disposition: attachment; filename="'.$fn.'"');
	
	echo $contents;
}

?>
