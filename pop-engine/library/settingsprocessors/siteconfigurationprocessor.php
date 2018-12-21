<?php
namespace PoP\Engine\Settings\Impl;

class PageModuleSiteConfigurationProcessor extends \PoP\Engine\Settings\SiteConfigurationProcessorBase {

	function get_entry_module() {

		$pop_module_pagemoduleprocessor_manager = \PoP\Engine\PageModuleProcessorManager_Factory::get_instance();
		return $pop_module_pagemoduleprocessor_manager->get_page_module_by_most_allmatching_vars_properties(POP_PAGEMODULEGROUP_ENTRYMODULE);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PageModuleSiteConfigurationProcessor();
