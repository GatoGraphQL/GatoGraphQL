<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_Wassup_SectionProcessors_EM_Template_SettingsProcessorHooks {

	function __construct() {

		add_filter(
			'WassupProcessors_Template_SettingsProcessor:page_blocks',
			array($this, 'get_page_blocks'),
			10,
			3
		);
	}

	function get_page_blocks($ret, $hierarchy, $include_common) {

		if ($hierarchy == GD_SETTINGS_HIERARCHY_PAGE || $hierarchy == GD_SETTINGS_HIERARCHY_HOME) {

			$pageblocks_map = array(
				POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_PROJECTS  => GD_TEMPLATE_BLOCK_PROJECTS_SCROLLMAP,
			);
			foreach ($pageblocks_map as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_MAP] = $block;
			}

			$pageblocks_horizontalmap = array(
				POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_PROJECTS  => GD_TEMPLATE_BLOCK_PROJECTS_HORIZONTALSCROLLMAP,
			);
			foreach ($pageblocks_horizontalmap as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_HORIZONTALMAP] = $block;
			}
		}

		// Author page blocks
		elseif ($hierarchy == GD_SETTINGS_HIERARCHY_AUTHOR) {

			$pageblocks_map = array(
				POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_PROJECTS  => GD_TEMPLATE_BLOCK_AUTHORPROJECTS_SCROLLMAP,
			);
			foreach ($pageblocks_map as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_MAP] = $block;
			}

			$pageblocks_horizontalmap = array(
				POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_PROJECTS  => GD_TEMPLATE_BLOCK_AUTHORPROJECTS_HORIZONTALSCROLLMAP,
			);
			foreach ($pageblocks_horizontalmap as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_HORIZONTALMAP] = $block;
			}
		}

		// Tag page blocks
		elseif ($hierarchy == GD_SETTINGS_HIERARCHY_TAG) {

			$pageblocks_map = array(
				POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_PROJECTS  => GD_TEMPLATE_BLOCK_TAGPROJECTS_SCROLLMAP,
			);
			foreach ($pageblocks_map as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_MAP] = $block;
			}

			$pageblocks_horizontalmap = array(
				POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_PROJECTS  => GD_TEMPLATE_BLOCK_TAGPROJECTS_HORIZONTALSCROLLMAP,
			);
			foreach ($pageblocks_horizontalmap as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_HORIZONTALMAP] = $block;
			}
		}

		return $ret;	
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_SectionProcessors_EM_Template_SettingsProcessorHooks();
