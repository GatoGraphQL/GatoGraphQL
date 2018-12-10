<?php

class PoP_Module_SiteConfigurationProcessor_Manager {

	var $processor;
	
	function __construct() {

		PoPEngine_Module_SiteConfigurationProcessorManager_Factory::set_instance($this);
	}

	function get_processor() {

		return $this->processor;
	}
	
	function set($processor) {

		$this->processor = $processor;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_Module_SiteConfigurationProcessor_Manager();
