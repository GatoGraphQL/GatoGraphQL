<?php
/*
Plugin Name: PoP Service Workers
Description: Implementation of Service Workers for PoP
Plugin URI: https://getpop.org
Version: 1.0
Author: Leonardo Losoviz
Author URI: https://getpop.org/en/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define ('POP_SERVICEWORKERS_VERSION', 0.126);
define ('POP_SERVICEWORKERS_DIR', dirname(__FILE__));
define ('POP_SERVICEWORKERS_ASSETS_DIR', POP_SERVICEWORKERS_DIR.'/kernel/serviceworkers/assets');

class PoP_ServiceWorkers {

	function __construct() {

		/**---------------------------------------------------------------------------------------------------------------
		 * WP Overrides
		 * ---------------------------------------------------------------------------------------------------------------*/
		// require_once dirname(__FILE__).'/wp-includes/load.php';
		
		// Priority: after PoP Core Processors loaded
		add_action('plugins_loaded', array($this,'init'), 35);
		add_action('PoP:system-generate', array($this,'system_generate'));
	}
	function init(){

		define ('POP_SERVICEWORKERS_URI', plugins_url('', __FILE__));

		// Comment Leo 29/09/2017: We can't comment this file and use the /pop-content path, because SW cannot change its filename!!!! Bleh
		// define ('POP_SERVICEWORKERS_ASSETDESTINATION_DIR', POP_CONTENT_DIR.'/pop-serviceworkers');
		// define ('POP_SERVICEWORKERS_ASSETDESTINATION_URI', POP_CONTENT_URL.'/pop-serviceworkers');
		// Allow the Theme to override the cache folder (eg: to add a custom folder after ir, eg: pop-cache/mesym/)
		if (!defined ('POP_SERVICEWORKERS_ASSETDESTINATION_DIR')) {
			define ('POP_SERVICEWORKERS_ASSETDESTINATION_DIR', WP_CONTENT_DIR.'/pop-serviceworkers');
		}
		if (!defined ('POP_SERVICEWORKERS_ASSETDESTINATION_URI')) {
			define ('POP_SERVICEWORKERS_ASSETDESTINATION_URI', WP_CONTENT_URL.'/pop-serviceworkers');
		}

		if ($this->validate()) {
			
			$this->initialize();
			define('POP_SERVICEWORKERS_INITIALIZED', true);
		}
	}
	function validate(){
		
		require_once 'validation.php';
		$validation = new PoP_ServiceWorkers_Validation();
		return $validation->validate();	
	}
	function system_generate(){

		require_once 'installation.php';
		$installation = new PoP_ServiceWorkers_Installation();
		return $installation->system_generate();	
	}	
	function initialize(){

		require_once 'initialization.php';
		global $PoP_ServiceWorkers_Initialization;
		return $PoP_ServiceWorkers_Initialization->initialize();	
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_ServiceWorkers();
