<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GetPoP_Processors_Template_SettingsProcessor extends GD_Template_SettingsProcessorBase {

	function get_page_blockgroups($hierarchy, $include_common = true) {

		$ret = array();

		// Page or Home
		if ($hierarchy == GD_SETTINGS_HIERARCHY_PAGE) {

			$pageblockgroups = array(
				GETPOP_PROCESSORS_PAGE_DOCUMENTATION_OVERVIEW => GD_GETPOP_TEMPLATE_BLOCKGROUP_OVERVIEW,
			);
			foreach ($pageblockgroups as $page => $blockgroup) {
				
				// Also Default
				$ret[$page]['blockgroups']['default'] = $blockgroup;
			}
		}

		return $ret;
	}

	function get_page_blocks($hierarchy, $include_common = true) {

		$ret = array();

		// Page or Home
		if ($hierarchy == GD_SETTINGS_HIERARCHY_PAGE || $hierarchy == GD_SETTINGS_HIERARCHY_HOME) {

			$pageblocks = array(
				GETPOP_PROCESSORS_PAGE_CONTACTABOUTUS => GD_TEMPLATE_BLOCK_CONTACTABOUTUS,
				GETPOP_PROCESSORS_PAGE_WHATISIT => GD_TEMPLATE_BLOCK_WHATISIT,
				// GETPOP_PROCESSORS_PAGE_DISCOVER => GD_TEMPLATE_BLOCK_DISCOVER,
				GETPOP_PROCESSORS_PAGE_FRAMEWORK => GD_TEMPLATE_BLOCK_FRAMEWORK,
				GETPOP_PROCESSORS_PAGE_DOCUMENTATION => GD_TEMPLATE_BLOCK_MENU_BODY_DOCUMENTATION,
				GETPOP_PROCESSORS_PAGE_DOCUMENTATION_COMINGSOON => GD_TEMPLATE_BLOCK_SINGLEABOUT_CONTENT,
				GETPOP_PROCESSORS_PAGE_DOCUMENTATION_MODULARITY => GD_TEMPLATE_BLOCK_SINGLEABOUT_CONTENT,
				GETPOP_PROCESSORS_PAGE_DOCUMENTATION_MODULEHIERARCHY => GD_TEMPLATE_BLOCK_SINGLEABOUT_CONTENT,
				GETPOP_PROCESSORS_PAGE_DOCUMENTATION_PROPERTIES => GD_TEMPLATE_BLOCK_SINGLEABOUT_CONTENT,
				GETPOP_PROCESSORS_PAGE_DOCUMENTATION_DATALOADING => GD_TEMPLATE_BLOCK_SINGLEABOUT_CONTENT,
				GETPOP_PROCESSORS_PAGE_DOCUMENTATION_DATAPOSTING => GD_TEMPLATE_BLOCK_SINGLEABOUT_CONTENT,
				GETPOP_PROCESSORS_PAGE_DOCUMENTATION_JSONSTRUCTURE => GD_TEMPLATE_BLOCK_SINGLEABOUT_CONTENT,
				GETPOP_PROCESSORS_PAGE_DOCUMENTATION_APPLICATIONSTATE => GD_TEMPLATE_BLOCK_SINGLEABOUT_CONTENT,
				GETPOP_PROCESSORS_PAGE_DOCUMENTATION_USABILITY => GD_TEMPLATE_BLOCK_SINGLEABOUT_CONTENT,
				GETPOP_PROCESSORS_PAGE_DOCUMENTATION_BACKGROUNDLOADING => GD_TEMPLATE_BLOCK_SINGLEABOUT_CONTENT,
				GETPOP_PROCESSORS_PAGE_DOCUMENTATION_LAZYLOADING => GD_TEMPLATE_BLOCK_SINGLEABOUT_CONTENT,
				GETPOP_PROCESSORS_PAGE_DOCUMENTATION_CACHE => GD_TEMPLATE_BLOCK_SINGLEABOUT_CONTENT,
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
