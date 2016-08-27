<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GetPoP_Processors_Template_SettingsProcessor extends GD_Template_SettingsProcessorBase {

	// function get_page_blockgroups($hierarchy, $include_common = true) {

	// 	$ret = array();

	// 	// Page or Home
	// 	if ($hierarchy == GD_SETTINGS_HIERARCHY_PAGE || $hierarchy == GD_SETTINGS_HIERARCHY_HOME) {

	// 		$pageblockgroups = array(
	// 			GETPOP_PROCESSORS_PAGE_HOME => GD_GETPOP_TEMPLATE_BLOCKGROUP_HOME,
	// 		);
	// 		foreach ($pageblockgroups as $page => $blockgroup) {
				
	// 			// Also Default
	// 			$ret[$page]['blockgroups']['default'] = $blockgroup;
	// 		}
	// 	}

	// 	return $ret;
	// }

	function get_page_blocks($hierarchy, $include_common = true) {

		$ret = array();

		// Page or Home
		if ($hierarchy == GD_SETTINGS_HIERARCHY_PAGE || $hierarchy == GD_SETTINGS_HIERARCHY_HOME) {

			$pageblocks = array(
				GETPOP_PROCESSORS_PAGE_CONTACTABOUTUS => GD_TEMPLATE_BLOCK_CONTACTABOUTUS,
			);
			foreach ($pageblocks as $page => $block) {
				
				// Also Default
				$ret[$page]['blocks']['default'] = $block;
			}
		}

		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GetPoP_Processors_Template_SettingsProcessor();
