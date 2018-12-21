<?php
namespace PoP\Engine\Settings;

abstract class DefaultSettingsProcessorBase extends SettingsProcessorBase {

	function init() {

		parent::init();

		$pop_module_settingsprocessor_manager = SettingsProcessorManager_Factory::get_instance();
		$pop_module_settingsprocessor_manager->set_default($this);
	}
}