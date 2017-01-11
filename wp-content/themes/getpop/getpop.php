<?php

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define ('GETPOP_VERSION', 0.205);

define ('GETPOP_DIR', STYLESHEETPATH);
define ('GETPOP_DIR_RESOURCES', GETPOP_DIR.'/resources');
define ('GETPOP_LIB', GETPOP_DIR.'/library' );
define ('GETPOP_PLUGINS_DIR', GETPOP_LIB.'/plugins');

define ('GETPOP_URI', get_stylesheet_directory_uri());
define ('GETPOP_URI_PLUGINS', GETPOP_URI.'/plugins');

// Change the Uploads folder so many websites can use the same code but different uploads in DEV
// define ('UPLOADS', 'wp-content/uploads/getpop');

class GetPoP {

	function __construct(){
		
		add_action('init', array($this, 'init_constants'), 0);
		add_action('PoP:version', array($this,'version'), 10000);
		if ($this->validate()) {
			
			$this->initialize();
			define('GETPOP_INITIALIZED', true);
		}
	}
	function init_constants() {

		// If we have a CDN URI, then use it for the assets
		if (defined('POP_AWS_CDN_ASSETS_URI') && POP_AWS_CDN_ASSETS_URI) {
			define ('GETPOP_ASSETS_URI', str_replace(get_site_url(), POP_AWS_CDN_ASSETS_URI, GETPOP_URI));
		}
		else {
			define ('GETPOP_ASSETS_URI', GETPOP_URI);
		}
	}
	function version($version){

		return GETPOP_VERSION;
	}
	function validate(){
		
		require_once 'validation.php';
		$validation = new GetPoP_Validation();
		return $validation->validate();	
	}	
	function initialize(){

		require_once 'initialization.php';
		$initialization = new GetPoP_Initialization();
		return $initialization->initialize();	
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GetPoP();
