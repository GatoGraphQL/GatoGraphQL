<?php

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define ('MESYM_VERSION', 0.331);

define ('MESYM_DIR', STYLESHEETPATH);
define ('MESYM_DIR_RESOURCES', MESYM_DIR.'/resources');
define ('MESYM_LIB', MESYM_DIR.'/library' );
define ('MESYM_PLUGINS_DIR', MESYM_LIB.'/plugins');

define ('MESYM_URI', get_stylesheet_directory_uri());
define ('MESYM_URI_PLUGINS', MESYM_URI.'/plugins');

// Change the Uploads folder so many websites can use the same code but different uploads in DEV
// define ('UPLOADS', 'wp-content/uploads/mesym');

class MESYM {

	function __construct(){
		
		add_action('init', array($this, 'init_constants'), 0);
		add_action('PoP:version', array($this,'version'), 10000);
		if ($this->validate()) {
			
			$this->initialize();
			define('MESYM_INITIALIZED', true);
		}
	}
	function init_constants() {

		// If we have a CDN URI, then use it for the assets
		if (defined('POP_AWS_CDN_ASSETS_URI') && POP_AWS_CDN_ASSETS_URI) {
			define ('MESYM_ASSETS_URI', str_replace(get_site_url(), POP_AWS_CDN_ASSETS_URI, MESYM_URI));
		}
		else {
			define ('MESYM_ASSETS_URI', MESYM_URI);
		}
	}
	function version($version){

		return MESYM_VERSION;
	}
	function validate(){
		
		require_once 'validation.php';
		$validation = new MESYM_Validation();
		return $validation->validate();	
	}	
	function initialize(){

		require_once 'initialization.php';
		$initialization = new MESYM_Initialization();
		return $initialization->initialize();	
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new MESYM();
