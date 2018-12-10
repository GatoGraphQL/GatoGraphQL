<?php

define ('POP_MODULEFILTER_MAINCONTENTMODULE', 'maincontentmodule');

class PoP_ModuleFilter_MainContentModule extends PoP_ModuleFilterBase {

	function get_name() {

		return POP_MODULEFILTER_MAINCONTENTMODULE;
	}

	function exclude_module($module, &$atts) {

		$vars = PoP_ModuleManager_Vars::get_vars();
		return $vars['maincontentmodule'] != $module;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_ModuleFilter_MainContentModule();