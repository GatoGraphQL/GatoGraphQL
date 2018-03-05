<?php
/*
Plugin Name: Gravity Forms for PoP
Version: 1.0
Description: Integration of plugin Gravity Forms with PoP.
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
Author URI: https://getpop.org/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define ('GFPOP_VERSION', 0.176);

define ('GFPOP_DIR', dirname(__FILE__));

class GFPoP {

	function __construct() {

		add_action('plugins_loaded', array($this,'init'), 60);
	}

	function init(){

		define ('GFPOP_URL', plugins_url('', __FILE__));

		if ($this->validate()) {
			
			$this->initialize();
			define('GFPOP_INITIALIZED', true);
		}
	}

	function validate() {
		
		require_once 'validation.php';
		$validation = new GFPoP_Validation();
		return $validation->validate();	
	}
	function initialize() {

		require_once 'initialization.php';
		$initialization = new GFPoP_Initialization();
		return $initialization->initialize();	
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GFPoP();
