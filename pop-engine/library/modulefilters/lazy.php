<?php

define ('POP_MODULEFILTER_LAZY', 'lazy');

class PoP_ModuleFilter_Lazy extends PoP_ModuleFilterBase {

	function get_name() {

		return POP_MODULEFILTER_LAZY;
	}

	function exclude_module($module, &$props) {

		// Exclude if it is not lazy
		global $pop_module_processor_manager;
		$processor = $pop_module_processor_manager->get_processor($module);
		return !$processor->is_lazyload($module, $props);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_ModuleFilter_Lazy();