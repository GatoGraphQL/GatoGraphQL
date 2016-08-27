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
define ('POP_FRONTENDENGINE_VERSION', 0.119);
define ('POP_FRONTENDENGINE_DIR', dirname(__FILE__));
define ('POP_FRONTENDENGINE_URI', plugins_url('', __FILE__));

class PoPFrontend {

	function __construct() {
		
		// Priority: after PoP WP Processors loaded
		add_action('plugins_loaded', array($this,'init'), 15);
		add_action('PoP:version', array($this,'version'), 15);
	}
	function version($version){

		return POP_FRONTENDENGINE_VERSION;
	}
	function init(){

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
