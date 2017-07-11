<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Util functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

function maybe_add_domain($url) {

	$home_url = trailingslashit(home_url());
	if (startsWith($url, $home_url)) {

		return $url;
	}

	if (startsWith($url, '/')) {

		return $home_url . substr($url, 1);
	}

	return $home_url . $url;
}

function limit_string($string, $length = null, $more = null, $bywords = false) {

	if (!$length)
		$length = apply_filters('excerpt_length', 250);
		
	// Similar to wp_trim_excerpt in wp-includes/formatting.php
	if (!$more) {
		$more = apply_filters( 'excerpt_more', ' ' . '[&hellip;]' );
	}
	if (!$bywords) {
		// Comment Leo 11/07/2017: for some weird reason, when the $string contains character "’", it fails! So I gotta make sure it is not there...
		// $string = (strlen($string) > $length) ? substr($string, 0, $length) . $more : $string;
		// $string = (strlen($string) > $length) ? substr(str_replace('’',"'", $string), 0, $length) . $more : $string;
		// Comment Leo 11/07/2017: it works fine using mb_substr instead, so use that one
		$string = (strlen($string) > $length) ? mb_substr($string, 0, $length) . $more : $string;
	}
	else {
		$string = wp_trim_words( $string, $length, $more );
	}
	
	return $string;
}


function maybe_add_http($url) {
    if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
        $url = "http://" . $url;
    }
    return $url;
}


function full_url() {
    $s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
    $sp = strtolower($_SERVER["SERVER_PROTOCOL"]);
    $protocol = substr($sp, 0, strpos($sp, "/")) . $s;
    $port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
    return $protocol . "://" . $_SERVER['SERVER_NAME'] . $port . $_SERVER['REQUEST_URI'];
}

function array_flatten(array $array) {
    $return = array();
    array_walk_recursive($array, function($a) use (&$return) { $return[] = $a; });
    return $return;
}

function array_id_key_map(array $array) {
    $return = array();
    array_walk($array, function($a) use (&$return) { $return[$a->ID] = $a; });
    return $return;
}

function get_rand() {

	return substr(md5(rand().rand()),0,5);
}

// Taken from http://stackoverflow.com/questions/4356289/php-random-string-generator
function generateRandomString($length = 6, $addtime = true) {
    $characters = 'abcdefghijklmnopqrstuvwxyz';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }

    if ($addtime) {
    	$randomString .= time();
    }
    return $randomString;
}

function gd_get_categories($post_id = null) {
	
	$categories = array();
	if (get_post_type($post_id) == 'post') {
		
		if ($cats = get_the_category($post_id)) {

			foreach ($cats as $cat) {
				$categories[] = $cat->term_id;
			}
		}
	}

	return apply_filters('gd_get_categories', $categories, $post_id);
}

function doing_post() {

	return ('POST' == $_SERVER['REQUEST_METHOD']);
}

// Returns true if this is an Ajax call
function doing_ajax() {

	$doing_ajax = defined('DOING_AJAX') && DOING_AJAX;
	return apply_filters('gd_doing_ajax', $doing_ajax);
}

function multiexplode ($delimiters,$string) {
    
    $ready = str_replace($delimiters, $delimiters[0], $string);
    $launch = explode($delimiters[0], $ready);
    return  $launch;
}

// Taken from https://secure.php.net/manual/en/function.copy.php
function recurse_copy($src,$dst) { 
	if (file_exists($src) && is_dir($src)) {
	    $dir = opendir($src); 
	    @mkdir($dst); 
	    while(false !== ( $file = readdir($dir)) ) { 
	        if (( $file != '.' ) && ( $file != '..' )) { 
	            if ( is_dir($src . '/' . $file) ) { 
	                recurse_copy($src . '/' . $file,$dst . '/' . $file); 
	            } 
	            else { 
	                copy($src . '/' . $file,$dst . '/' . $file); 
	            } 
	        } 
	    } 
	    closedir($dir); 
	}
} 


function delTree($dir) { 

	// Delete the "/" from the dir if it is included
	if (substr($dir, -1) == '/') {

		$dir = substr($dir, 0, strlen($dir) - 1);
	}
	
	if (file_exists($dir) && is_dir($dir)) {
		
		$files = array_diff(scandir($dir), array('.','..')); 
		foreach ($files as $file) { 
			(is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file"); 
		} 
		return rmdir($dir); 
	}

	return false;
}

// Function to get the client IP address
// Taken from https://stackoverflow.com/questions/15699101/get-the-client-ip-address-using-php
function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}