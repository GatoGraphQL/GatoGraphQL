<?php
/*
Plugin Name: Events Manager PoP Processors
Version: 1.0
Description: Collection of Processors for Events Manager for the Platform of Platforms (PoP)
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
Author URI: https://getpop.org/u/leo/
*/

define('EM_POPPROCESSORS_VERSION', 0.122);
define('EM_POPPROCESSORS_VENDORRESOURCESVERSION', 0.100);
define('EM_POPPROCESSORS_EM_VERSION', 5.61);
define('EM_POPPROCESSORS_DIR', dirname(__FILE__));
define('EM_POPPROCESSORS_PHPTEMPLATES_DIR', EM_POPPROCESSORS_DIR.'/php-templates/compiled');

class EM_PoPProcessors {

	function __construct() {
		
		// Priority: after Events Manager for PoP and PoP Core Processors loaded
		add_action('plugins_loaded', array($this,'init'), 60);
	}

	function init(){

		define('EM_POPPROCESSORS_URL', plugins_url('', __FILE__));
		
		if ($this->validate()) {
			
			$this->initialize();
			define('EM_POPPROCESSORS_INITIALIZED', true);
		}
	}

	function validate(){
		
		require_once 'validation.php';
		$validation = new EM_PoPProcessors_Validation();
		return $validation->validate();	
	}
	function initialize(){

		require_once 'initialization.php';
		$initialization = new EM_PoPProcessors_Initialization();
		return $initialization->initialize();	
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new EM_PoPProcessors();
