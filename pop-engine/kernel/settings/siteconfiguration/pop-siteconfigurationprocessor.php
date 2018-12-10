<?php

class PoPEngine_Module_SiteConfigurationProcessorBase {

	function __construct() {

		PoPEngine_Module_SiteConfigurationProcessorManager_Factory::get_instance()->set($this);
	}

	function get_entry_module() {

		return null;
	}
}