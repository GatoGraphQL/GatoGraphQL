<?php
/*
Plugin Name: PoP Theme: Wassup
Version: 1.0
Description: Wassup Theme for PoP—Platform of Platforms
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
Author URI: https://getpop.org/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define ('POPTHEME_WASSUP_VERSION', 0.176);
define ('POPTHEME_WASSUP_VENDORRESOURCESVERSION', 0.100);

define ('POPTHEME_WASSUP_DIR', dirname(__FILE__));
define ('POPTHEME_WASSUP_PHPTEMPLATES_DIR', POPTHEME_WASSUP_DIR.'/php-templates/compiled');
define ('POPTHEME_WASSUP_TEMPLATES', POPTHEME_WASSUP_DIR.'/templates');
define ('POPTHEME_WASSUP_THEMES', POPTHEME_WASSUP_DIR.'/themes');
define ('POPTHEME_WASSUP_PLUGINS', POPTHEME_WASSUP_DIR.'/plugins');
define ('POPTHEME_WASSUP_ORIGINURI', plugins_url('', __FILE__));
define ('POPTHEME_WASSUP_ORIGINURI_PLUGINS', POPTHEME_WASSUP_ORIGINURI.'/plugins');

class PoPTheme_Wassup {

	function __construct() {

		/**---------------------------------------------------------------------------------------------------------------
		 * WP Overrides
		 * ---------------------------------------------------------------------------------------------------------------*/
		require_once dirname(__FILE__).'/wp-includes/load.php';
		
		// Priority: after PoPProcessors and EMPoPProcessors loaded
		add_action('plugins_loaded', array($this,'init'), 110);
		add_action('PoP:version', array($this,'version'), 110);
		add_action('PoP:system-activateplugins', array($this,'system_activateplugins'));
		add_action('PoP:system-generate', array($this,'system_generate'));
	}
	function version($version){

		return POPTHEME_WASSUP_VERSION;
	}

	function init(){

		define ('POPTHEME_WASSUP_URL', plugins_url('', __FILE__));
		define ('POPTHEME_WASSUP_PLUGINSURI', POPTHEME_WASSUP_URL.'/plugins');

		define ('POPTHEME_WASSUP_THEMECUSTOMIZATION_ASSETDESTINATION_DIR', POP_CONTENT_DIR.'/theme-custom');
		define ('POPTHEME_WASSUP_THEMECUSTOMIZATION_ASSETDESTINATION_URL', POP_CONTENT_URL.'/theme-custom');

		if ($this->validate()) {
			
			$this->initialize();
			define('POPTHEME_WASSUP_INITIALIZED', true);
		}
	}

	function validate() {
		
		require_once 'validation.php';
		$validation = new PoPTheme_Wassup_Validation();
		return $validation->validate();	
	}
	function initialize() {

		require_once 'initialization.php';
		$initialization = new PoPTheme_Wassup_Initialization();
		return $initialization->initialize();	
	}
	function system_activateplugins() {

		require_once 'installation.php';
		$installation = new PoPTheme_Wassup_Installation();
		return $installation->system_activateplugins();	
	}	
	function system_generate() {

		require_once 'installation.php';
		$installation = new PoPTheme_Wassup_Installation();
		return $installation->system_generate();	
	}	
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup();
