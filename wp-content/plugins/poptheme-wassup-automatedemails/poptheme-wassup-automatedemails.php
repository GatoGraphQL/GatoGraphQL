<?php
/*
Plugin Name: PoP Theme Wassup: Automated Emails
Version: 1.0
Description: Implementations of automated emails for the Wassup Theme for PoP—Platform of Platforms
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
Author URI: https://getpop.org/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define ('POPTHEME_WASSUP_AUTOMATEDEMAILS_VERSION', 0.107);
define ('POPTHEME_WASSUP_AUTOMATEDEMAILS_DIR', dirname(__FILE__));

class PoPTheme_Wassup_AutomatedEmails {

	function __construct() {
		
		// Priority: after MESYM PoPTheme
		add_action('plugins_loaded', array($this,'init'), 120);
	}

	function init(){
		
		if ($this->validate()) {
			
			$this->initialize();
			define('POPTHEME_WASSUP_AUTOMATEDEMAILS_INITIALIZED', true);
		}
	}

	function validate(){
		
		require_once 'validation.php';
		$validation = new PoPTheme_Wassup_AutomatedEmails_Validation();
		return $validation->validate();	
	}
	function initialize(){

		require_once 'initialization.php';
		$initialization = new PoPTheme_Wassup_AutomatedEmails_Initialization();
		return $initialization->initialize();	
	}
}
/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_AutomatedEmails();
