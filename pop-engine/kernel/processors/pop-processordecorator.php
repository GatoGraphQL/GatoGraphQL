<?php

abstract class PoP_ProcessorDecoratorBase {

	use PoP_ModulePathProcessorTrait;

	//-------------------------------------------------
	// PROTECTED Functions
	//-------------------------------------------------

	protected function get_module_processor($module) {

		return $this->get_module_processordecorator($module);
	}

	protected function get_module_processordecorator($module) {

		$processor = $this->get_decoratedmodule_processor($module);
		return $this->get_processordecorator_manager()->get_processordecorator($processor);
	}

	protected function get_decoratedmodule_processor($module) {

		global $pop_module_processor_manager;
		return $pop_module_processor_manager->get_processor($module);
	}

	protected function get_processordecorator_manager() {

		return null;
	}

	//-------------------------------------------------
	// PUBLIC Functions
	//-------------------------------------------------
	function get_settings_id($module) {

		global $pop_module_processor_manager;
		$processor = $pop_module_processor_manager->get_processor($module);
		return $processor->get_settings_id($module);
	}

	function get_descendant_modules($module) {

		global $pop_module_processor_manager;
		$processor = $pop_module_processor_manager->get_processor($module);
		return $processor->get_descendant_modules($module);
	}
}