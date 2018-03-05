<?php
/*
Plugin Name: PoP Generic Forms
Version: 1.0
Description: Implementation of generic forms for PoP sites.
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
Author URI: https://getpop.org/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define ('POP_GENERICFORMS_VERSION', 0.176);

define ('POP_GENERICFORMS_DIR', dirname(__FILE__));

class PoP_GenericForms {

	function __construct() {

		// Priority: after PoP Core Processors and PoP Email Sender loaded
		add_action('plugins_loaded', array($this,'init'), 40);
	}
	function version($version){

		return POP_GENERICFORMS_VERSION;
	}

	function init(){

		define ('POP_GENERICFORMS_URL', plugins_url('', __FILE__));
		
		if ($this->validate()) {
			
			$this->initialize();
			define('POP_GENERICFORMS_INITIALIZED', true);
		}
	}

	function validate() {
		
		require_once 'validation.php';
		$validation = new PoP_GenericForms_Validation();
		return $validation->validate();	
	}
	function initialize() {

		require_once 'initialization.php';
		$initialization = new PoP_GenericForms_Initialization();
		return $initialization->initialize();	
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_GenericForms();
