<?php
namespace PoP\Engine;

trait DataloadModuleProcessorBaseTrait {

	//-------------------------------------------------
	// PUBLIC Functions
	//-------------------------------------------------

	function get_dataloading_module($module) {

		return $module;
	}

	function get_format($module) {

		return null;
	}

	function get_filter_module($module) {

		return null;
	}

	function get_filter($module) {

		if ($filter_module = $this->get_filter_module($module)) {

			$moduleprocessor_manager = ModuleProcessor_Manager_Factory::get_instance();
			return $moduleprocessor_manager->get_processor($filter_module)->get_filter($filter_module);
		}

		return parent::get_filter($module);
	}
	
	//-------------------------------------------------
	// PUBLIC Overriding Functions
	//-------------------------------------------------

	function get_modules($module) {
	
		$ret = parent::get_modules($module);

		if ($filter = $this->get_filter_module($module)) {

			$ret[] = $filter;
		}

		if ($inners = $this->get_inner_modules($module)) {
			
			$ret = array_merge(
				$ret,
				$inners
			);
		}
				
		return $ret;
	}

	protected function get_inner_modules($module) {

		return array();
	}

	//-------------------------------------------------
	// PROTECTED Functions
	//-------------------------------------------------

	function meta_init_props($module, &$props) {

		/**---------------------------------------------------------------------------------------------------------------
		 * Allow to add more stuff
		 * ---------------------------------------------------------------------------------------------------------------*/
		do_action(
			'\PoP\Engine\DataloadModuleProcessorBaseTrait:init_model_props',
			array(&$props),
			$module,
			$this
		);
	}

	function get_model_props_for_descendant_datasetmodules($module, &$props) {

		$ret = parent::get_model_props_for_descendant_datasetmodules($module, $props);

		if ($filter_module = $this->get_filter_module($module)) {
			
			$moduleprocessor_manager = ModuleProcessor_Manager_Factory::get_instance();
			$ret['filter-module'] = $filter_module;
			$ret['filter'] = $moduleprocessor_manager->get_processor($filter_module)->get_filter($filter_module);
		}

		return $ret;
	}

	function init_model_props($module, &$props) {

		$this->meta_init_props($module, $props);
		parent::init_model_props($module, $props);
	}

	//-------------------------------------------------
	// PROTECTED Functions
	//-------------------------------------------------

	function get_dataloader($module) {

		return GD_DATALOADER_NIL;
	}
}
