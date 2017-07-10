<?php
/*
Plugin Name: Events Manager for PoP
Version: 1.0
Description: Events Manager for the Platform of Platforms (PoP)
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
Author URI: https://getpop.org/u/leo/
*/

define('EMPOP_VERSION', 0.103);
define('EMPOP_DIR', dirname(__FILE__));

class EM_PoP {

	function __construct() {
		
		// Priority: after POP loaded
		add_action('plugins_loaded', array($this,'init'), 50);
	}

	function init(){

		define('EMPOP_URI', plugins_url('', __FILE__));
		
		if ($this->validate()) {
			
			$this->initialize();
			define('EMPOP_INITIALIZED', true);
		}
	}

	function validate(){
		
		require_once 'validation.php';
		$validation = new EM_PoP_Validation();
		return $validation->validate();	
	}
	function initialize(){

		require_once 'initialization.php';
		$initialization = new EM_PoP_Initialization();
		return $initialization->initialize();	
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new EM_PoP();
