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
define ('POP_SERVICEWORKERS_VERSION', 0.1);
define ('POP_SERVICEWORKERS_DIR', dirname(__FILE__));
define ('POP_SERVICEWORKERS_ASSETS_DIR', POP_SERVICEWORKERS_DIR.'/kernel/serviceworkers/assets');

class PoP_ServiceWorkers {

	function __construct() {
		
		// Priority: after PoP WP Processors loaded
		add_action('plugins_loaded', array($this,'init'), 30);
		add_action('PoP:install', array($this,'install'), 10, 1);
	}
	function init(){

		define ('POP_SERVICEWORKERS_URI', plugins_url('', __FILE__));

		// Allow the Theme to override the cache folder (eg: to add a custom folder after ir, eg: pop-cache/mesym/)
		if (!defined ('POP_SERVICEWORKERS_ASSETDESTINATION_DIR')) {
			define ('POP_SERVICEWORKERS_ASSETDESTINATION_DIR', WP_CONTENT_DIR.'/pop-serviceworkers');
		}
		if (!defined ('POP_SERVICEWORKERS_ASSETDESTINATION_URI')) {
			define ('POP_SERVICEWORKERS_ASSETDESTINATION_URI', WP_CONTENT_URL.'/pop-serviceworkers');
		}

		// add_action('admin_init', array($this, 'install'));
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
	function install($version){

		require_once 'installation.php';
		$installation = new PoP_ServiceWorkers_Installation();
		return $installation->install($version);	
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
