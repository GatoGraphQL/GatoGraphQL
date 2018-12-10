<?php
/*
Plugin Name: PoP Engine
Version: 1.0
Description: The Platform of Platforms is a niche Social Media website. It can operate as a Platform, it can aggregate other Platforms, or it can be a combination.
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
Author URI: https://getpop.org/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define ('POP_ENGINE_VERSION', 0.108);
define ('POP_ENGINE_DIR', dirname(__FILE__));

define ('POP_ENGINE_LIB', POP_ENGINE_DIR.'/library' );
define ('POP_ENGINE_KERNEL', POP_ENGINE_DIR.'/kernel' );
define ('POP_ENGINE_TEMPLATES', POP_ENGINE_DIR.'/templates');

class PoPEngine {

	function __construct() {
		
		// Allow the Theme to override definitions. Eg: POP_METAKEY_PREFIX
		// Priority: new section, after PoP CMS section
		add_action('plugins_loaded', array($this, 'init'), 100);
		add_action('plugins_loaded', array($this, 'define_startup_constants'), PHP_INT_MAX);
		add_action('PoP:version', array($this, 'version'), 100);
	}
	function version($version){

		return POP_ENGINE_VERSION;
	}
	function init() {

		if ($this->validate()) {

			$this->initialize();
			define('POP_ENGINE_INITIALIZED', true);

			// Allow plug-ins to override values
			add_action('plugins_loaded', array($this, 'define_constants'), 110);
		}
	}
	function define_startup_constants() {
		
		define('POP_STARTUP_INITIALIZED', true);
	}
	function define_constants() {
		
		define('POP_CONTENT_DIR', WP_CONTENT_DIR.'/pop-content');
		define('POP_CONTENT_URL', WP_CONTENT_URL.'/pop-content');
		
		define('POP_BUILD_DIR', WP_CONTENT_DIR.'/pop-build');
		define('POP_GENERATECACHE_DIR', WP_CONTENT_DIR.'/pop-generatecache');
	}
	function validate(){
		
		require_once 'validation.php';
		$validation = new PoPEngine_Validation();
		return $validation->validate();	
	}
	function initialize() {

		require_once 'initialization.php';
		$initialization = new PoPEngine_Initialization();
		return $initialization->initialize();	
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
if (!defined('POP_SERVER_DISABLEPOP') || !POP_SERVER_DISABLEPOP) {
	new PoPEngine();
}