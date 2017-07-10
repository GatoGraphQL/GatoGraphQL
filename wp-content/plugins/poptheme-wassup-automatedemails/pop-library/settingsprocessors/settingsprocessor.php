<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_Wassup_AutomatedEmails_Template_SettingsProcessor extends GD_Template_SettingsProcessorBase {

	function get_checkpoints($hierarchy) {

		$ret = array();

		if ($hierarchy == GD_SETTINGS_HIERARCHY_PAGE) {

			return array(
				POPTHEME_WASSUP_AUTOMATEDEMAILS_PAGE_LATESTPOSTS_WEEKLY => PoPTheme_Wassup_PoPSystem_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_CHECKPOINT_LOGGEDIN_SYSTEMACCESSVALID),
			);
		}
	
		return parent::get_checkpoints($hierarchy);
	}

	function get_page_blocks($hierarchy, $include_common = true) {

		$ret = array();

		if ($hierarchy == GD_SETTINGS_HIERARCHY_PAGE) {

			$default_format_section = PoPTheme_Wassup_Utils::get_defaultformat_by_screen(POP_AUTOMATEDEMAIL_SCREEN_SECTION);

			$pageblocks_details = array(
				POPTHEME_WASSUP_AUTOMATEDEMAILS_PAGE_LATESTPOSTS_WEEKLY => GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_DETAILS,
			);
			foreach ($pageblocks_details as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_DETAILS] = $block;

				if ($default_format_section == GD_TEMPLATEFORMAT_DETAILS) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_simpleview = array(
				POPTHEME_WASSUP_AUTOMATEDEMAILS_PAGE_LATESTPOSTS_WEEKLY => GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_SIMPLEVIEW,
			);
			foreach ($pageblocks_simpleview as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_SIMPLEVIEW] = $block;

				if ($default_format_section == GD_TEMPLATEFORMAT_SIMPLEVIEW) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_fullview = array(
				POPTHEME_WASSUP_AUTOMATEDEMAILS_PAGE_LATESTPOSTS_WEEKLY => GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_FULLVIEW,
			);
			foreach ($pageblocks_fullview as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_FULLVIEW] = $block;

				if ($default_format_section == GD_TEMPLATEFORMAT_FULLVIEW) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_thumbnail = array(
				POPTHEME_WASSUP_AUTOMATEDEMAILS_PAGE_LATESTPOSTS_WEEKLY => GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_THUMBNAIL,
			);
			foreach ($pageblocks_thumbnail as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_THUMBNAIL] = $block;

				if ($default_format_section == GD_TEMPLATEFORMAT_THUMBNAIL) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_list = array(
				POPTHEME_WASSUP_AUTOMATEDEMAILS_PAGE_LATESTPOSTS_WEEKLY => GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_LATESTPOSTS_SCROLL_LIST,
			);
			foreach ($pageblocks_list as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_LIST] = $block;

				if ($default_format_section == GD_TEMPLATEFORMAT_LIST) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
		}

		// // Allow Events Manager to inject the settings for the Map
		// $ret = apply_filters('PoPTheme_Wassup_AutomatedEmails_Template_SettingsProcessor:page_blocks', $ret, $hierarchy, $include_common);

		return $ret;	
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_AutomatedEmails_Template_SettingsProcessor();
