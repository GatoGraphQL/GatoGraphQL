<?php

trait PoP_Processor_DataloadsBaseTrait {

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

			global $pop_module_processor_manager;
			return $pop_module_processor_manager->get_processor($filter_module)->get_filter($filter_module);
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

	function meta_init_atts($module, &$atts) {

		/**---------------------------------------------------------------------------------------------------------------
		 * Allow to add more stuff
		 * ---------------------------------------------------------------------------------------------------------------*/
		do_action(
			'PoP_Processor_DataloadsBaseTrait:init_model_atts',
			array(&$atts),
			$module,
			$this
		);
	}

	function get_model_atts_for_descendant_datasetmodules($module, &$atts) {

		$ret = parent::get_model_atts_for_descendant_datasetmodules($module, $atts);

		if ($filter_module = $this->get_filter_module($module)) {
			
			global $pop_module_processor_manager;
			$ret['filter-module'] = $filter_module;
			$ret['filter'] = $pop_module_processor_manager->get_processor($filter_module)->get_filter($filter_module);
		}

		return $ret;
	}

	function init_model_atts($module, &$atts) {

		$this->meta_init_atts($module, $atts);
		parent::init_model_atts($module, $atts);
	}

	//-------------------------------------------------
	// PROTECTED Functions
	//-------------------------------------------------

	function get_dataloader($module) {

		return GD_DATALOADER_NIL;
	}
}
