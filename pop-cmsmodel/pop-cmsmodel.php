<?php
/*
Plugin Name: PoP CMS Model
Version: 1.0
Description: The foundation for a PoP CMS Model
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
Author URI: https://getpop.org/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define ('POP_CMSMODEL_VERSION', 0.106);
define ('POP_CMSMODEL_DIR', dirname(__FILE__));
define ('POP_CMSMODEL_LIB', POP_CMSMODEL_DIR.'/library');

class PoP_CMSModel {

	function __construct(){
		
		// Priority: new section, after PoP Engine section
		add_action('plugins_loaded', array($this, 'init'), 200);
		add_action('PoP:version', array($this, 'version'), 200);
	}
	function version($version){

		return POP_CMSMODEL_VERSION;
	}
	function init(){
		
		if ($this->validate()) {
			
			$this->initialize();
			define('POP_CMSMODEL_INITIALIZED', true);
		}
	}
	function validate(){
		
		require_once 'validation.php';
		$validation = new PoP_CMSModel_Validation();
		return $validation->validate();	
	}
	function initialize(){

		require_once 'initialization.php';
		$initialization = new PoP_CMSModel_Initialization();
		return $initialization->initialize();	
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
if (!defined('POP_SERVER_DISABLEPOP') || !POP_SERVER_DISABLEPOP) {
	new PoP_CMSModel();
}