<?php
/*
Plugin Name: PoP User Avatar
Version: 1.0
Description: User Avatar for the Platform of Platforms (PoP)
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
Author URI: https://getpop.org/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define ('POP_USERAVATAR_VERSION', 0.103);
define ('POP_USERAVATAR_DIR', dirname(__FILE__));

class PoP_UserAvatar {

	function __construct() {
		
		// Priority: after PoP WP Core Processors loaded
		add_action('plugins_loaded', array($this,'init'), 35);
		add_action('PoP:version', array($this,'version'), 35);
	}
	function version($version){

		return POP_USERAVATAR_VERSION;
	}
	function init(){

		define ('POP_USERAVATAR_URI', plugins_url('', __FILE__));
		define ('POP_USERAVATAR_URI_LIB', POP_USERAVATAR_URI.'/library' );

		// add_action('admin_init', array($this, 'install'));
		if ($this->validate()) {
			
			$this->initialize();
			define('POP_USERAVATAR_INITIALIZED', true);
		}
	}
	function validate(){
		
		require_once 'validation.php';
		$validation = new PoP_UserAvatar_Validation();
		return $validation->validate();	
	}
	// function install(){

	// 	require_once 'installation.php';
	// 	$installation = new PoP_UserAvatar_Installation();
	// 	return $installation->install();	
	// }	
	function initialize(){

		require_once 'initialization.php';
		global $PoP_UserAvatar_Initialization;
		return $PoP_UserAvatar_Initialization->initialize();	
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_UserAvatar();
