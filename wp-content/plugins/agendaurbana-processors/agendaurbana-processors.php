<?php
/*
Plugin Name: Agenda Urbana Processors
Version: 1.0
Description: Processors for the Agenda Urbana website, implemented using the Wassup Theme for the PoP—Platform of Platforms
Plugin URI: https://agendaurbana.org/
Author: Leonardo Losoviz
Author URI: https://getpop.org/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define ('AGENDAURBANA_PROCESSORS_VERSION', 0.102);
define ('AGENDAURBANA_PROCESSORS_DIR', dirname(__FILE__));

class AgendaUrbana_Processors {

	function __construct() {
		
		// Priority: after MESYM PoPTheme
		add_action('plugins_loaded', array($this,'init'), 120);
		add_action('PoP:version', array($this,'version'), 120);
	}
	function version($version){

		return AGENDAURBANA_PROCESSORS_VERSION;
	}

	function init(){

		define ('AGENDAURBANA_PROCESSORS_URI', plugins_url('', __FILE__));
		
		if ($this->validate()) {
			
			$this->initialize();
			define('AGENDAURBANA_PROCESSORS_INITIALIZED', true);
		}
	}

	function validate(){
		
		require_once 'validation.php';
		$validation = new AgendaUrbana_Processors_Validation();
		return $validation->validate();	
	}
	function initialize(){

		require_once 'initialization.php';
		$initialization = new AgendaUrbana_Processors_Initialization();
		return $initialization->initialize();	
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new AgendaUrbana_Processors();
