<?php
/*
Plugin Name: PoP AWS
Version: 1.0
Description: Use AWS for the Platform of Platforms (PoP)
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
Author URI: https://getpop.org/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define ('POP_AWS_VERSION', 0.101);
define ('POP_AWS_DIR', dirname(__FILE__));

class PoP_AWS {

	function __construct() {
		
		// Priority 5: just after PoP Engine, and before everything else (except the "website-environment" plug-ins), 
		// so we can set the POP_AWS_CDN_ASSETS_URI constant in plugin_url before all other plug-ins need it
		add_action('plugins_loaded', array($this,'init'), 6);
		add_action('PoP:version', array($this,'version'), 6);
	}
	function version($version){

		return POP_AWS_VERSION;
	}
	function init(){

		// define ('POP_AWS_URI', plugins_url('', __FILE__));
		// define ('POP_AWS_URI_LIB', POP_AWS_URI.'/library' );

		// add_action('admin_init', array($this, 'install'));
		if ($this->validate()) {
			
			$this->initialize();
			define('POP_AWS_INITIALIZED', true);
		}
	}
	function validate(){
		
		require_once 'validation.php';
		$validation = new PoP_AWS_Validation();
		return $validation->validate();	
	}
	function initialize(){

		require_once 'initialization.php';
		$initialization = new PoP_AWS_Initialization();
		return $initialization->initialize();	
		// global $PoP_AWS_Initialization;
		// return $PoP_AWS_Initialization->initialize();	
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_AWS();
