<?php
/*
Plugin Name: PoP Example Modules
Version: 1.0
Description: The foundation for a PoP Example Modules
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
Author URI: https://getpop.org/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define ('POP_EXAMPLEMODULES_VERSION', 0.106);
define ('POP_EXAMPLEMODULES_DIR', dirname(__FILE__));
define ('POP_EXAMPLEMODULES_LIB', POP_EXAMPLEMODULES_DIR.'/library');

class PoP_ExampleModules {

	function __construct(){
		
		// Priority: new section, after PoP Engine section
		add_action('plugins_loaded', array($this, 'init'), 200);
		add_action('PoP:version', array($this, 'version'), 200);
	}
	function version($version){

		return POP_EXAMPLEMODULES_VERSION;
	}
	function init(){
		
		if ($this->validate()) {
			
			$this->initialize();
			define('POP_EXAMPLEMODULES_INITIALIZED', true);
		}
	}
	function validate(){
		
		require_once 'validation.php';
		$validation = new PoP_ExampleModules_Validation();
		return $validation->validate();	
	}
	function initialize(){

		require_once 'initialization.php';
		$initialization = new PoP_ExampleModules_Initialization();
		return $initialization->initialize();	
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
if (!defined('POP_SERVER_DISABLEPOP') || !POP_SERVER_DISABLEPOP) {
	new PoP_ExampleModules();
}