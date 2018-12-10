<?php

define ('POP_MODULEFILTER_HEADMODULE', 'headmodule');

class PoP_ModuleFilter_HeadModule extends PoP_ModuleFilterBase {

	function get_name() {

		return POP_MODULEFILTER_HEADMODULE;
	}

	function exclude_module($module, &$atts) {

		$vars = PoP_ModuleManager_Vars::get_vars();
		return $vars['headmodule'] != $module;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_ModuleFilter_HeadModule();