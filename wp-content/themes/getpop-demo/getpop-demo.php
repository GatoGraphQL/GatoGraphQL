<?php

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define ('GETPOPDEMO_VERSION', 0.290);

define ('GETPOPDEMO_DIR', STYLESHEETPATH);
define ('GETPOPDEMO_DIR_RESOURCES', GETPOPDEMO_DIR.'/resources');
define ('GETPOPDEMO_LIB', GETPOPDEMO_DIR.'/library' );
define ('GETPOPDEMO_PLUGINS_DIR', GETPOPDEMO_LIB.'/plugins');

define ('GETPOPDEMO_URI', get_stylesheet_directory_uri());
define ('GETPOPDEMO_URI_PLUGINS', GETPOPDEMO_URI.'/plugins');

define ('GETPOPDEMO_ORIGINURI', get_origin_stylesheet_directory_uri());
define ('GETPOPDEMO_ORIGINURI_PLUGINS', GETPOPDEMO_ORIGINURI.'/plugins');

// Change the Uploads folder so many websites can use the same code but different uploads in DEV
// define ('UPLOADS', 'wp-content/uploads/getpop');

class GetPoPDemo {

	function __construct(){
		
		add_action('init', array($this, 'init_constants'), 0);
		add_action('PoP:version', array($this,'version'), 10000);
		if ($this->validate()) {
			
			$this->initialize();
			define('GETPOPDEMO_INITIALIZED', true);
		}
	}
	function init_constants() {

		// If we have a CDN URI, then use it for the assets
		if (defined('POP_CDN_ASSETS_URI') && POP_CDN_ASSETS_URI) {
			define ('GETPOPDEMO_ASSETS_URI', str_replace(get_site_url(), POP_CDN_ASSETS_URI, GETPOPDEMO_URI));
		}
		else {
			define ('GETPOPDEMO_ASSETS_URI', GETPOPDEMO_URI);
		}
	}
	function version($version){

		return GETPOPDEMO_VERSION;
	}
	function validate(){
		
		require_once 'validation.php';
		$validation = new GetPoPDemo_Validation();
		return $validation->validate();	
	}	
	function initialize(){

		require_once 'initialization.php';
		$initialization = new GetPoPDemo_Initialization();
		return $initialization->initialize();	
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GetPoPDemo();
