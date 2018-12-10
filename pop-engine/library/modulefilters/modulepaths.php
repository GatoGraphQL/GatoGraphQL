<?php

define ('POP_MODULEFILTER_MODULEPATHS', 'modulepaths');

class PoP_ModuleFilter_ModulePaths extends PoP_ModuleFilterBase {

	protected $paths, $propagation_unsettled_paths;
	protected $backlog_unsettled_paths;

	function __construct() {

		parent::__construct();

		$this->paths = PoP_ModuleManager_Vars::get_modulepaths();
		$this->propagation_unsettled_paths = $this->paths;
		$this->backlog_unsettled_paths = array();
	}

	function get_name() {

		return POP_MODULEFILTER_MODULEPATHS;
	}

	function exclude_module($module, &$atts) {

		// The module is included for rendering, if either there is no path, or if there is, if it's the last module
		// on the path or any module thereafter
		if (!$this->propagation_unsettled_paths) {

			return false;
		}

		// Check if this module is the last item of any modulepath
		foreach ($this->propagation_unsettled_paths as $unsettled_path) {
			
			if (count($unsettled_path) == 1 && $unsettled_path[0] == $module) {

				return false;
			}
		}

		return true;
	}

	function remove_excluded_submodules($module, $submodules) {

		// If there are no remaining path left, then everything goes in
		if (!$this->propagation_unsettled_paths) {

			return $submodules;
		}

		// $module_unsettled_path: Start only from the specified module. It is passed under URL param "modulepaths", and it's the list of module paths
		// starting from the entry, and joined by ".", like this: modulepaths[]=toplevel.pagesection-top.frame-top.block-notifications-scroll-list
		// This way, the component can interact with itself to fetch or post data, etc
		$matching_submodules = array();
		foreach ($this->propagation_unsettled_paths as $unsettled_path) {

			// Validate that the current module is at the head of the path
			// This validation will work for the entry module only, since the array_intersect below will guarantee that only the path modules are returned
			$unsettled_path_module = $unsettled_path[0];
			if (count($unsettled_path) == 1) {

				// We reached the end of the unsettled path => from now on, all modules must be included
				if ($unsettled_path_module == $module) {

					return $submodules;
				}
			}
			else {

				// Then, check that the following element in the unsettled_path, which is the submodule, is on the submodules
				$unsettled_path_submodule = $unsettled_path[1];
				if ($unsettled_path_module == $module && in_array($unsettled_path_submodule, $submodules) && !in_array($unsettled_path_submodule, $matching_submodules)) {

					$matching_submodules[] = $unsettled_path_submodule;
				}
			}
		}

		return $matching_submodules;
	}	

	/**
	* The `prepare` function advances the modulepath one level down, when interating into the submodules, and then calling `restore` the value goes one level up again
	*/
	function prepare_for_propagation($module) {

		if ($this->paths) {

			// Save the current propagation_unsettled_paths, to restore it later on
			$this->backlog_unsettled_paths[$this->get_backlog_entry()] = $this->propagation_unsettled_paths;

			$matching_unsettled_paths = array();
			foreach ($this->propagation_unsettled_paths as $unsettled_path) {

				$module_unsettled_path = $unsettled_path[0];
				if ($module_unsettled_path == $module) {

					array_shift($unsettled_path);
					// If there are still elements, then add it to the list
					if ($unsettled_path) {
						$matching_unsettled_paths[] = $unsettled_path;
					}
				}
			}
			$this->propagation_unsettled_paths = $matching_unsettled_paths;
		}
	}
	function restore_from_propagation($module) {

		// Restore the previous propagation_unsettled_paths
		if ($this->paths) {

			$backlog_entry = $this->get_backlog_entry();
			$this->propagation_unsettled_paths = $this->backlog_unsettled_paths[$backlog_entry];
			unset($this->backlog_unsettled_paths[$backlog_entry]);
		}
	}
	protected function get_backlog_entry() {

		$module_path_manager = PoP_ModulePathManager_Factory::get_instance();
		return json_encode($module_path_manager->get_propagation_current_path());
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_ModuleFilter_ModulePaths();