<?php
/*
Plugin Name: PoP Theme Wassup: Section Processors
Version: 1.0
Description: Processors for different Sections for the Wassup Theme for PoP—Platform of Platforms
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
Author URI: https://getpop.org/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define ('POPTHEME_WASSUP_SECTIONPROCESSORS_VERSION', 0.102);
define ('POPTHEME_WASSUP_SECTIONPROCESSORS_DIR', dirname(__FILE__));

class PoPTheme_Wassup_SectionProcessors {

	function __construct() {
		
		// Priority: after MESYM PoPTheme
		add_action('plugins_loaded', array($this,'init'), 120);
		add_action('PoP:version', array($this,'version'), 120);
	}
	function version($version){

		return POPTHEME_WASSUP_SECTIONPROCESSORS_VERSION;
	}

	function init(){

		define ('POPTHEME_WASSUP_SECTIONPROCESSORS_URI', plugins_url('', __FILE__));
		
		if ($this->validate()) {
			
			$this->initialize();
			define('POPTHEME_WASSUP_SECTIONPROCESSORS_INITIALIZED', true);
		}
	}

	function validate(){
		
		require_once 'validation.php';
		$validation = new PoPTheme_Wassup_SectionProcessors_Validation();
		return $validation->validate();	
	}
	function initialize(){

		require_once 'initialization.php';
		$initialization = new PoPTheme_Wassup_SectionProcessors_Initialization();
		return $initialization->initialize();	
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_SectionProcessors();
