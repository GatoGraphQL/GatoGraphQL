<?php

class PoP_DefinitionUtils {

	public static function get_manager() {

		global $pop_definitionmanager;
		return $pop_definitionmanager;
	}

	/**
	 * Function used to create a definition for a module. Needed for reducing the filesize of the html generated for PROD
	 * Instead of using the name of the $module, we use a unique number in base 36, so the name will occupy much lower size
	 * Comment Leo 27/09/2017: Changed from $module to only $id so that it can also be used with ResourceLoaders
	 */
	public static function get_module_definition($id, $group = null) {

		return self::get_manager()->get_module_definition($id, $group);
	}
}