<?php
/*
Plugin Name: Wordpress Social Login PoP Processors
Version: 1.0
Description: Collection of Processors for Wordpress Social Login for the Platform of Platforms (PoP)
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
Author URI: https://getpop.org/u/leo/
*/

define('WSL_POPPROCESSORS_VERSION', 0.102);
define('WSL_POPPROCESSORS_DIR', dirname(__FILE__));

class WSL_PoPProcessors {

	function __construct() {
		
		// Priority: after PoP Core Processors loaded
		add_action('plugins_loaded', array($this,'init'), 60);
	}

	function init(){

		define('WSL_POPPROCESSORS_URI', plugins_url('', __FILE__));
		
		if ($this->validate()) {
			
			$this->initialize();
			define('WSL_POPPROCESSORS_INITIALIZED', true);
		}
	}

	function validate(){
		
		require_once 'validation.php';
		$validation = new WSL_PoPProcessors_Validation();
		return $validation->validate();	
	}
	function initialize(){

		require_once 'initialization.php';
		$initialization = new WSL_PoPProcessors_Initialization();
		return $initialization->initialize();	
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new WSL_PoPProcessors();
