<?php
/*
Plugin Name: PhotoSwipe for PoP
Version: 1.0
Description: PhotoSwipe for the Platform of Platforms (PoP)
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
Author URI: https://getpop.org/u/leo/
*/

define('PHOTOSWIPEPOP_VERSION', 0.104);
define('PHOTOSWIPEPOP_PHOTOSWIPE_VERSION', '4.1.1');
define('PHOTOSWIPEPOP_DIR', dirname(__FILE__));

class PhotoSwipe_PoP {

	function __construct() {
		
		// Priority: after POP loaded
		add_action('plugins_loaded', array($this,'init'), 100);
	}

	function init(){

		define('PHOTOSWIPEPOP_URI', plugins_url('', __FILE__));
		
		if ($this->validate()) {
			
			$this->initialize();
			define('PHOTOSWIPEPOP_INITIALIZED', true);
		}
	}

	function validate(){
		
		require_once 'validation.php';
		$validation = new PhotoSwipe_PoP_Validation();
		return $validation->validate();	
	}
	function initialize(){

		require_once 'initialization.php';
		$initialization = new PhotoSwipe_PoP_Initialization();
		return $initialization->initialize();	
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PhotoSwipe_PoP();
