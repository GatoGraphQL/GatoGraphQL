<?php
/*
Plugin Name: PoP Engine for WordPress
Version: 1.0
Description: Implementation of Module Definitions for PoP modules
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
Author URI: https://getpop.org/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define ('POP_ENGINEWP_VERSION', 0.108);
define ('POP_ENGINEWP_DIR', dirname(__FILE__));

class PoP_EngineWP {

	function __construct() {

		// Priority: after PoP Engine, inner circle
		add_action('plugins_loaded', array($this, 'init'), 102);
	}
	function init() {

		if ($this->validate()) {

			$this->initialize();
			define('POP_ENGINEWP_INITIALIZED', true);
		}
	}
	function validate(){
		
		require_once 'validation.php';
		$validation = new PoP_EngineWP_Validation();
		return $validation->validate();	
	}
	function initialize() {

		require_once 'initialization.php';
		$initialization = new PoP_EngineWP_Initialization();
		return $initialization->initialize();	
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
if (!defined('POP_SERVER_DISABLEPOP') || !POP_SERVER_DISABLEPOP) {
	new PoP_EngineWP();
}