<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class OrganikFundraising_Processors_Template_SettingsProcessor extends GD_Template_SettingsProcessorBase {

	// function get_page_blockgroups($hierarchy, $include_common = true) {

	// 	$ret = array();

	// 	// Page or Home
	// 	if ($hierarchy == GD_SETTINGS_HIERARCHY_PAGE || $hierarchy == GD_SETTINGS_HIERARCHY_HOME) {

	// 		$pageblockgroups = array(
	// 			ORGANIKFUNDRAISING_PROCESSORS_PAGE_HOME => GD_ORGANIKFUNDRAISING_TEMPLATE_BLOCKGROUP_HOME,
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
				ORGANIKFUNDRAISING_PROCESSORS_PAGE_ASKTHEEXPERTS => GD_TEMPLATE_BLOCK_ASKTHEEXPERTS,
				ORGANIKFUNDRAISING_PROCESSORS_PAGE_HOWMUCHWENEED => GD_TEMPLATE_BLOCK_HOWMUCHWENEED,
				ORGANIKFUNDRAISING_PROCESSORS_PAGE_CONTACTABOUTUS => GD_TEMPLATE_BLOCK_CONTACTABOUTUS,
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
new OrganikFundraising_Processors_Template_SettingsProcessor();
