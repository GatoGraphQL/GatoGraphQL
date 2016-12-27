<?php
/*
Plugin Name: PoP Demo Environment
Version: 1.0
Description: Environment Constants for the PoP Demo Website
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
Author URI: https://getpop.org/u/leo/
*/

define ('POPDEMO_ENVIRONMENT_VERSION', 0.100);

// Priority 5: execute before everything else, to set all the environment constants
// That is needed to set the pop-aws CDN URI
add_action('plugins_loaded', 'popdemo_environment_init', 5);
function popdemo_environment_init() {
	require_once 'plugins/load.php';
}