<?php
namespace PoP\Engine\Settings;

abstract class DefaultSettingsProcessorBase extends SettingsProcessorBase {

	function init() {

		parent::init();

		SettingsProcessorManager_Factory::get_instance()->set_default($this);
	}
}