<?php
/*
Plugin Name: PoP Bootstrap Processors
Version: 1.0
Description: Plug-in providing a collection of processors for Boostrap for the Platform of Platforms
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
Author URI: https://getpop.org/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define ('POP_BOOTSTRAPPROCESSORS_VERSION', 0.215);
define ('POP_BOOTSTRAPPROCESSORS_VENDORRESOURCESVERSION', 0.200);
define ('POP_BOOTSTRAPPROCESSORS_DIR', dirname(__FILE__));
define ('POP_BOOTSTRAPPROCESSORS_PHPTEMPLATES_DIR', POP_BOOTSTRAPPROCESSORS_DIR.'/php-templates/compiled');
// define ('POP_BOOTSTRAPPROCESSORS_CACHE_DIR', WP_CONTENT_DIR.'/cache');
// define ('POP_BOOTSTRAPPROCESSORS_CACHE_POPDIR', POP_BOOTSTRAPPROCESSORS_CACHE_DIR.'/pop');

define ('POP_BOOTSTRAPPROCESSORS_LIB', POP_BOOTSTRAPPROCESSORS_DIR.'/library' );
define ('POP_BOOTSTRAPPROCESSORS_PLUGINS_DIR', POP_BOOTSTRAPPROCESSORS_LIB.'/plugins');

// Needed for the Captcha, to make sure the plugins_url points to the website and not to a CDN (as will be hooked in by pop-aws)
define ('POP_BOOTSTRAPPROCESSORS_PHPURI', plugins_url('', __FILE__));
define ('POP_BOOTSTRAPPROCESSORS_PHPURI_LIB', POP_BOOTSTRAPPROCESSORS_PHPURI.'/library' );

class PoP_BootstrapProcessors {

	function __construct() {
		
		// Priority: after PoP WP Processors loaded
		add_action('plugins_loaded', array($this,'init'), 30);
		add_action('PoP:version', array($this,'version'), 30);
	}
	function version($version){

		return POP_BOOTSTRAPPROCESSORS_VERSION;
	}
	function init(){

		define ('POP_BOOTSTRAPPROCESSORS_URL', plugins_url('', __FILE__));
		define ('POP_BOOTSTRAPPROCESSORS_URL_LIB', POP_BOOTSTRAPPROCESSORS_URL.'/library' );

		if ($this->validate()) {
			
			$this->initialize();
			define('POP_BOOTSTRAPPROCESSORS_INITIALIZED', true);
		}
	}
	function validate(){
		
		require_once 'validation.php';
		$validation = new PoP_BootstrapProcessors_Validation();
		return $validation->validate();	
	}
	function initialize(){

		require_once 'initialization.php';
		global $PoP_BootstrapProcessors_Initialization;
		return $PoP_BootstrapProcessors_Initialization->initialize();	
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_BootstrapProcessors();
