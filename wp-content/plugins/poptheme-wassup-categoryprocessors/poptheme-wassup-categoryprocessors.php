<?php
/*
Plugin Name: PoP Theme Wassup: Category Processors
Version: 1.0
Description: Processors for different Sections of the Category Website for the Wassup Theme for PoP—Platform of Platforms
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
Author URI: https://getpop.org/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define ('POPTHEME_WASSUP_CATEGORYPROCESSORS_VERSION', 0.107);
define ('POPTHEME_WASSUP_CATEGORYPROCESSORS_DIR', dirname(__FILE__));

class PoPTheme_Wassup_CategoryProcessors {

	function __construct() {
		
		// Priority: after MESYM PoPTheme
		add_action('plugins_loaded', array($this,'init'), 120);
		add_action('PoP:version', array($this,'version'), 120);
	}
	function version($version){

		return POPTHEME_WASSUP_CATEGORYPROCESSORS_VERSION;
	}

	function init(){
		
		define ('POPTHEME_WASSUP_CATEGORYPROCESSORS_URL', plugins_url('', __FILE__));

		if ($this->validate()) {
			
			$this->initialize();
			define('POPTHEME_WASSUP_CATEGORYPROCESSORS_INITIALIZED', true);
		}
	}

	function validate(){
		
		require_once 'validation.php';
		$validation = new PoPTheme_Wassup_CategoryProcessors_Validation();
		return $validation->validate();	
	}
	function initialize(){

		require_once 'initialization.php';
		$initialization = new PoPTheme_Wassup_CategoryProcessors_Initialization();
		return $initialization->initialize();	
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_CategoryProcessors();
