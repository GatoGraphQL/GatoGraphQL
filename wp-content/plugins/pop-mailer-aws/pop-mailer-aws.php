<?php
/*
Plugin Name: PoP Mailer for AWS
Version: 1.0
Description: Use AWS for Sending emails for the Platform of Platforms (PoP). It uses a combination of S3, Lambda and SES to send the emails in an asynchronous way.
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
Author URI: https://getpop.org/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define ('POP_MAILER_AWS_VERSION', 0.101);
define ('POP_MAILER_AWS_DIR', dirname(__FILE__));
define ('POP_MAILER_AWS_DIR_RESOURCES', POP_MAILER_AWS_DIR.'/resources');

define ('POP_MAILER_AWS_URI', plugins_url('', __FILE__));

class PoP_Mailer_AWS {

	function __construct() {

		/**---------------------------------------------------------------------------------------------------------------
		 * WP Overrides
		 * ---------------------------------------------------------------------------------------------------------------*/
		require_once dirname(__FILE__).'/wp-includes/load.php';

		// Priority: after PoP AWS and PoP Email Sender loaded
		add_action('plugins_loaded', array($this,'init'), 50);
	}
	function init(){

		// add_action('admin_init', array($this, 'install'));
		if ($this->validate()) {
			
			$this->initialize();
			define('POP_MAILER_AWS_INITIALIZED', true);
		}
	}
	function validate(){
		
		require_once 'validation.php';
		$validation = new PoP_Mailer_AWS_Validation();
		return $validation->validate();	
	}
	function initialize(){

		require_once 'initialization.php';
		$initialization = new PoP_Mailer_AWS_Initialization();
		return $initialization->initialize();
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_Mailer_AWS();
