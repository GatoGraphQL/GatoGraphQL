<?php

class PoP_ModulePathManager_Utils {

	public static function get_stringified_module_propagation_current_path($module) {

		$module_path_manager = PoP_ModulePathManager_Factory::get_instance();
		$module_propagation_current_path = $module_path_manager->get_propagation_current_path();
		$module_propagation_current_path[] = $module;
		return self::stringify_module_path($module_propagation_current_path);
	}

	public static function stringify_module_path($modulepath) {

		return implode(POP_CONSTANT_MODULESTARTPATH_SEPARATOR, $modulepath);
	}

	public static function recast_module_path($modulepath_as_string) {

		return explode(POP_CONSTANT_MODULESTARTPATH_SEPARATOR, $modulepath_as_string);
	}
}