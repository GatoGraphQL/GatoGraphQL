<?php
/*
Plugin Name: Gravity Forms for PoP Generic Forms
Version: 1.0
Description: Implementation of the Generic Forms plugin using Gravity Forms for PoP sites.
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
Author URI: https://getpop.org/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define ('POP_GFPOPGENERICFORMS_VERSION', 0.176);

define ('POP_GFPOPGENERICFORMS_DIR', dirname(__FILE__));

class PoP_GFPoPGenericForms {

	function __construct() {

		// Priority: after PoPProcessors and EMPoPProcessors loaded
		add_action('plugins_loaded', array($this,'init'), 70);
	}

	function init(){

		define ('POP_GFPOPGENERICFORMS_URL', plugins_url('', __FILE__));

		if ($this->validate()) {

			$this->initialize();
			define('POP_GFPOPGENERICFORMS_INITIALIZED', true);
		}
	}

	function validate() {
		
		require_once 'validation.php';
		$validation = new PoP_GFPoPGenericForms_Validation();
		return $validation->validate();	
	}
	function initialize() {

		require_once 'initialization.php';
		$initialization = new PoP_GFPoPGenericForms_Initialization();
		return $initialization->initialize();	
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_GFPoPGenericForms();
