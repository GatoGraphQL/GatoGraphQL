<?php
/*
Plugin Name: PoP Frontend Engine
Version: 1.0
Description: Front-end module for the Platform of Platforms
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
Author URI: https://getpop.org/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define ('POP_FRONTENDENGINE_VERSION', 0.159);
define ('POP_FRONTENDENGINE_VENDORRESOURCESVERSION', 0.100);
define ('POP_FRONTENDENGINE_DIR', dirname(__FILE__));
define ('POP_FRONTENDENGINE_PHPTEMPLATES_DIR', POP_FRONTENDENGINE_DIR.'/php-templates/compiled');
define ('POP_RESOURCELOADER_ASSETS_DIR', POP_FRONTENDENGINE_DIR.'/kernel/resourceloaders/config/assets');

class PoPFrontend {

	function __construct() {
		
		// Priority: after PoP WP Processors loaded
		add_action('plugins_loaded', array($this,'init'), 15);
		add_action('PoP:system-build', array($this,'system_build'));
		add_action('PoP:system-generate:theme', array($this,'system_generate_theme'));
		add_action('PoP:version', array($this,'version'), 15);
	}
	function version($version){

		return POP_FRONTENDENGINE_VERSION;
	}
	function init(){

		// Allow other plug-ins to modify the plugins_url path (eg: pop-aws adding the CDN)
		define ('POP_FRONTENDENGINE_URL', plugins_url('', __FILE__));
		define ('POP_RESOURCES_DIR', WP_CONTENT_DIR.'/pop-resources');
		define ('POP_RESOURCES_URL', WP_CONTENT_URL.'/pop-resources');

		define ('POP_RESOURCELOADER_CONTENT_DIR', POP_CONTENT_DIR.'/resourceloader');
		define ('POP_RESOURCELOADER_CONTENT_URL', POP_CONTENT_URL.'/resourceloader');
		define ('POP_FRONTENDENGINE_BUILD_DIR', POP_BUILD_DIR.'/pop-frontendengine');
		define ('POP_FRONTENDENGINE_GENERATECACHE_DIR', POP_GENERATECACHE_DIR.'/pop-frontendengine');

		if ($this->validate()) {
			
			$this->initialize();
			define('POP_FRONTENDENGINE_INITIALIZED', true);
		}
	}
	function validate(){
		
		require_once 'validation.php';
		$validation = new PoPFrontend_Validation();
		return $validation->validate();	
	}
	function system_build(){

		require_once 'installation.php';
		$installation = new PoPFrontend_Installation();
		return $installation->system_build();	
	}	
	function system_generate_theme(){

		require_once 'installation.php';
		$installation = new PoPFrontend_Installation();
		return $installation->system_generate_theme();	
	}	
	function initialize(){

		require_once 'initialization.php';
		global $PoPFrontend_Initialization;
		return $PoPFrontend_Initialization->initialize();	
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPFrontend();
