<?php
/*
Plugin Name: PoP Generic Forms Processors
Version: 1.0
Description: Collection of processors for the Generic Forms plugin for the Platform of Platforms
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
Author URI: https://getpop.org/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define ('POP_GENERICFORMSPROCESSORS_VERSION', 0.229);
define ('POP_GENERICFORMSPROCESSORS_DIR', dirname(__FILE__));

class PoP_GenericFormsProcessors {

	function __construct() {
		
		// Priority: after Generic Forms loaded
		add_action('plugins_loaded', array($this,'init'), 50);
	}
	function init() {

		if ($this->validate()) {
			
			$this->initialize();
			define('POP_GENERICFORMSPROCESSORS_INITIALIZED', true);
		}
	}
	function validate(){
		
		require_once 'validation.php';
		$validation = new PoP_GenericFormsProcessors_Validation();
		return $validation->validate();	
	}
	function initialize(){

		require_once 'initialization.php';
		global $PoP_GenericFormsProcessors_Initialization;
		return $PoP_GenericFormsProcessors_Initialization->initialize();	
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_GenericFormsProcessors();
