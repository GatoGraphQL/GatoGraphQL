<?php
namespace PoP\Engine;

class ModulePathManager {

	protected $propagation_current_path;

	function __construct() {

		ModulePathManager_Factory::set_instance($this);

		$this->propagation_current_path = array();
	}

	function get_propagation_current_path() {

		return $this->propagation_current_path;
	}

	function set_propagation_current_path($propagation_current_path = null) {

		$this->propagation_current_path = $propagation_current_path;
	}

	/**
	* The `prepare` function advances the modulepath one level down, when interating into the submodules, and then calling `restore` the value goes one level up again
	*/
	function prepare_for_propagation($module) {

		// Execute steps in this order
		// Step 1: call on the filter
		$modulefilter_manager = ModuleFilterManager_Factory::get_instance();
		$modulefilter_manager->prepare_for_propagation($module);

		// Step 2: add the module to the path
		// Prepare for the submodule, going one level down, and adding it to the current path
		// We add $module instead of the first element from $this->propagation_unsettled_paths, so that calculating $this->propagation_current_path works also when not doing ?modulepaths=...
		$this->propagation_current_path[] = $module;
	}
	function restore_from_propagation($module) {

		// Execute steps in this order
		// Step 1: add the module to the path
		array_pop($this->propagation_current_path);

		// Step 2: call on the filter
		$modulefilter_manager = ModuleFilterManager_Factory::get_instance();
		$modulefilter_manager->restore_from_propagation($module);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new ModulePathManager();