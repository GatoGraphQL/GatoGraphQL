<?php
/*
Plugin Name: PoP Base Processors
Version: 1.0
Description: Plug-in providing the base processors for the Platform of Platforms
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
Author URI: https://getpop.org/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define ('POP_BASEPROCESSORS_VERSION', 0.208);
define ('POP_BASEPROCESSORS_DIR', dirname(__FILE__));
define ('POP_BASEPROCESSORS_PHPTEMPLATES_DIR', POP_BASEPROCESSORS_DIR.'/php-templates/compiled');
// define ('POP_BASEPROCESSORS_CACHE_DIR', WP_CONTENT_DIR.'/cache');
// define ('POP_BASEPROCESSORS_CACHE_POPDIR', POP_BASEPROCESSORS_CACHE_DIR.'/pop');

define ('POP_BASEPROCESSORS_LIB', POP_BASEPROCESSORS_DIR.'/library' );
define ('POP_BASEPROCESSORS_PLUGINS_DIR', POP_BASEPROCESSORS_LIB.'/plugins');

// Needed for the Captcha, to make sure the plugins_url points to the website and not to a CDN (as will be hooked in by pop-aws)
define ('POP_BASEPROCESSORS_PHPURI', plugins_url('', __FILE__));
define ('POP_BASEPROCESSORS_PHPURI_LIB', POP_BASEPROCESSORS_PHPURI.'/library' );

class PoP_BaseProcessors {

	function __construct() {
		
		// Priority: after PoP Frontend Engine Processors
		add_action('plugins_loaded', array($this,'init'), 28);
		add_action('PoP:version', array($this,'version'), 28);
	}
	function version($version){

		return POP_BASEPROCESSORS_VERSION;
	}
	function init(){

		define ('POP_BASEPROCESSORS_URI', plugins_url('', __FILE__));
		define ('POP_BASEPROCESSORS_URI_LIB', POP_BASEPROCESSORS_URI.'/library' );

		if ($this->validate()) {
			
			$this->initialize();
			define('POP_BASEPROCESSORS_INITIALIZED', true);
		}
	}
	function validate(){
		
		require_once 'validation.php';
		$validation = new PoP_BaseProcessors_Validation();
		return $validation->validate();	
	}
	function initialize(){

		require_once 'initialization.php';
		global $PoP_BaseProcessors_Initialization;
		return $PoP_BaseProcessors_Initialization->initialize();	
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_BaseProcessors();
