<?php
/*
Plugin Name: PoP Automated Emails
Version: 1.0
Description: Library for sending automated emails for the Platform of Platforms (PoP)
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
Author URI: https://getpop.org/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define ('POP_AUTOMATEDEMAILS_VERSION', 0.106);
define ('POP_AUTOMATEDEMAILS_DIR', dirname(__FILE__));

class PoP_AutomatedEmails {

	function __construct() {

		// Priority: after PoP AWS and Email Sender loaded
		add_action('plugins_loaded', array($this,'init'), 50);
	}
	function init(){

		if ($this->validate()) {
			
			$this->initialize();
			define('POP_AUTOMATEDEMAILS_INITIALIZED', true);
		}
	}
	function validate(){
		
		require_once 'validation.php';
		$validation = new PoP_AutomatedEmails_Validation();
		return $validation->validate();	
	}
	function initialize(){

		require_once 'initialization.php';
		$initialization = new PoP_AutomatedEmails_Initialization();
		return $initialization->initialize();
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_AutomatedEmails();
