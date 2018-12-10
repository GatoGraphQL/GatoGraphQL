<?php

class PoPEngine_Module_DefaultSettingsProcessorBase extends PoPEngine_Module_SettingsProcessorBase {

	function init() {

		parent::init();

		$pop_module_settingsprocessor_manager = PoPEngine_Module_SettingsProcessorManager_Factory::get_instance();
		$pop_module_settingsprocessor_manager->set_default($this);
	}
}