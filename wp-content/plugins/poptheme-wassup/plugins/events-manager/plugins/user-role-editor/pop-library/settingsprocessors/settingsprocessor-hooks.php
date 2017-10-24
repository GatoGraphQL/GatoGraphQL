<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class Wassup_EM_URE_Template_SettingsProcessorHooks {

	function __construct() {

		add_filter(
			'Wassup_URE_Template_SettingsProcessor:page_blocks',
			array($this, 'get_page_blocks'),
			10,
			3
		);
	}

	function get_page_blocks($ret, $hierarchy, $include_common) {

		// Page or Blocks inserted in Home
		// if ($hierarchy == GD_SETTINGS_HIERARCHY_PAGE || $hierarchy == GD_SETTINGS_HIERARCHY_HOME) {
		if ($hierarchy == GD_SETTINGS_HIERARCHY_PAGE) {

			$pageblocks_map = array(
				POP_URE_POPPROCESSORS_PAGE_COMMUNITIES  => GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLLMAP,
				POP_URE_POPPROCESSORS_PAGE_ORGANIZATIONS  => GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLLMAP,
				POP_URE_POPPROCESSORS_PAGE_INDIVIDUALS  => GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLLMAP,
			);
			foreach ($pageblocks_map as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_MAP] = $block;
			}
		}

		// Author page blocks
		elseif ($hierarchy == GD_SETTINGS_HIERARCHY_AUTHOR) {

			$pageblocks_map = array(
				POP_URE_POPPROCESSORS_PAGE_MEMBERS  => GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLLMAP,
			);
			foreach ($pageblocks_map as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_MAP] = $block;
			}
		}

		return $ret;	
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new Wassup_EM_URE_Template_SettingsProcessorHooks();
