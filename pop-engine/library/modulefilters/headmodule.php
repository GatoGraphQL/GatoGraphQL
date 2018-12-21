<?php
namespace PoP\Engine\Impl;

define ('POP_MODULEFILTER_HEADMODULE', 'headmodule');

class ModuleFilter_HeadModule extends \PoP\Engine\ModuleFilterBase {

	function get_name() {

		return POP_MODULEFILTER_HEADMODULE;
	}

	function exclude_module($module, &$props) {

		$vars = \PoP\Engine\Engine_Vars::get_vars();
		return $vars['headmodule'] != $module;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new ModuleFilter_HeadModule();