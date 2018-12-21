<?php
/*
Plugin Name: PoP CMS
Version: 1.0
Description: Abstraction plugin to represent the Content Management Syster over which PoP is installed
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
Author URI: https://getpop.org/u/leo/
*/
namespace PoP\CMS;

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define ('POP_CMS_VERSION', 0.107);
define ('POP_CMS_DIR', dirname(__FILE__));

define ('POP_CMS_KERNEL', POP_CMS_DIR.'/kernel' );

class Plugin {

	function __construct() {
		
		// Priority: first section, load before anything else
		add_action('plugins_loaded', array($this, 'init'), 0);
	}
	function init() {

		$this->initialize();
		define('POP_CMS_INITIALIZED', true);
	}
	function initialize() {

		require_once 'initialization.php';
		$initialization = new Initialization();
		return $initialization->initialize();	
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
if (!defined('POP_SERVER_DISABLEPOP') || !POP_SERVER_DISABLEPOP) {
	new Plugin();
}