<?php

trait PoP_ModulePathProcessorTrait {

	//-------------------------------------------------
	// PUBLIC Functions
	//-------------------------------------------------
	function get_settings_id($module) {

		return $module;
	}

	function get_descendant_modules($module) {

		return array();
	}

	//-------------------------------------------------
	// PROTECTED Functions
	//-------------------------------------------------

	protected function get_module_processor($module) {

		global $pop_module_processor_manager;
		return $pop_module_processor_manager->get_processor($module);
	}

	//-------------------------------------------------
	// TEMPLATE Functions
	//-------------------------------------------------

	protected function has_no_dataloader($module) {

		return is_null($this->get_module_processor($module)->get_dataloader($module));
	}

	// $use_settings_id_as_key: For response structures (eg: configuration, feedback, etc) must be true
	// for internal structures (eg: $props, $data_properties) no need
	protected function execute_on_self_and_propagate_to_datasetmodules($eval_self_fn, $propagate_fn, $module, &$props, $data_properties, $checkpoint_validation, $executed, $dbobjectids) {

		$ret = array();
		$key = $this->get_settings_id($module);

		// If modulepaths is provided, and we haven't reached the destination module yet, then do not execute the function at this level
		$modulefilter_manager = PoP_ModuleFilterManager_Factory::get_instance();
		if (!$modulefilter_manager->exclude_module($module, $props)) {
			
			if ($module_ret = $this->$eval_self_fn($module, $props, $data_properties, $checkpoint_validation, $executed, $dbobjectids)) {
		
				$ret[$key] = $module_ret;
			}
		}
				
		// Stop iterating when the submodule starts a new cycle of loading data (i.e. if it has a dataloader)
		$submodules = array_filter($this->get_descendant_modules($module), array($this, 'has_no_dataloader'));
		$submodules = $modulefilter_manager->remove_excluded_submodules($module, $submodules);

		// This function must be called always, to register matching modules into requestmeta.filtermodules even when the module has no submodules
		$module_path_manager = PoP_ModulePathManager_Factory::get_instance();
		$module_path_manager->prepare_for_propagation($module);
		$submodules_ret = array();
		foreach ($submodules as $submodule) {
		
			$submodules_ret = array_merge(
				$submodules_ret,
				$this->get_module_processor($submodule)->$propagate_fn($submodule, $props[$module][POP_PROPS_MODULES], $data_properties, $checkpoint_validation, $executed, $dbobjectids)
			);
		}
		if ($submodules_ret) {

			$ret[$key][GD_JS_MODULES] = $submodules_ret;
		}
		$module_path_manager->restore_from_propagation($module);
		
		return $ret;
	}

	protected function execute_on_self_and_merge_with_datasetmodules($eval_self_fn, $propagate_fn, $module, $props, $data_properties, $checkpoint_validation, $executed, $dbobjectids) {

		// If modulepaths is provided, and we haven't reached the destination module yet, then do not execute the function at this level
		$modulefilter_manager = PoP_ModuleFilterManager_Factory::get_instance();
		if (!$modulefilter_manager->exclude_module($module, $props)) {
			
			$ret = $this->$eval_self_fn($module, $props, $data_properties, $checkpoint_validation, $executed, $dbobjectids);
		}
		else {

			$ret = array();
		}
		
		// Stop iterating when the submodule starts a new cycle of loading data (i.e. if it has a dataloader)
		$submodules = array_filter($this->get_descendant_modules($module), array($this, 'has_no_dataloader'));
		$submodules = $modulefilter_manager->remove_excluded_submodules($module, $submodules);

		// This function must be called always, to register matching modules into requestmeta.filtermodules even when the module has no submodules
		$module_path_manager = PoP_ModulePathManager_Factory::get_instance();
		$module_path_manager->prepare_for_propagation($module);
		foreach ($submodules as $submodule) {
		
			$ret = array_merge_recursive(
				$ret,
				$this->get_module_processor($submodule)->$propagate_fn($submodule, $props[$module][POP_PROPS_MODULES], $data_properties, $checkpoint_validation, $executed, $dbobjectids)
			);
		}
		$module_path_manager->restore_from_propagation($module);
		
		return $ret;
	}

	// $use_settings_id_as_key: For response structures (eg: configuration, feedback, etc) must be true
	// for internal structures (eg: $props, $data_properties) no need
	protected function execute_on_self_and_propagate_to_modules($eval_self_fn, $propagate_fn, $module, &$props, $use_settings_id_as_key = true) {

		$ret = array();
		$key = $use_settings_id_as_key ? $this->get_settings_id($module) : $module;
		
		// If modulepaths is provided, and we haven't reached the destination module yet, then do not execute the function at this level
		$modulefilter_manager = PoP_ModuleFilterManager_Factory::get_instance();
		if (!$modulefilter_manager->exclude_module($module, $props)) {
			
			if ($module_ret = $this->$eval_self_fn($module, $props)) {
		
				$ret[$key] = $module_ret;
			}
		}

		$submodules = $this->get_descendant_modules($module);
		$submodules = $modulefilter_manager->remove_excluded_submodules($module, $submodules);

		// This function must be called always, to register matching modules into requestmeta.filtermodules even when the module has no submodules
		$module_path_manager = PoP_ModulePathManager_Factory::get_instance();
		$module_path_manager->prepare_for_propagation($module);
		$submodules_ret = array();		
		foreach ($submodules as $submodule) {
		
			$submodules_ret = array_merge(
				$submodules_ret,
				$this->get_module_processor($submodule)->$propagate_fn($submodule, $props[$module][POP_PROPS_MODULES])
			);
		}
		if ($submodules_ret) {

			$ret[$key][GD_JS_MODULES] = $submodules_ret;
		}
		$module_path_manager->restore_from_propagation($module);	
		
		return $ret;
	}

	protected function execute_on_self_and_merge_with_modules($eval_self_fn, $propagate_fn, $module, &$props, $recursive = true) {

		// If modulepaths is provided, and we haven't reached the destination module yet, then do not execute the function at this level
		$modulefilter_manager = PoP_ModuleFilterManager_Factory::get_instance();
		if (!$modulefilter_manager->exclude_module($module, $props)) {
			
			$ret = $this->$eval_self_fn($module, $props);
		}
		else {

			$ret = array();
		}
		
		$submodules = $this->get_descendant_modules($module);
		$submodules = $modulefilter_manager->remove_excluded_submodules($module, $submodules);

		// This function must be called always, to register matching modules into requestmeta.filtermodules even when the module has no submodules
		$module_path_manager = PoP_ModulePathManager_Factory::get_instance();
		$module_path_manager->prepare_for_propagation($module);
		foreach ($submodules as $submodule) {
		
			$submodule_ret = $this->get_module_processor($submodule)->$propagate_fn($submodule, $props[$module][POP_PROPS_MODULES], $recursive);
			$ret = $recursive ? 
				array_merge_recursive(
					$ret,
					$submodule_ret
				) :
				array_unique(array_values(array_merge(
					$ret,
					$submodule_ret
				)));
		}
		$module_path_manager->restore_from_propagation($module);	
		
		return $ret;
	}
}