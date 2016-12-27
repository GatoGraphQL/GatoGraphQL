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
define ('POP_EMAILSENDER_VERSION', 0.101);
define ('POP_EMAILSENDER_DIR', dirname(__FILE__));

class PoP_EmailSender {

	function __construct() {

		// Priority: after PoP AWS loaded
		add_action('plugins_loaded', array($this,'init'), 40);
	}
	function init(){

		define ('POP_EMAILSENDER_URI', plugins_url('', __FILE__));

		// add_action('admin_init', array($this, 'install'));
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
