<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class AAL_Template_SettingsProcessor extends GD_Template_SettingsProcessorBase {

	function silent_document($hierarchy) {

		if ($hierarchy == GD_SETTINGS_HIERARCHY_PAGE) {

			return array(
				POP_AAL_PAGE_NOTIFICATIONS_MARKALLASREAD => true,
				POP_AAL_PAGE_NOTIFICATIONS_MARKASREAD => true,
				POP_AAL_PAGE_NOTIFICATIONS_MARKASUNREAD => true,
			);
		}

		return parent::silent_document($hierarchy);
	}

	function get_checkpoints($hierarchy) {

		$ret = array();

		if ($hierarchy == GD_SETTINGS_HIERARCHY_PAGE) {

			return array(
				POP_AAL_PAGE_NOTIFICATIONS_MARKALLASREAD => Wassup_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_CHECKPOINT_LOGGEDIN_DATAFROMSERVER),
				POP_AAL_PAGE_NOTIFICATIONS_MARKASREAD => Wassup_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_CHECKPOINT_LOGGEDIN_DATAFROMSERVER),
				POP_AAL_PAGE_NOTIFICATIONS_MARKASUNREAD => Wassup_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_CHECKPOINT_LOGGEDIN_DATAFROMSERVER),
			);
		}
	
		return parent::get_checkpoints($hierarchy);
	}

	function get_page_blockgroups($hierarchy, $include_common = true) {

		$ret = array();

		// Common BlockGroups
		if ($include_common) {

			// Default
			$pageblockgroups_allothers = array(

				POP_AAL_PAGE_NOTIFICATIONS_MARKALLASREAD => GD_TEMPLATE_BLOCKGROUP_MARKALLNOTIFICATIONSASREAD,
				POP_AAL_PAGE_NOTIFICATIONS_MARKASREAD => GD_TEMPLATE_BLOCKGROUP_MARKNOTIFICATIONASREAD,
				POP_AAL_PAGE_NOTIFICATIONS_MARKASUNREAD => GD_TEMPLATE_BLOCKGROUP_MARKNOTIFICATIONASUNREAD,
			);
			foreach ($pageblockgroups_allothers as $page => $blockgroup) {
				$ret[$page]['blockgroups']['default'] = $blockgroup;
			}
		}

		return $ret;
	}

	function get_page_blocks($hierarchy, $include_common = true) {

		$ret = array();

		// $include_common: used to tell if we include also the common blocks in the response.
		// These common blocks are needed to produce the dataload-source of, eg, the Navigator Blocks, even
		// when first loading the website on an Author or Single page. Without the Navigator blocks placed in
		// common, then we can't get their dataload-source.
		// However, when generating the cache (file generator.php) these are not needed, so then skip them
		// Common blocks
		if ($include_common) {

			// Actions 
			$pageactions = array(
				POP_AAL_PAGE_NOTIFICATIONS_MARKALLASREAD => GD_TEMPLATE_ACTION_MARKALLNOTIFICATIONSASREAD,
				POP_AAL_PAGE_NOTIFICATIONS_MARKASREAD => GD_TEMPLATE_ACTION_MARKNOTIFICATIONASREAD,
				POP_AAL_PAGE_NOTIFICATIONS_MARKASUNREAD => GD_TEMPLATE_ACTION_MARKNOTIFICATIONASUNREAD,
			);
			foreach ($pageactions as $page => $action) {
				$ret[$page]['action'] = $action;
			}
		}

		$ret = apply_filters('AAL_Template_SettingsProcessor:page_blocks', $ret, $hierarchy, $include_common);

		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new AAL_Template_SettingsProcessor();
