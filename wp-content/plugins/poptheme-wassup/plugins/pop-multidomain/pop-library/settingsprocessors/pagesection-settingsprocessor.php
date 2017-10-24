<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_Wassup_MultiDomain_PageSectionSettingsProcessor extends Wassup_PageSectionSettingsProcessorBase {

	function add_page_blockunits(&$ret, $template_id) {

		global $gd_template_settingsmanager;

		$vars = GD_TemplateManager_Utils::get_vars();
		$target = $vars['target'];

		$page_id = GD_TemplateManager_Utils::get_hierarchy_page_id();
		
		// Comment Leo 27/07/2016: function add_page_blockunits is invoked also for error pages, which produce no $page_id
		// Because POP_MULTIDOMAIN_PAGE_EXTERNAL has no value, the switch actually matches the "false" value from POP_MULTIDOMAIN_PAGE_EXTERNAL with the "null" value from $page_id
		// and it enters the condition. To avoid that problem, make sure there is a $page_id
		if (!$page_id) return;

		$blockgroups = array();

		switch ($page_id) {

			/**-------------------------------------------
			 * OPERATIONAL
			 *-------------------------------------------*/
			case POP_MULTIDOMAIN_PAGE_LOADERS_INITIALIZEDOMAIN:

				if ($template_id == GD_TEMPLATE_PAGESECTION_OPERATIONAL) {
			
					$blockgroups[] = $gd_template_settingsmanager->get_page_blockgroup($page_id);
					break;
				}
				break;
		}

		GD_TemplateManager_Utils::add_blockgroups($ret, $blockgroups, GD_TEMPLATEBLOCKSETTINGS_BLOCKGROUP);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_MultiDomain_PageSectionSettingsProcessor();
