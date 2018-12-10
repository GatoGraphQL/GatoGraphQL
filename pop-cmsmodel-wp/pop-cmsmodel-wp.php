<?php
/*
Plugin Name: PoP CMS Model for WordPress
Version: 1.0
Description: Implementation of WordPress functions for PoP CMS
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
Author URI: https://getpop.org/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define ('POP_CMSMODELWP_VERSION', 0.106);
define ('POP_CMSMODELWP_DIR', dirname(__FILE__));
define ('POP_CMSMODELWP_LIB', POP_CMSMODELWP_DIR.'/library');

class PoP_CMSModelWP {

	function __construct(){
		
		require_once 'validation.php';
		add_filter(
			'PoP_CMSModel_Validation:provider-validation-class',
			array($this, 'get_provider_validation_class')
		);
		
		// Priority: mid section, after PoP CMS Model section
		add_action('plugins_loaded', array($this, 'init'), 250);
	}
	function get_provider_validation_class($class) {

		return PoP_CMSModelWP_Validation;
	}
	
	function init(){
		
		if ($this->validate()) {
			
			$this->initialize();
			define('POP_CMSMODELWP_INITIALIZED', true);
		}
	}
	function validate(){
		
		// require_once 'validation.php';
		$validation = new PoP_CMSModelWP_Validation();
		return $validation->validate();	
	}
	function initialize(){

		require_once 'initialization.php';
		$initialization = new PoP_CMSModelWP_Initialization();
		return $initialization->initialize();	
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
if (!defined('POP_SERVER_DISABLEPOP') || !POP_SERVER_DISABLEPOP) {
	new PoP_CMSModelWP();
}