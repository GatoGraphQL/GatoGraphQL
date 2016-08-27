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
define ('POP_ENGINE_VERSION', 0.102);
define ('POP_ENGINE_DIR', dirname(__FILE__));

define ('POP_ENGINE_LIB', POP_ENGINE_DIR.'/library' );
define ('POP_ENGINE_KERNEL', POP_ENGINE_DIR.'/kernel' );
define ('POP_ENGINE_KERNEL_TEMPLATES', POP_ENGINE_KERNEL.'/templates');

class PoPEngine {

	function __construct(){
		
		// Allow the Theme to override definitions. Eg: POP_METAKEY_PREFIX
		add_action('plugins_loaded', array($this, 'init'), 10);
	}
	function init(){
		
		add_action('admin_init', array($this, 'install'));
		$this->initialize();
		define('POP_ENGINE_INITIALIZED', true);
	}
	function install(){

		require_once 'installation.php';
		$installation = new PoPEngine_Installation();
		return $installation->install();	
	}	
	function initialize(){

		require_once 'initialization.php';
		$initialization = new PoPEngine_Initialization();
		return $initialization->initialize();	
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPEngine();
