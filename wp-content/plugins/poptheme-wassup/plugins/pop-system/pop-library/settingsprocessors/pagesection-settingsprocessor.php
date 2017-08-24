<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_Wassup_PoPSystem_PageSectionSettingsProcessor extends Wassup_PageSectionSettingsProcessorBase {

	function add_page_blockunits(&$ret, $template_id) {

		global $gd_template_settingsmanager;

		$page_id = GD_TemplateManager_Utils::get_hierarchy_page_id();
		
		// Comment Leo 27/07/2016: function add_page_blockunits is invoked also for error pages, which produce no $page_id
		// Because POP_MULTIDOMAIN_PAGE_EXTERNAL has no value, the switch actually matches the "false" value from POP_MULTIDOMAIN_PAGE_EXTERNAL with the "null" value from $page_id
		// and it enters the condition. To avoid that problem, make sure there is a $page_id
		if (!$page_id) return;

		$blocks = array();

		switch ($page_id) {

			/*********************************************
			 * System
			 *********************************************/
			case POP_SYSTEM_PAGE_SYSTEM_INSTALL:
			case POP_SYSTEM_PAGE_SYSTEM_BUILD:

				if ($template_id == GD_TEMPLATE_PAGESECTION_OPERATIONAL) {

					$blocks[] = $gd_template_settingsmanager->get_page_action($page_id);
				}
				break;
		}

		GD_TemplateManager_Utils::add_blocks($ret, $blocks, GD_TEMPLATEBLOCKSETTINGS_MAIN);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_PoPSystem_PageSectionSettingsProcessor();
