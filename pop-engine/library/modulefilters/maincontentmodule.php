<?php
namespace PoP\Engine\Impl;

define ('POP_MODULEFILTER_MAINCONTENTMODULE', 'maincontentmodule');

class ModuleFilter_MainContentModule extends \PoP\Engine\ModuleFilterBase {

	function get_name() {

		return POP_MODULEFILTER_MAINCONTENTMODULE;
	}

	function exclude_module($module, &$props) {

		$vars = \PoP\Engine\Engine_Vars::get_vars();
		return $vars['maincontentmodule'] != $module;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new ModuleFilter_MainContentModule();