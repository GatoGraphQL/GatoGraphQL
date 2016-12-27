<?php
/*
Plugin Name: PoP PrettyPrint
Version: 1.0
Description: Functionality for doing the PrettyPrint using Google's library: https://github.com/google/code-prettify
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
Author URI: https://getpop.org/u/leo/
*/

define('POP_PRETTYPRINT_VERSION', 0.102);
define('POP_PRETTYPRINT_DIR', dirname(__FILE__));

class PoP_PrettyPrint {

	function __construct() {
		
		// Priority: after PoP Core Processors loaded
		add_action('plugins_loaded', array($this,'init'), 60);
	}

	function init(){

		define('POP_PRETTYPRINT_URI', plugins_url('', __FILE__));
		
		if ($this->validate()) {
			
			$this->initialize();
			define('POP_PRETTYPRINT_INITIALIZED', true);
		}
	}

	function validate(){
		
		require_once 'validation.php';
		$validation = new PoP_PrettyPrint_Validation();
		return $validation->validate();	
	}
	function initialize(){

		require_once 'initialization.php';
		$initialization = new PoP_PrettyPrint_Initialization();
		return $initialization->initialize();	
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_PrettyPrint();
