<?php
/*
Plugin Name: GetPoP Processors
Version: 1.0
Description: Processors for the GetPoP website, implemented using the Wassup Theme for the PoP—Platform of Platforms
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
Author URI: https://getpop.org/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define ('GETPOP_PROCESSORS_VERSION', 0.102);

define ('GETPOP_PROCESSORS_URI', plugins_url('', __FILE__));
define ('GETPOP_PROCESSORS_DIR', dirname(__FILE__));

class GetPoP_Processors {

	function __construct() {
		
		// Priority: after MESYM PoPTheme
		add_action('plugins_loaded', array($this,'init'), 120);
		add_action('PoP:version', array($this,'version'), 120);
	}
	function version($version){

		return GETPOP_PROCESSORS_VERSION;
	}

	function init(){
		
		if ($this->validate()) {
			
			$this->initialize();
			define('GETPOP_PROCESSORS_INITIALIZED', true);
		}
	}

	function validate(){
		
		require_once 'validation.php';
		$validation = new GetPoP_Processors_Validation();
		return $validation->validate();	
	}
	function initialize(){

		require_once 'initialization.php';
		$initialization = new GetPoP_Processors_Initialization();
		return $initialization->initialize();	
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GetPoP_Processors();
