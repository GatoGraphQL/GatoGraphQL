<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class Wassup_GF_Template_SettingsProcessor extends GD_Template_SettingsProcessorBase {

	function is_functional($hierarchy) {

		if ($hierarchy == GD_SETTINGS_HIERARCHY_PAGE) {

			return array(
				POPTHEME_WASSUP_GF_PAGE_SHAREBYEMAIL => true,
			);
		}

		return parent::is_functional($hierarchy);
	}

	function needs_target_id($hierarchy) {

		if ($hierarchy == GD_SETTINGS_HIERARCHY_PAGE) {

			return array(
				POPTHEME_WASSUP_GF_PAGE_CONTACTUSER => true,
				POPTHEME_WASSUP_GF_PAGE_VOLUNTEER => true,
				POPTHEME_WASSUP_GF_PAGE_FLAG => true,
			);
		}

		return parent::needs_target_id($hierarchy);
	}

	function get_page_blockgroups($hierarchy, $include_common = true) {

		$ret = array();

		// Page or Blocks inserted in Home
		if ($hierarchy == GD_SETTINGS_HIERARCHY_PAGE || $hierarchy == GD_SETTINGS_HIERARCHY_HOME) {

			$pageblockgroups = array(
				POPTHEME_WASSUP_GF_PAGE_CONTACTUSER => GD_TEMPLATE_BLOCKGROUP_CONTACTUSER,
				POPTHEME_WASSUP_GF_PAGE_VOLUNTEER => GD_TEMPLATE_BLOCKGROUP_VOLUNTEER,
				POPTHEME_WASSUP_GF_PAGE_FLAG => GD_TEMPLATE_BLOCKGROUP_FLAG,
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

		// $include_common: used to tell if we include also the common blocks in the response.
		// These common blocks are needed to produce the dataload-source of, eg, the Navigator Blocks, even
		// when first loading the website on an Author or Single page. Without the Navigator blocks placed in
		// common, then we can't get their dataload-source.
		// However, when generating the cache (file generator.php) these are not needed, so then skip them
		// Common blocks
		if ($include_common) {

			// Default
			$pageblocks_allothers = array(

				// Modals
				POPTHEME_WASSUP_GF_PAGE_SHAREBYEMAIL => GD_TEMPLATE_BLOCK_SHAREBYEMAIL,
				
				// Addon pageSection
				POPTHEME_WASSUP_GF_PAGE_CONTACTUSER => GD_TEMPLATE_BLOCK_CONTACTUSER,
				POPTHEME_WASSUP_GF_PAGE_VOLUNTEER => GD_TEMPLATE_BLOCK_VOLUNTEER,
				POPTHEME_WASSUP_GF_PAGE_FLAG => GD_TEMPLATE_BLOCK_FLAG,

				// About
				POPTHEME_WASSUP_GF_PAGE_NEWSLETTER => GD_TEMPLATE_BLOCK_NEWSLETTER,
				POPTHEME_WASSUP_GF_PAGE_CONTACTUS => GD_TEMPLATE_BLOCK_CONTACTUS,
			);
			foreach ($pageblocks_allothers as $page => $block) {
				$ret[$page]['blocks']['default'] = $block;
			}

			// Actions 
			$pageactions = array(
				POPTHEME_WASSUP_GF_PAGE_CONTACTUS => GD_TEMPLATE_ACTION_CONTACTUS,
				POPTHEME_WASSUP_GF_PAGE_CONTACTUSER => GD_TEMPLATE_ACTION_CONTACTUSER,
				POPTHEME_WASSUP_GF_PAGE_SHAREBYEMAIL => GD_TEMPLATE_ACTION_SHAREBYEMAIL,
				POPTHEME_WASSUP_GF_PAGE_VOLUNTEER => GD_TEMPLATE_ACTION_VOLUNTEER,
				POPTHEME_WASSUP_GF_PAGE_FLAG => GD_TEMPLATE_ACTION_FLAG,
				POPTHEME_WASSUP_GF_PAGE_NEWSLETTER => GD_TEMPLATE_ACTION_NEWSLETTER,
			);
			foreach ($pageactions as $page => $action) {
				$ret[$page]['action'] = $action;
			}
		}

		$ret = apply_filters('Wassup_GF_Template_SettingsProcessor:page_blocks', $ret, $hierarchy, $include_common);

		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new Wassup_GF_Template_SettingsProcessor();
