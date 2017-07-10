<?php
/*
Plugin Name: PoP CDN
Description: Implementation of CDN capabitilies for PoP
Plugin URI: https://getpop.org
Version: 1.0
Author: Leonardo Losoviz
Author URI: https://getpop.org/en/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define ('POP_CDN_VERSION', 0.122);
define ('POP_CDN_DIR', dirname(__FILE__));

class PoP_CDN {

	function __construct() {

		// Priority 6: just after PoP Engine, and before everything else (except the "website-environment" plug-ins), 
		// so we can set the POP_CDN_ASSETS_URI constant in plugin_url before all other plug-ins need it
		add_action('plugins_loaded', array($this,'init'), 6);
	}
	function init(){

		// define ('POP_CDN_URI', plugins_url('', __FILE__));

		if ($this->validate()) {
			
			$this->initialize();
			define('POP_CDN_INITIALIZED', true);
		}
	}
	function validate(){
		
		require_once 'validation.php';
		$validation = new PoP_CDN_Validation();
		return $validation->validate();	
	}
	function initialize(){

		require_once 'initialization.php';
		global $PoP_CDN_Initialization;
		return $PoP_CDN_Initialization->initialize();	
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_CDN();
