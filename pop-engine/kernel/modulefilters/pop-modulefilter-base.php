<?php
namespace PoP\Engine;

abstract class ModuleFilterBase {

	function __construct() {

		ModuleFilterManager_Factory::get_instance()->add($this);
	}

	abstract function get_name();

	function exclude_module($module, &$props) {

		return false;
	}

	function remove_excluded_submodules($module, $submodules) {

		return $submodules;
	}	

	function prepare_for_propagation($module) {

	}

	function restore_from_propagation($module) {

	}
}
