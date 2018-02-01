<?php
/*
Plugin Name: GetPoP Demo Processors
Version: 1.0
Description: Processors for the GetPoP Demo website, implemented using the Wassup Theme for the PoP—Platform of Platforms
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
Author URI: https://getpop.org/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define ('GETPOPDEMO_PROCESSORS_VERSION', 0.106);
define ('GETPOPDEMO_PROCESSORS_DIR', dirname(__FILE__));

class GetPoPDemo_Processors {

	function __construct() {
		
		// Priority: after MESYM PoPTheme
		add_action('plugins_loaded', array($this,'init'), 120);
		add_action('PoP:version', array($this,'version'), 120);
	}
	function version($version){

		return GETPOPDEMO_PROCESSORS_VERSION;
	}

	function init(){

		if ($this->validate()) {
			
			$this->initialize();
			define('GETPOPDEMO_PROCESSORS_INITIALIZED', true);
		}
	}

	function validate(){
		
		require_once 'validation.php';
		$validation = new GetPoPDemo_Processors_Validation();
		return $validation->validate();	
	}
	function initialize(){

		require_once 'initialization.php';
		$initialization = new GetPoPDemo_Processors_Initialization();
		return $initialization->initialize();	
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GetPoPDemo_Processors();
