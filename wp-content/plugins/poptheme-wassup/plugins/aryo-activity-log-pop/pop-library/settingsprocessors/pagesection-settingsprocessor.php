<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class AAL_PageSectionSettingsProcessor extends Wassup_PageSectionSettingsProcessorBase {

	function add_page_blockunits(&$ret, $template_id) {

		global $gd_template_settingsmanager;

		$vars = GD_TemplateManager_Utils::get_vars();
		$page_id = GD_TemplateManager_Utils::get_hierarchy_page_id();
		if (!$page_id) return;

		$blockgroups = array();

		switch ($page_id) {

			/**-------------------------------------------
			 * OPERATIONAL
			 *-------------------------------------------*/
			case POP_AAL_PAGE_NOTIFICATIONS_MARKALLASREAD:
			case POP_AAL_PAGE_NOTIFICATIONS_MARKASREAD:
			case POP_AAL_PAGE_NOTIFICATIONS_MARKASUNREAD:

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
new AAL_PageSectionSettingsProcessor();
