<?php
/*
Plugin Name: PoP Email Sender
Version: 1.0
Description: Utilities for sending emails for the Platform of Platforms (PoP)
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
Author URI: https://getpop.org/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define ('POP_EMAILSENDER_VERSION', 0.107);
define ('POP_EMAILSENDER_DIR', dirname(__FILE__));

class PoP_EmailSender {

	function __construct() {

		// Priority: after PoP WP API loaded
		add_action('plugins_loaded', array($this,'init'), 30);
	}
	function init(){

		if ($this->validate()) {
			
			$this->initialize();
			define('POP_EMAILSENDER_INITIALIZED', true);
		}
	}
	function validate(){
		
		require_once 'validation.php';
		$validation = new PoP_EmailSender_Validation();
		return $validation->validate();	
	}
	function initialize(){

		require_once 'initialization.php';
		$initialization = new PoP_EmailSender_Initialization();
		return $initialization->initialize();
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_EmailSender();
