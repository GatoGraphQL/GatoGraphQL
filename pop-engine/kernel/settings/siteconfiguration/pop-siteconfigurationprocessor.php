<?php
namespace PoP\Engine\Settings;

class SiteConfigurationProcessorBase {

	function __construct() {

		SiteConfigurationProcessorManager_Factory::get_instance()->set($this);
	}

	function get_entry_module() {

		return null;
	}
}