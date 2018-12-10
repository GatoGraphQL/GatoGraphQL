<?php

class PoPEngine_Module_PageModuleSiteConfigurationProcessor extends PoPEngine_Module_SiteConfigurationProcessorBase {

	function get_entry_module() {

		$pop_module_pagemoduleprocessor_manager = PoPEngine_Module_PageModuleProcessorManager_Factory::get_instance();
		return $pop_module_pagemoduleprocessor_manager->get_page_module_by_most_allmatching_vars_properties(POP_PAGEMODULEGROUP_ENTRYMODULE);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPEngine_Module_PageModuleSiteConfigurationProcessor();
