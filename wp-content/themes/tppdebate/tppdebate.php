<?php

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define ('TPPDEBATE_VERSION', 0.417);

define ('TPPDEBATE_DIR', STYLESHEETPATH);
define ('TPPDEBATE_DIR_RESOURCES', TPPDEBATE_DIR.'/resources');
define ('TPPDEBATE_LIB', TPPDEBATE_DIR.'/library' );
define ('TPPDEBATE_PLUGINS_DIR', TPPDEBATE_LIB.'/plugins');

define ('TPPDEBATE_URI', get_stylesheet_directory_uri());
define ('TPPDEBATE_URI_LIB', TPPDEBATE_URI.'/library');
define ('TPPDEBATE_URI_PLUGINS', TPPDEBATE_URI.'/plugins');

// If we have a CDN URI, then use it for the assets
if (defined('POP_AWS_CDN_ASSETS_URI') && POP_AWS_CDN_ASSETS_URI) {
	define ('TPPDEBATE_ASSETS_URI', str_replace(get_site_url(), POP_AWS_CDN_ASSETS_URI, TPPDEBATE_URI));
}
else {
	define ('TPPDEBATE_ASSETS_URI', TPPDEBATE_URI);
}

// Change the Uploads folder so many websites can use the same code but different uploads in DEV
// define ('UPLOADS', 'wp-content/uploads/tppdebate');

class TPPDebate {

	function __construct(){
		
		add_action('PoP:version', array($this,'version'), 10000);
		if ($this->validate()) {
			
			$this->initialize();
			define('TPPDEBATE_INITIALIZED', true);
		}
	}
	function version($version){

		return TPPDEBATE_VERSION;
	}
	function validate(){
		
		require_once 'validation.php';
		$validation = new TPPDebate_Validation();
		return $validation->validate();	
	}	
	function initialize(){

		require_once 'initialization.php';
		$initialization = new TPPDebate_Initialization();
		return $initialization->initialize();	
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new TPPDebate();
