<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class Wassup_AAL_PoPProcessors_Template_SettingsProcessor extends GD_Template_SettingsProcessorBase {

	function get_checkpoints($hierarchy) {

		$ret = array();

		if ($hierarchy == GD_SETTINGS_HIERARCHY_PAGE) {

			// Cannot cache, since the notifications will always check if we are logged in or not when retrieving the data
			return array(
				POP_AAL_PAGE_NOTIFICATIONS => Wassup_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_CHECKPOINT_NEVERCACHE),
			);
		}
	
		return parent::get_checkpoints($hierarchy);
	}

	function get_page_blocks($hierarchy/*, $include_common = true*/) {

		$ret = array();
		$default_format_notifications = PoPTheme_Wassup_Utils::get_defaultformat_by_screen(POP_SCREEN_NOTIFICATIONS);

		if ($hierarchy == GD_SETTINGS_HIERARCHY_PAGE) {

			$pageblocks_details = array(
				POP_AAL_PAGE_NOTIFICATIONS  => GD_TEMPLATE_BLOCK_NOTIFICATIONS_SCROLL_DETAILS,
			);
			foreach ($pageblocks_details as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_DETAILS] = $block;

				if ($default_format_notifications == GD_TEMPLATEFORMAT_DETAILS) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_list = array(
				POP_AAL_PAGE_NOTIFICATIONS  => GD_TEMPLATE_BLOCK_NOTIFICATIONS_SCROLL_LIST,
			);
			foreach ($pageblocks_list as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_LIST] = $block;
				
				if ($default_format_notifications == GD_TEMPLATEFORMAT_LIST) {
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
new Wassup_AAL_PoPProcessors_Template_SettingsProcessor();
