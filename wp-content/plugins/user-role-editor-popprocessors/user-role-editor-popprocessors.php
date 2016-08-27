<?php
/*
Plugin Name: User Role Editor PoP Processors
Version: 1.0
Description: Collection of Processors for User Role Editor for the Platform of Platforms (PoP)
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
Author URI: https://getpop.org/u/leo/
*/

define('URE_POPPROCESSORS_VERSION', 0.102);
define('URE_POPPROCESSORS_URE_VERSION', '4.19.1');
define('URE_POPPROCESSORS_DIR', dirname(__FILE__));
define('URE_POPPROCESSORS_URI', plugins_url('', __FILE__));

class URE_PoPProcessors {

	function __construct() {
		
		// Priority: after PoP Core Processors loaded
		add_action('plugins_loaded', array($this,'init'), 50);
	}

	function init(){
		
		if ($this->validate()) {
			
			$this->initialize();
			define('URE_POPPROCESSORS_INITIALIZED', true);
		}
	}

	function validate(){
		
		require_once 'validation.php';
		$validation = new URE_PoPProcessors_Validation();
		return $validation->validate();	
	}
	function initialize(){

		require_once 'initialization.php';
		$initialization = new URE_PoPProcessors_Initialization();
		return $initialization->initialize();	
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new URE_PoPProcessors();
