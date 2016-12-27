<?php
/*
Plugin Name: Organik Fundraising Processors
Version: 1.0
Description: Processors for the OrganikFundraising website, implemented using the Wassup Theme for the PoP—Platform of Platforms
Plugin URI: https://fundraising.organik.org.my/
Author: Leonardo Losoviz
Author URI: https://fundraising.organik.org.my/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define ('ORGANIKFUNDRAISING_PROCESSORS_VERSION', 0.102);
define ('ORGANIKFUNDRAISING_PROCESSORS_DIR', dirname(__FILE__));

class OrganikFundraising_Processors {

	function __construct() {
		
		// Priority: after MESYM PoPTheme
		add_action('plugins_loaded', array($this,'init'), 120);
		add_action('PoP:version', array($this,'version'), 120);
	}
	function version($version){

		return ORGANIKFUNDRAISING_PROCESSORS_VERSION;
	}

	function init(){

		define ('ORGANIKFUNDRAISING_PROCESSORS_URI', plugins_url('', __FILE__));
		
		if ($this->validate()) {
			
			$this->initialize();
			define('ORGANIKFUNDRAISING_PROCESSORS_INITIALIZED', true);
		}
	}

	function validate(){
		
		require_once 'validation.php';
		$validation = new OrganikFundraising_Processors_Validation();
		return $validation->validate();	
	}
	function initialize(){

		require_once 'initialization.php';
		$initialization = new OrganikFundraising_Processors_Initialization();
		return $initialization->initialize();	
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new OrganikFundraising_Processors();
