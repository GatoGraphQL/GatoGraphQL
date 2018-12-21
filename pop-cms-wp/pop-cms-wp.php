<?php
/*
Plugin Name: PoP WordPress CMS
Version: 1.0
Description: Implementation of WordPress functions for PoP CMS
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
Author URI: https://getpop.org/u/leo/
*/
namespace PoP\CMS\WP;

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define ('POP_CMSWP_VERSION', 0.106);
define ('POP_CMSWP_DIR', dirname(__FILE__));
define ('POP_CMSWP_LIB', POP_CMSWP_DIR.'/library');

class Plugins {

	function __construct(){
		
		// Priority: mid-section, after PoP CMS section
		add_action('plugins_loaded', array($this, 'init'), 50);
	}
	function init(){
		
		if ($this->validate()) {
			
			$this->initialize();
			define('POP_CMSWP_INITIALIZED', true);
		}
	}
	function validate(){
		
		require_once 'validation.php';
		$validation = new Validation();
		return $validation->validate();	
	}
	function initialize(){

		require_once 'initialization.php';
		$initialization = new Initialization();
		return $initialization->initialize();	
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
if (!defined('POP_SERVER_DISABLEPOP') || !POP_SERVER_DISABLEPOP) {
	new Plugins();
}