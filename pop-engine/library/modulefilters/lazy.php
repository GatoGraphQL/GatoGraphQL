<?php
namespace PoP\Engine\Impl;

define ('POP_MODULEFILTER_LAZY', 'lazy');

class ModuleFilter_Lazy extends \PoP\Engine\ModuleFilterBase {

	function get_name() {

		return POP_MODULEFILTER_LAZY;
	}

	function exclude_module($module, &$props) {

		// Exclude if it is not lazy
		$moduleprocessor_manager = ModuleProcessor_Manager_Factory::get_instance();
		$processor = $moduleprocessor_manager->get_processor($module);
		return !$processor->is_lazyload($module, $props);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new ModuleFilter_Lazy();