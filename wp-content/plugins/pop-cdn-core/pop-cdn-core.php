<?php
/*
Plugin Name: PoP CDN for Core Processors
Description: Implementation of the CDN for the Core Processors plugin for PoP
Plugin URI: https://getpop.org
Version: 1.0
Author: Leonardo Losoviz
Author URI: https://getpop.org/en/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define ('POP_CDNCORE_VERSION', 0.151);
define ('POP_CDNCORE_DIR', dirname(__FILE__));
define ('POP_CDNCORE_ASSETS_DIR', POP_CDNCORE_DIR.'/kernel/cdn/assets');

class PoP_CDNCore {

	function __construct() {

		// Priority: after PoP Core Processors loaded
		add_action('plugins_loaded', array($this,'init'), 35);
		add_action('PoP:system-install', array($this,'system_install'));
	}
	function init(){

		define ('POP_CDNCORE_URI', plugins_url('', __FILE__));

		// Allow the Theme to override the cache folder (eg: to add a custom folder after ir, eg: pop-cache/mesym/)
		if (!defined ('POP_CDNCORE_ASSETDESTINATION_DIR')) {
			define ('POP_CDNCORE_ASSETDESTINATION_DIR', WP_CONTENT_DIR.'/pop-cdn');
		}
		if (!defined ('POP_CDNCORE_ASSETDESTINATION_URI')) {
			define ('POP_CDNCORE_ASSETDESTINATION_URI', WP_CONTENT_URL.'/pop-cdn');
		}

		if ($this->validate()) {
			
			$this->initialize();
			define('POP_CDNCORE_INITIALIZED', true);
		}
	}
	function validate(){
		
		require_once 'validation.php';
		$validation = new PoP_CDNCore_Validation();
		return $validation->validate();	
	}
	function system_install(){

		require_once 'installation.php';
		$installation = new PoP_CDNCore_Installation();
		return $installation->system_install();	
	}	
	function initialize(){

		require_once 'initialization.php';
		global $PoP_CDNCore_Initialization;
		return $PoP_CDNCore_Initialization->initialize();	
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_CDNCore();
