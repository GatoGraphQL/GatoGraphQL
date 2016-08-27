<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_Wassup_OrganikProcessors_Template_SettingsProcessor extends GD_Template_SettingsProcessorBase {

	function get_checkpoints($hierarchy) {

		$ret = array();

		if ($hierarchy == GD_SETTINGS_HIERARCHY_PAGE) {

			return array(
				POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_ADDFARM => Wassup_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_CHECKPOINT_LOGGEDIN_STATIC),
				POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_ADDFARMLINK => Wassup_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_CHECKPOINT_LOGGEDIN_STATIC),

				POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_MYFARMS => Wassup_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_CHECKPOINT_LOGGEDIN_DATAFROMSERVER),
				
				POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_EDITFARM => Wassup_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_CHECKPOINT_LOGGEDIN_CANEDIT),
				POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_EDITFARMLINK => Wassup_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_CHECKPOINT_LOGGEDIN_CANEDIT),
			);
		}
	
		return parent::get_checkpoints($hierarchy);
	}

	function get_page_blockgroups($hierarchy, $include_common = true) {

		$ret = array();

		// Page or Blocks inserted in Home
		if ($hierarchy == GD_SETTINGS_HIERARCHY_PAGE || $hierarchy == GD_SETTINGS_HIERARCHY_HOME) {

			$pageblockgroups = array(
				POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_FARMS => GD_TEMPLATE_BLOCKGROUP_TABPANEL_FARMS,
				POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_MYFARMS => GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYFARMS,
			);
			foreach ($pageblockgroups as $page => $blockgroup) {
				
				// Also Default
				$ret[$page]['blockgroups']['default'] = $blockgroup;
			}
		}

		// Author page blocks
		elseif ($hierarchy == GD_SETTINGS_HIERARCHY_AUTHOR) {

			$pageblockgroups = array(
				POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_FARMS => GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORFARMS,
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

				// Add Content
				POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_ADDFARM => GD_TEMPLATE_BLOCK_FARM_CREATE,
				POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_ADDFARMLINK => GD_TEMPLATE_BLOCK_FARMLINK_CREATE,
				
				// My Content
				POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_EDITFARM => GD_TEMPLATE_BLOCK_FARM_UPDATE,
				POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_EDITFARMLINK => GD_TEMPLATE_BLOCK_FARMLINK_UPDATE,
			);
			foreach ($pageblocks_allothers as $page => $block) {
				$ret[$page]['blocks']['default'] = $block;
			}

			// Actions 
			$pageactions = array(
				POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_ADDFARM => GD_TEMPLATE_ACTION_FARM_CREATE,
				POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_ADDFARMLINK => GD_TEMPLATE_ACTION_FARMLINK_CREATE,
				POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_EDITFARM => GD_TEMPLATE_ACTION_FARM_UPDATE,
				POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_EDITFARMLINK => GD_TEMPLATE_ACTION_FARMLINK_UPDATE,
			);
			foreach ($pageactions as $page => $action) {
				$ret[$page]['action'] = $action;
			}
		}

		if ($hierarchy == GD_SETTINGS_HIERARCHY_PAGE || $hierarchy == GD_SETTINGS_HIERARCHY_HOME) {

			$default_format_section = PoPTheme_Wassup_Utils::get_defaultformat_by_screen(POP_SCREEN_SECTION);
			$default_format_mycontent = PoPTheme_Wassup_Utils::get_defaultformat_by_screen(POP_SCREEN_MYCONTENT);
			
			$pageblocks_typeahead = array(
				POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_FARMS  => GD_TEMPLATE_BLOCK_FARMS_TYPEAHEAD,
			);
			foreach ($pageblocks_typeahead as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_TYPEAHEAD] = $block;

				if ($default_format_section == GD_TEMPLATEFORMAT_TYPEAHEAD) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}

			$pageblocks_navigator = array(						
				POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_FARMS  => GD_TEMPLATE_BLOCK_FARMS_SCROLL_NAVIGATOR,
			);
			foreach ($pageblocks_navigator as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_NAVIGATOR] = $block;

				if ($default_format_section == GD_TEMPLATEFORMAT_NAVIGATOR) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}

			$pageblocks_addons = array(						
				POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_FARMS  => GD_TEMPLATE_BLOCK_FARMS_SCROLL_ADDONS,
			);
			foreach ($pageblocks_addons as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_ADDONS] = $block;

				if ($default_format_section == GD_TEMPLATEFORMAT_ADDONS) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}

			$pageblocks_details = array(
				POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_FARMS  => GD_TEMPLATE_BLOCK_FARMS_SCROLL_DETAILS,
			);
			foreach ($pageblocks_details as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_DETAILS] = $block;

				if ($default_format_section == GD_TEMPLATEFORMAT_DETAILS) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_simpleview = array(
				POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_FARMS  => GD_TEMPLATE_BLOCK_FARMS_SCROLL_SIMPLEVIEW,
			);
			foreach ($pageblocks_simpleview as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_SIMPLEVIEW] = $block;

				if ($default_format_section == GD_TEMPLATEFORMAT_SIMPLEVIEW) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_fullview = array(
				POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_FARMS  => GD_TEMPLATE_BLOCK_FARMS_SCROLL_FULLVIEW,
			);
			foreach ($pageblocks_fullview as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_FULLVIEW] = $block;

				if ($default_format_section == GD_TEMPLATEFORMAT_FULLVIEW) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_thumbnail = array(
				POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_FARMS  => GD_TEMPLATE_BLOCK_FARMS_SCROLL_THUMBNAIL,
			);
			foreach ($pageblocks_thumbnail as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_THUMBNAIL] = $block;

				if ($default_format_section == GD_TEMPLATEFORMAT_THUMBNAIL) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_list = array(
				POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_FARMS  => GD_TEMPLATE_BLOCK_FARMS_SCROLL_LIST,
			);
			foreach ($pageblocks_list as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_LIST] = $block;

				if ($default_format_section == GD_TEMPLATEFORMAT_LIST) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_map = array(
				POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_FARMS  => GD_TEMPLATE_BLOCK_FARMS_SCROLLMAP,
			);
			foreach ($pageblocks_map as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_MAP] = $block;

				if ($default_format_section == GD_TEMPLATEFORMAT_MAP) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_mycontent = array(
				POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_MYFARMS => GD_TEMPLATE_BLOCK_MYFARMS_TABLE_EDIT,
			);
			foreach ($pageblocks_mycontent as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_TABLE] = $block;

				if ($default_format_mycontent == GD_TEMPLATEFORMAT_TABLE) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_mycontent_simpleviewpreviews = array(
				POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_MYFARMS => GD_TEMPLATE_BLOCK_MYFARMS_SCROLL_SIMPLEVIEWPREVIEW,
			);
			foreach ($pageblocks_mycontent_simpleviewpreviews as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_SIMPLEVIEW] = $block;

				if ($default_format_mycontent == GD_TEMPLATEFORMAT_SIMPLEVIEW) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_mycontent_fullviewpreviews = array(
				POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_MYFARMS => GD_TEMPLATE_BLOCK_MYFARMS_SCROLL_FULLVIEWPREVIEW,
			);
			foreach ($pageblocks_mycontent_fullviewpreviews as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_FULLVIEW] = $block;

				if ($default_format_mycontent == GD_TEMPLATEFORMAT_FULLVIEW) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
		}

		// Author page blocks
		elseif ($hierarchy == GD_SETTINGS_HIERARCHY_AUTHOR) {

			$default_format_authorsection = PoPTheme_Wassup_Utils::get_defaultformat_by_screen(POP_SCREEN_AUTHORSECTION);
			
			$pageblocks_details = array(
				POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_FARMS  => GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_DETAILS,
			);
			foreach ($pageblocks_details as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_DETAILS] = $block;

				if ($default_format_authorsection == GD_TEMPLATEFORMAT_DETAILS) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_simpleview = array(
				POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_FARMS  => GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_SIMPLEVIEW,
			);
			foreach ($pageblocks_simpleview as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_SIMPLEVIEW] = $block;

				if ($default_format_authorsection == GD_TEMPLATEFORMAT_SIMPLEVIEW) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_fullview = array(
				POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_FARMS  => GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_FULLVIEW,
			);
			foreach ($pageblocks_fullview as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_FULLVIEW] = $block;

				if ($default_format_authorsection == GD_TEMPLATEFORMAT_FULLVIEW) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_thumbnail = array(
				POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_FARMS  => GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_THUMBNAIL,
			);
			foreach ($pageblocks_thumbnail as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_THUMBNAIL] = $block;

				if ($default_format_authorsection == GD_TEMPLATEFORMAT_THUMBNAIL) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_list = array(
				POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_FARMS  => GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLL_LIST,
			);
			foreach ($pageblocks_list as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_LIST] = $block;

				if ($default_format_authorsection == GD_TEMPLATEFORMAT_LIST) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_map = array(
				POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_FARMS  => GD_TEMPLATE_BLOCK_AUTHORFARMS_SCROLLMAP,
			);
			foreach ($pageblocks_map as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_MAP] = $block;

				if ($default_format_authorsection == GD_TEMPLATEFORMAT_MAP) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
		}

		// Allow Events Manager to inject the settings for the Map
		$ret = apply_filters('PoPTheme_Wassup_OrganikProcessors_Template_SettingsProcessor:page_blocks', $ret, $hierarchy, $include_common);

		return $ret;	
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_OrganikProcessors_Template_SettingsProcessor();
