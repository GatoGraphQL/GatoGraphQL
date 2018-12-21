<?php
namespace PoP\Engine;

class ModuleFilterManager {

	protected $selected_filter_name, $filters;

	// From the moment in which a module is not excluded, every module from then on must also be included
	protected $not_excluded_ancestor_module, $not_excluded_module_sets, $not_excluded_module_sets_as_string;

	// When targeting modules in pop-engine.php (eg: when doing ->get_dbobjectids()) those modules are already and always included, so no need to check for their ancestors or anything
	protected $never_exclude = false;

	function __construct() {

		ModuleFilterManager_Factory::set_instance($this);

		$this->filters = array();
		add_action('init', array($this, 'init'));
	}

	function get_selected_filter_name() {

		if ($selected = $_REQUEST[GD_URLPARAM_MODULEFILTER]) {

			// Validate that the selected filter exists
			if (in_array($selected, array_keys($this->filters))) {

				return $selected;
			}
		}

		return null;
	}

	function init() {

		if ($selected = $this->get_selected_filter_name()) {

			$this->selected_filter_name = $selected;

			// Initialize only if we are intending to filter modules. This way, passing modulefilter=somewrongpath will return an empty array, meaning to not render anything
			$this->not_excluded_module_sets = $this->not_excluded_module_sets_as_string = array();
		}
	}

	function get_not_excluded_module_sets() {

		// It shall be used for requestmeta.rendermodules, to know from which modules the client must start rendering
		return $this->not_excluded_module_sets;
	}

	function add($filter) {

		$this->filters[$filter->get_name()] = $filter;
	}

	protected function ancestor_module_not_excluded() {

		return !is_null($this->not_excluded_ancestor_module);
	}

	function never_exclude($never_exclude) {

		$this->never_exclude = $never_exclude;
	}

	function exclude_module($module, &$props) {

		if ($this->selected_filter_name) {

			if ($this->never_exclude) {

				return false;
			}

			if ($this->ancestor_module_not_excluded()) {

				return false;
			}

			return $this->filters[$this->selected_filter_name]->exclude_module($module, $props);
		}

		return false;
	}

	function remove_excluded_submodules($module, $submodules) {

		if ($this->selected_filter_name) {
			
			if ($this->never_exclude) {

				return $submodules;
			}

			return $this->filters[$this->selected_filter_name]->remove_excluded_submodules($module, $submodules);
		}

		return $submodules;
	}	

	/**
	* The `prepare` function advances the modulepath one level down, when interating into the submodules, and then calling `restore` the value goes one level up again
	*/
	function prepare_for_propagation($module) {

		if ($this->selected_filter_name) {

			if (!$this->never_exclude && is_null($this->not_excluded_ancestor_module) && $this->exclude_module($module, $props) === false) {

				// Set the current module as the one which is not excluded.
				$module_path_manager = ModulePathManager_Factory::get_instance();
				$module_propagation_current_path = $module_path_manager->get_propagation_current_path();
				$module_propagation_current_path[] = $module;

				$this->not_excluded_ancestor_module = ModulePathManager_Utils::stringify_module_path($module_propagation_current_path);

				// Add it to the list of not-excluded modules
				if (!in_array($this->not_excluded_ancestor_module, $this->not_excluded_module_sets_as_string)) {

					$this->not_excluded_module_sets_as_string[] = $this->not_excluded_ancestor_module;
					$this->not_excluded_module_sets[] = $module_propagation_current_path;
				}
			}

			$this->filters[$this->selected_filter_name]->prepare_for_propagation($module);
		}
	}
	function restore_from_propagation($module) {

		if ($this->selected_filter_name) {

			if (!$this->never_exclude && !is_null($this->not_excluded_ancestor_module) && $this->exclude_module($module, $props) === false) {

				$module_path_manager = ModulePathManager_Factory::get_instance();
				$module_propagation_current_path = $module_path_manager->get_propagation_current_path();
				$module_propagation_current_path[] = $module;

				// If the current module was set as the one not excluded, then reset it
				if ($this->not_excluded_ancestor_module == ModulePathManager_Utils::stringify_module_path($module_propagation_current_path)) {

					$this->not_excluded_ancestor_module = null;
				}
			}

			$this->filters[$this->selected_filter_name]->restore_from_propagation($module);
		}
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new ModuleFilterManager();