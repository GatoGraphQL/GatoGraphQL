<?php
/*
Plugin Name: Aryo Activity Log PoP Processors
Version: 1.0
Description: Collection of Processors for Aryo Activity Log for the Platform of Platforms (PoP)
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
Author URI: https://getpop.org/u/leo/
*/

define('AAL_POPPROCESSORS_VERSION', 0.106);
define('AAL_POPPROCESSORS_DIR', dirname(__FILE__));
define('AAL_POPPROCESSORS_URI', plugins_url('', __FILE__));

class AAL_PoPProcessors {

	function __construct() {
		
		// Priority: after Aryo Activity Log for PoP
		add_action('plugins_loaded', array($this,'init'), 60);
	}

	function init(){
		
		if ($this->validate()) {
			
			$this->initialize();
			define('AAL_POPPROCESSORS_INITIALIZED', true);
		}
	}

	function validate(){
		
		require_once 'validation.php';
		$validation = new AAL_PoPProcessors_Validation();
		return $validation->validate();	
	}
	function initialize(){

		require_once 'initialization.php';
		$initialization = new AAL_PoPProcessors_Initialization();
		return $initialization->initialize();	
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new AAL_PoPProcessors();
