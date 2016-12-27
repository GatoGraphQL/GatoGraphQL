<?php
/*
Plugin Name: PoP System
Version: 1.0
Description: Access system functionalities for the Platform of Platforms
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
Author URI: https://getpop.org/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define ('POP_SYSTEM_VERSION', 0.203);
define ('POP_SYSTEM_DIR', dirname(__FILE__));

class PoP_System {

	function __construct() {
		
		// Priority: after PoP WP Processors loaded
		add_action('plugins_loaded', array($this,'init'), 30);
	}
	function init(){

		define ('POP_SYSTEM_URI', plugins_url('', __FILE__));
		
		// add_action('admin_init', array($this, 'install'));
		if ($this->validate()) {
			
			$this->initialize();
			define('POP_SYSTEM_INITIALIZED', true);
		}
	}
	function validate(){
		
		require_once 'validation.php';
		$validation = new PoP_System_Validation();
		return $validation->validate();	
	}
	// function install(){

	// 	require_once 'installation.php';
	// 	$installation = new PoP_System_Installation();
	// 	return $installation->install();	
	// }	
	function initialize(){

		require_once 'initialization.php';
		global $PoP_System_Initialization;
		return $PoP_System_Initialization->initialize();	
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_System();
