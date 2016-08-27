<?php
/*
Plugin Name: PoP User Avatar for AWS
Version: 1.0
Description: Use AWS for the User Avatar for the Platform of Platforms (PoP)
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
Author URI: https://getpop.org/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define ('POP_USERAVATAR_AWS_VERSION', 0.101);
define ('POP_USERAVATAR_AWS_DIR', dirname(__FILE__));

define ('POP_USERAVATAR_AWS_URI', plugins_url('', __FILE__));
define ('POP_USERAVATAR_AWS_URI_LIB', POP_USERAVATAR_AWS_URI.'/library' );

class PoP_UserAvatar_AWS {

	function __construct() {

		// Priority: after User Avatar PoP and PoP AWS loaded
		add_action('plugins_loaded', array($this,'init'), 40);
		add_action('PoP:version', array($this,'version'), 40);
	}
	function version($version){

		return POP_USERAVATAR_AWS_VERSION;
	}
	function init(){

		// add_action('admin_init', array($this, 'install'));
		if ($this->validate()) {
			
			$this->initialize();
			define('POP_USERAVATAR_AWS_INITIALIZED', true);
		}
	}
	function validate(){
		
		require_once 'validation.php';
		$validation = new PoP_UserAvatar_AWS_Validation();
		return $validation->validate();	
	}
	function initialize(){

		require_once 'initialization.php';
		$initialization = new PoP_UserAvatar_AWS_Initialization();
		return $initialization->initialize();	
		// global $PoP_UserAvatar_AWS_Initialization;
		// return $PoP_UserAvatar_AWS_Initialization->initialize();	
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_UserAvatar_AWS();
