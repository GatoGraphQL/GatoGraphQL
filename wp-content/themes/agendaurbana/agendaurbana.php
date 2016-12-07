<?php

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define ('AGENDAURBANA_VERSION', 0.417);

define ('AGENDAURBANA_DIR', STYLESHEETPATH);
define ('AGENDAURBANA_DIR_RESOURCES', AGENDAURBANA_DIR.'/resources');
define ('AGENDAURBANA_LIB', AGENDAURBANA_DIR.'/library' );
define ('AGENDAURBANA_PLUGINS_DIR', AGENDAURBANA_LIB.'/plugins');

define ('AGENDAURBANA_URI', get_stylesheet_directory_uri());
define ('AGENDAURBANA_URI_PLUGINS', AGENDAURBANA_URI.'/plugins');

// If we have a CDN URI, then use it for the assets
if (defined('POP_AWS_CDN_ASSETS_URI') && POP_AWS_CDN_ASSETS_URI) {
	define ('AGENDAURBANA_ASSETS_URI', str_replace(get_site_url(), POP_AWS_CDN_ASSETS_URI, AGENDAURBANA_URI));
}
else {
	define ('AGENDAURBANA_ASSETS_URI', AGENDAURBANA_URI);
}

// Change the Uploads folder so many websites can use the same code but different uploads in DEV
// define ('UPLOADS', 'wp-content/uploads/agendaurbana');

class AgendaUrbana {

	function __construct(){
		
		add_action('PoP:version', array($this,'version'), 10000);
		if ($this->validate()) {
			
			$this->initialize();
			define('AGENDAURBANA_INITIALIZED', true);
		}
	}
	function version($version){

		return AGENDAURBANA_VERSION;
	}
	function validate(){
		
		require_once 'validation.php';
		$validation = new AgendaUrbana_Validation();
		return $validation->validate();	
	}	
	function initialize(){

		require_once 'initialization.php';
		$initialization = new AgendaUrbana_Initialization();
		return $initialization->initialize();	
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new AgendaUrbana();
