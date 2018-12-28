<?php
namespace PoP\Engine;

abstract class ModuleDecoratorProcessorBase {

	use ModulePathProcessorTrait;

	//-------------------------------------------------
	// PROTECTED Functions
	//-------------------------------------------------

	protected function get_module_processor($module) {

		return $this->get_module_processordecorator($module);
	}

	protected function get_module_processordecorator($module) {

		$processor = $this->get_decoratedmodule_processor($module);
		return $this->get_moduledecoratorprocessor_manager()->get_processordecorator($processor);
	}

	protected function get_decoratedmodule_processor($module) {

		$moduleprocessor_manager = ModuleProcessor_Manager_Factory::get_instance();
		return $moduleprocessor_manager->get_processor($module);
	}

	protected function get_moduledecoratorprocessor_manager() {

		return null;
	}

	//-------------------------------------------------
	// PUBLIC Functions
	//-------------------------------------------------
	function get_settings_id($module) {

		$moduleprocessor_manager = ModuleProcessor_Manager_Factory::get_instance();
		$processor = $moduleprocessor_manager->get_processor($module);
		return $processor->get_settings_id($module);
	}

	function get_descendant_modules($module) {

		$moduleprocessor_manager = ModuleProcessor_Manager_Factory::get_instance();
		$processor = $moduleprocessor_manager->get_processor($module);
		return $processor->get_descendant_modules($module);
	}
}