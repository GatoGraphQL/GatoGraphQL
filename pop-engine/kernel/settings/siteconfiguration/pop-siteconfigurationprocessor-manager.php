<?php
namespace PoP\Engine\Settings;

class SiteConfigurationProcessor_Manager {

	var $processor;
	
	function __construct() {

		SiteConfigurationProcessorManager_Factory::set_instance($this);
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
new SiteConfigurationProcessor_Manager();
