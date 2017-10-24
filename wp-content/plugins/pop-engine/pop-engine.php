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
define ('POP_ENGINE_VERSION', 0.105);
define ('POP_ENGINE_DIR', dirname(__FILE__));

define ('POP_ENGINE_LIB', POP_ENGINE_DIR.'/library' );
define ('POP_ENGINE_KERNEL', POP_ENGINE_DIR.'/kernel' );
define ('POP_ENGINE_KERNEL_TEMPLATES', POP_ENGINE_KERNEL.'/templates');

class PoPEngine {

	function __construct() {
		
		// Allow the Theme to override definitions. Eg: POP_METAKEY_PREFIX
		// Load before anything else
		add_action('plugins_loaded', array($this, 'init'), 0);

		// Allow plug-ins to override values
		add_action('plugins_loaded', array($this, 'define_constants'), 10);
	}
	function init() {
		
		// Comment Leo 17/07/2017: instead of executing 'install' whenever in the back-end and the pop_version changes,
		// we create a build page to be executed, statically, even in DEV
		// add_action('admin_init', array($this, 'system_build'), 10, 0);
		
		$this->initialize();
		define('POP_ENGINE_INITIALIZED', true);
	}
	function define_constants() {
		
		define('POP_CONTENT_DIR', WP_CONTENT_DIR.'/pop-content');
		define('POP_CONTENT_URL', WP_CONTENT_URL.'/pop-content');
		
		define('POP_BUILD_DIR', WP_CONTENT_DIR.'/pop-build');
		define('POP_GENERATECACHE_DIR', WP_CONTENT_DIR.'/pop-generatecache');
		// define('POP_BUILD_URL', WP_CONTENT_URL.'/pop-build');
	}
	function system_build() {

		require_once 'installation.php';
		$installation = new PoPEngine_Installation();
		return $installation->system_build();
	}
	function system_generate() {

		require_once 'installation.php';
		$installation = new PoPEngine_Installation();
		return $installation->system_generate();
	}
	function system_generate_theme() {

		require_once 'installation.php';
		$installation = new PoPEngine_Installation();
		return $installation->system_generate_theme();
	}
	function system_activateplugins() {

		require_once 'installation.php';
		$installation = new PoPEngine_Installation();
		return $installation->system_activateplugins();
	}
	function system_install() {

		require_once 'installation.php';
		$installation = new PoPEngine_Installation();
		return $installation->system_install();
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
// Make it global so that the installation can be called from outside (eg: PoP System)
global $pop_engine;
$pop_engine = new PoPEngine();
