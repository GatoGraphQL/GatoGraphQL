<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_Wassup_PoPSystem_Template_SettingsProcessor extends GD_Template_SettingsProcessorBase {

	function silent_document($hierarchy) {

		if ($hierarchy == GD_SETTINGS_HIERARCHY_PAGE) {

			return array(
				POP_SYSTEM_PAGE_SYSTEM_INSTALL => true,
				POP_SYSTEM_PAGE_SYSTEM_BUILD => true,
			);
		}

		return parent::silent_document($hierarchy);
	}

	function get_checkpoints($hierarchy) {

		$ret = array();

		if ($hierarchy == GD_SETTINGS_HIERARCHY_PAGE) {
			return array(
				POP_SYSTEM_PAGE_SYSTEM_INSTALL => PoPTheme_Wassup_PoPSystem_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_CHECKPOINT_SYSTEMACCESSVALID),
				POP_SYSTEM_PAGE_SYSTEM_BUILD => PoPTheme_Wassup_PoPSystem_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_CHECKPOINT_SYSTEMACCESSVALID),
			);
		}
	
		return parent::get_checkpoints($hierarchy);
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
				POP_SYSTEM_PAGE_SYSTEM_INSTALL => GD_TEMPLATE_ACTION_SYSTEM_INSTALL,
				POP_SYSTEM_PAGE_SYSTEM_BUILD => GD_TEMPLATE_ACTION_SYSTEM_BUILD,
			);
			foreach ($pageactions as $page => $action) {
				$ret[$page]['action'] = $action;
			}
		}

		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_PoPSystem_Template_SettingsProcessor();
