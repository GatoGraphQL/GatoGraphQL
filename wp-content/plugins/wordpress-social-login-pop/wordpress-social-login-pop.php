<?php
/*
Plugin Name: Wordpress Social Login PoP
Version: 1.0
Description: Wordpress Social Login for the Platform of Platforms (PoP)
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
Author URI: https://getpop.org/u/leo/
*/

define('WSL_POP_VERSION', 0.105);
define('WSL_POP_DIR', dirname(__FILE__));

/**---------------------------------------------------------------------------------------------------------------
 * Includes: load it initially, as to override the functions from WSL
 * ---------------------------------------------------------------------------------------------------------------*/
require_once 'includes/load.php';

class WSL_PoP {

	function __construct() {
		
		// After Aryo Activity Log PoP and WSL PoP
		add_action('plugins_loaded', array($this,'init'), 60);
	}

	function init(){

		define('WSL_POP_URI', plugins_url('', __FILE__));
		
		if ($this->validate()) {
			
			$this->initialize();
			define('WSL_POP_INITIALIZED', true);
		}
	}

	function validate(){
		
		require_once 'validation.php';
		$validation = new WSL_PoP_Validation();
		return $validation->validate();	
	}
	function initialize(){

		require_once 'initialization.php';
		$initialization = new WSL_PoP_Initialization();
		return $initialization->initialize();	
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new WSL_PoP();
