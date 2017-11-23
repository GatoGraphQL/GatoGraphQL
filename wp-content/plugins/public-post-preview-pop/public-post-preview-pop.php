<?php
/*
Plugin Name: Public Post Preview for PoP
Version: 1.0
Description: Integration with plug-in Public Post Preview for the Platform of Platforms (PoP)
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
Author URI: https://getpop.org/u/leo/
*/

define('PPP_POP_VERSION', 0.106);
define('PPP_POP_DIR', dirname(__FILE__));

class PPP_PoP {

	function __construct() {
		
		// Priority: after PoP Core Processors loaded
		add_action('plugins_loaded', array($this,'init'), 60);
	}

	function init(){

		define('PPP_POP_URI', plugins_url('', __FILE__));
		
		if ($this->validate()) {
			
			$this->initialize();
			define('PPP_POP_INITIALIZED', true);
		}
	}

	function validate(){
		
		require_once 'validation.php';
		$validation = new PPP_PoP_Validation();
		return $validation->validate();	
	}
	function initialize(){

		require_once 'initialization.php';
		$initialization = new PPP_PoP_Initialization();
		return $initialization->initialize();	
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PPP_PoP();
