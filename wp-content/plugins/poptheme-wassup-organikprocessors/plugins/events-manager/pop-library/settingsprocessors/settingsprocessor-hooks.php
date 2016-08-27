<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_Wassup_OrganikProcessors_EM_Template_SettingsProcessorHooks {

	function __construct() {

		add_filter(
			'PoPTheme_Wassup_OrganikProcessors_Template_SettingsProcessor:page_blocks',
			array($this, 'get_page_blocks'),
			10,
			3
		);
	}

	function get_page_blocks($ret, $hierarchy, $include_common) {

		if ($hierarchy == GD_SETTINGS_HIERARCHY_PAGE || $hierarchy == GD_SETTINGS_HIERARCHY_HOME) {

			$pageblocks_map = array(
				POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_FARMS  => GD_TEMPLATE_BLOCK_FARMS_SCROLLMAP,
			);
			foreach ($pageblocks_map as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_MAP] = $block;
			}
		}

		// Author page blocks
		elseif ($hierarchy == GD_SETTINGS_HIERARCHY_AUTHOR) {

			$pageblocks_map = array(
				POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_FARMS  => GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLLMAP,
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
new PoPTheme_Wassup_OrganikProcessors_EM_Template_SettingsProcessorHooks();
