<?php
/*
Plugin Name: PoP WP API
Version: 1.0
Description: The Platform of Platforms is a niche Social Media website. It can operate as a Platform, it can aggregate other Platforms, or it can be a combination.
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
Author URI: https://getpop.org/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define ('POP_WPAPI_VERSION', 0.102);
define ('POP_WPAPI_DIR', dirname(__FILE__));
define ('POP_WPAPI_LIB', POP_WPAPI_DIR.'/library');

class PoP_WPAPI {

	function __construct(){
		
		add_action('plugins_loaded', array($this, 'init'), 20);
		add_action('PoP:version', array($this,'version'), 20);
	}
	function version($version){

		return POP_WPAPI_VERSION;
	}
	function init(){
		
		$this->initialize();
		define('POP_WPAPI_INITIALIZED', true);
	}
	function initialize(){

		require_once 'initialization.php';
		$initialization = new PoP_WPAPI_Initialization();
		return $initialization->initialize();	
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_WPAPI();
