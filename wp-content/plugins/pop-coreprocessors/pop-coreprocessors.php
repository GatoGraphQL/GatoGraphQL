<?php
/*
Plugin Name: PoP Core Processors
Version: 1.0
Description: Plug-in providing a collection of processors for the Platform of Platforms
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
Author URI: https://getpop.org/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define ('POP_COREPROCESSORS_VERSION', 0.229);
define ('POP_COREPROCESSORS_VENDORRESOURCESVERSION', 0.200);
define ('POP_COREPROCESSORS_DIR', dirname(__FILE__));
define ('POP_COREPROCESSORS_PHPTEMPLATES_DIR', POP_COREPROCESSORS_DIR.'/php-templates/compiled');
// define ('POP_COREPROCESSORS_CACHE_DIR', WP_CONTENT_DIR.'/cache');
// define ('POP_COREPROCESSORS_CACHE_POPDIR', POP_COREPROCESSORS_CACHE_DIR.'/pop');

define ('POP_COREPROCESSORS_LIB', POP_COREPROCESSORS_DIR.'/library' );
define ('POP_COREPROCESSORS_PLUGINS_DIR', POP_COREPROCESSORS_LIB.'/plugins');

// Needed for the Captcha, to make sure the plugins_url points to the website and not to a CDN (as will be hooked in by pop-aws)
define ('POP_COREPROCESSORS_PHPURI', plugins_url('', __FILE__));
define ('POP_COREPROCESSORS_PHPURI_LIB', POP_COREPROCESSORS_PHPURI.'/library' );

class PoP_CoreProcessors {

	function __construct() {
		
		// Priority: after PoP WP Processors and PoP Bootstrap Processors loaded
		add_action('plugins_loaded', array($this,'init'), 32);
		add_action('PoP:system-generate', array($this,'system_generate'));
	}
	function init() {

		define ('POP_COREPROCESSORS_URL', plugins_url('', __FILE__));
		define ('POP_COREPROCESSORS_URL_LIB', POP_COREPROCESSORS_URL.'/library' );

		define ('POP_USERSTATE_ASSETDESTINATION_DIR', POP_CONTENT_DIR.'/user-state');
		define ('POP_USERSTATE_ASSETDESTINATION_URL', POP_CONTENT_URL.'/user-state');

		if ($this->validate()) {
			
			$this->initialize();
			define('POP_COREPROCESSORS_INITIALIZED', true);
		}
	}
	function system_generate(){

		require_once 'installation.php';
		$installation = new PoP_CoreProcessors_Installation();
		return $installation->system_generate();	
	}	
	function validate(){
		
		require_once 'validation.php';
		$validation = new PoP_CoreProcessors_Validation();
		return $validation->validate();	
	}
	function initialize(){

		require_once 'initialization.php';
		global $PoP_CoreProcessors_Initialization;
		return $PoP_CoreProcessors_Initialization->initialize();	
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_CoreProcessors();
