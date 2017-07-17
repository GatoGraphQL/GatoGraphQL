<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_Wassup_AutomatedEmails_AAL_Template_SettingsProcessor extends GD_Template_SettingsProcessorBase {

	function get_checkpoints($hierarchy) {

		$ret = array();

		if ($hierarchy == GD_SETTINGS_HIERARCHY_PAGE) {

			return array(
				POPTHEME_WASSUP_AUTOMATEDEMAILS_PAGE_LATESTNOTIFICATIONS_DAILY => PoPTheme_Wassup_PoPSystem_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_CHECKPOINT_SYSTEMACCESSVALID),
			);
		}
	
		return parent::get_checkpoints($hierarchy);
	}

	function get_page_blocks($hierarchy, $include_common = true) {

		$ret = array();

		if ($hierarchy == GD_SETTINGS_HIERARCHY_PAGE) {

			$default_format_section = PoPTheme_Wassup_Utils::get_defaultformat_by_screen(POP_AUTOMATEDEMAIL_SCREEN_NOTIFICATIONS);

			$pageblocks_details = array(
				POPTHEME_WASSUP_AUTOMATEDEMAILS_PAGE_LATESTNOTIFICATIONS_DAILY => GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_DETAILS,
			);
			foreach ($pageblocks_details as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_DETAILS] = $block;

				if ($default_format_section == GD_TEMPLATEFORMAT_DETAILS) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_list = array(
				POPTHEME_WASSUP_AUTOMATEDEMAILS_PAGE_LATESTNOTIFICATIONS_DAILY => GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_NOTIFICATIONS_SCROLL_LIST,
			);
			foreach ($pageblocks_list as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_LIST] = $block;

				if ($default_format_section == GD_TEMPLATEFORMAT_LIST) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
		}

		return $ret;	
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_AutomatedEmails_AAL_Template_SettingsProcessor();
