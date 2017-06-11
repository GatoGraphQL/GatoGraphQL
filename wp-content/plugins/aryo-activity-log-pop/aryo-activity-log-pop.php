<?php
/*
Plugin Name: Aryo Activity Log for PoP
Version: 1.0
Description: Aryo Activity Log for the Platform of Platforms (PoP)
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
Author URI: https://getpop.org/u/leo/
*/

define('AAL_POP_VERSION', 0.102);
define('AAL_POP_DIR', dirname(__FILE__));

define( 'AAL_POP_LOG__FILE__', __FILE__ );
define( 'AAL_POP_LOG_BASE', plugin_basename( AAL_POP_LOG__FILE__ ) );

class AAL_PoP {

	function __construct() {

		// The maintenance must be executed before 'load_plugins' or 'init' take place, so place it here
		// https://codex.wordpress.org/Function_Reference/register_activation_hook
		require_once 'maintenance/load.php';

		// Priority: after POP loaded
		add_action('plugins_loaded', array($this,'init'), 50);
	}

	function init(){

		define('AAL_POP_URI', plugins_url('', __FILE__));
		
		if ($this->validate()) {
			
			$this->initialize();
			define('AAL_POP_INITIALIZED', true);
		}
	}

	function validate(){
		
		require_once 'validation.php';
		$validation = new AAL_PoP_Validation();
		return $validation->validate();	
	}
	function initialize(){

		require_once 'initialization.php';
		$initialization = new AAL_PoP_Initialization();
		return $initialization->initialize();	
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new AAL_PoP();
