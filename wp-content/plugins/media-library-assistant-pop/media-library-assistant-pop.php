<?php
/*
Plugin Name: Media Library Assistant for PoP
Version: 1.0
Description: Media Library Assistant for the Platform of Platforms (PoP)
Plugin URI: http://getpop.org/
Author: Leonardo Losoviz
Author URI: http://getpop.org/u/leo/
*/

define('MLAPOP_VERSION', 1.0);
define('MLAPOP_EM_VERSION', 5.61);
define('MLAPOP_DIR', dirname(__FILE__));

class MLA_PoP {

	function __construct() {
		
		// Priority: after POP loaded
		add_action('plugins_loaded', array(&$this,'init'), 100);
	}

	function init(){

		define('MLAPOP_URI', plugins_url('', __FILE__));
		
		if ($this->validate()) {
			
			$this->initialize();
			define('MLAPOP_INITIALIZED', true);
		}
	}

	function validate(){
		
		require_once 'validation.php';
		$validation = new MLA_PoP_Validation();
		return $validation->validate();	
	}
	function initialize(){

		require_once 'initialization.php';
		$initialization = new MLA_PoP_Initialization();
		return $initialization->initialize();	
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new MLA_PoP();
