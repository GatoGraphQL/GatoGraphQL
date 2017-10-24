<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class Wassup_URE_Template_SettingsProcessor extends GD_Template_SettingsProcessorBase {

	function get_checkpoints($hierarchy) {

		$ret = array();

		if ($hierarchy == GD_SETTINGS_HIERARCHY_PAGE) {

			return array(
				POP_URE_POPPROCESSORS_PAGE_ADDPROFILEORGANIZATION => Wassup_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_CHECKPOINT_NOTLOGGEDIN),
				POP_URE_POPPROCESSORS_PAGE_ADDPROFILEINDIVIDUAL => Wassup_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_CHECKPOINT_NOTLOGGEDIN),

				POP_URE_POPPROCESSORS_PAGE_EDITPROFILEORGANIZATION => Wassup_URE_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_URE_CHECKPOINT_PROFILEORGANIZATION_DATAFROMSERVER),
				POP_URE_POPPROCESSORS_PAGE_EDITPROFILEINDIVIDUAL => Wassup_URE_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_URE_CHECKPOINT_PROFILEINDIVIDUAL_DATAFROMSERVER),
				POP_URE_POPPROCESSORS_PAGE_INVITENEWMEMBERS => Wassup_URE_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_URE_CHECKPOINT_PROFILECOMMUNITY_STATIC),
				POP_URE_POPPROCESSORS_PAGE_EDITMEMBERSHIP => Wassup_URE_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_URE_CHECKPOINT_EDITMEMBERSHIP_DATAFROMSERVER),

				POP_URE_POPPROCESSORS_PAGE_MYCOMMUNITIES => Wassup_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_CHECKPOINT_LOGGEDIN_DATAFROMSERVER),
				POP_URE_POPPROCESSORS_PAGE_MYMEMBERS => Wassup_URE_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_URE_CHECKPOINT_PROFILECOMMUNITY_DATAFROMSERVER),
			);
		}
	
		return parent::get_checkpoints($hierarchy);
	}

	function needs_target_id($hierarchy) {

		$ret = array();

		if ($hierarchy == GD_SETTINGS_HIERARCHY_PAGE) {

			return array(
				POP_URE_POPPROCESSORS_PAGE_EDITMEMBERSHIP => true,
			);
		}
	
		return parent::needs_target_id($hierarchy);
	}

	function get_page_blockgroups($hierarchy/*, $include_common = true*/) {

		$ret = array();

		// Page or Blocks inserted in Home
		// if ($hierarchy == GD_SETTINGS_HIERARCHY_PAGE || $hierarchy == GD_SETTINGS_HIERARCHY_HOME) {
		if ($hierarchy == GD_SETTINGS_HIERARCHY_PAGE) {

			$pageblockgroups = array(
				POP_URE_POPPROCESSORS_PAGE_COMMUNITIES => GD_TEMPLATE_BLOCKGROUP_TABPANEL_COMMUNITIES,
				POP_URE_POPPROCESSORS_PAGE_ORGANIZATIONS => GD_TEMPLATE_BLOCKGROUP_TABPANEL_ORGANIZATIONS,
				POP_URE_POPPROCESSORS_PAGE_INDIVIDUALS => GD_TEMPLATE_BLOCKGROUP_TABPANEL_INDIVIDUALS,
				
				POP_URE_POPPROCESSORS_PAGE_MYMEMBERS => GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYMEMBERS,
			);
			foreach ($pageblockgroups as $page => $blockgroup) {
				
				// Also Default
				$ret[$page]['blockgroups']['default'] = $blockgroup;
			}
		}

		// Author page blocks
		elseif ($hierarchy == GD_SETTINGS_HIERARCHY_AUTHOR) {

			$pageblockgroups = array(
				POP_URE_POPPROCESSORS_PAGE_MEMBERS => GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORMEMBERS,
			);
			foreach ($pageblockgroups as $page => $blockgroup) {
				
				// Also Default
				$ret[$page]['blockgroups']['default'] = $blockgroup;
			}
		}

		return $ret;
	}

	function get_page_blocks($hierarchy/*, $include_common = true*/) {

		$ret = array();

		// $include_common: used to tell if we include also the common blocks in the response.
		// These common blocks are needed to produce the dataload-source of, eg, the Navigator Blocks, even
		// when first loading the website on an Author or Single page. Without the Navigator blocks placed in
		// common, then we can't get their dataload-source.
		// However, when generating the cache (file generator.php) these are not needed, so then skip them
		// Common blocks
		// if ($include_common) {
		if ($hierarchy == GD_SETTINGS_HIERARCHY_PAGE) {

			// Navigator
			$pageblocks_navigator = array(						
				POP_URE_POPPROCESSORS_PAGE_ORGANIZATIONS  => GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_NAVIGATOR,
				POP_URE_POPPROCESSORS_PAGE_INDIVIDUALS  => GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_NAVIGATOR,
			);
			foreach ($pageblocks_navigator as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_NAVIGATOR] = $block;
			}

			// Addons
			$pageblocks_addons = array(						
				POP_URE_POPPROCESSORS_PAGE_ORGANIZATIONS  => GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_ADDONS,
				POP_URE_POPPROCESSORS_PAGE_INDIVIDUALS  => GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_ADDONS,
			);
			foreach ($pageblocks_addons as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_ADDONS] = $block;
			}

			// Default
			$pageblocks_allothers = array(

				// Modals
				POP_URE_POPPROCESSORS_PAGE_INVITENEWMEMBERS => GD_TEMPLATE_BLOCK_INVITENEWMEMBERS,
				
				// Addon pageSection
				POP_URE_POPPROCESSORS_PAGE_EDITMEMBERSHIP => GD_TEMPLATE_BLOCK_EDITMEMBERSHIP,

				// Profile
				POP_URE_POPPROCESSORS_PAGE_ADDPROFILEORGANIZATION => GD_TEMPLATE_BLOCK_PROFILEORGANIZATION_CREATE,
				POP_URE_POPPROCESSORS_PAGE_ADDPROFILEINDIVIDUAL => GD_TEMPLATE_BLOCK_PROFILEINDIVIDUAL_CREATE,
				POP_URE_POPPROCESSORS_PAGE_EDITPROFILEORGANIZATION => GD_TEMPLATE_BLOCK_PROFILEORGANIZATION_UPDATE,
				POP_URE_POPPROCESSORS_PAGE_EDITPROFILEINDIVIDUAL => GD_TEMPLATE_BLOCK_PROFILEINDIVIDUAL_UPDATE,
				POP_URE_POPPROCESSORS_PAGE_MYCOMMUNITIES => GD_TEMPLATE_BLOCK_MYCOMMUNITIES_UPDATE,
			);
			foreach ($pageblocks_allothers as $page => $block) {
				$ret[$page]['blocks']['default'] = $block;
			}

			// Actions 
			$pageactions = array(
				POP_URE_POPPROCESSORS_PAGE_INVITENEWMEMBERS => GD_TEMPLATE_ACTION_INVITENEWMEMBERS,
				POP_URE_POPPROCESSORS_PAGE_EDITMEMBERSHIP => GD_TEMPLATE_ACTION_EDITMEMBERSHIP,
				POP_URE_POPPROCESSORS_PAGE_ADDPROFILEORGANIZATION => GD_TEMPLATE_ACTION_PROFILEORGANIZATION_CREATE,
				POP_URE_POPPROCESSORS_PAGE_EDITPROFILEORGANIZATION => GD_TEMPLATE_ACTION_PROFILEORGANIZATION_UPDATE,
				POP_URE_POPPROCESSORS_PAGE_ADDPROFILEINDIVIDUAL => GD_TEMPLATE_ACTION_PROFILEINDIVIDUAL_CREATE,
				POP_URE_POPPROCESSORS_PAGE_EDITPROFILEINDIVIDUAL => GD_TEMPLATE_ACTION_PROFILEINDIVIDUAL_UPDATE,
				POP_URE_POPPROCESSORS_PAGE_MYCOMMUNITIES => GD_TEMPLATE_ACTION_MYCOMMUNITIES_UPDATE,
			);
			foreach ($pageactions as $page => $action) {
				$ret[$page]['action'] = $action;
			}
		// }

		// // Page or Blocks inserted in Home
		// if ($hierarchy == GD_SETTINGS_HIERARCHY_PAGE || $hierarchy == GD_SETTINGS_HIERARCHY_HOME) {

			$default_format_users = PoPTheme_Wassup_Utils::get_defaultformat_by_screen(POP_SCREEN_USERS);
			$default_format_myusers = PoPTheme_Wassup_Utils::get_defaultformat_by_screen(POP_URE_SCREEN_MYUSERS);
			
			$pageblocks_typeahead = array(
				POP_URE_POPPROCESSORS_PAGE_COMMUNITIES  => GD_TEMPLATE_BLOCK_COMMUNITIES_TYPEAHEAD,
				POP_URE_POPPROCESSORS_PAGE_ORGANIZATIONS  => GD_TEMPLATE_BLOCK_ORGANIZATIONS_TYPEAHEAD,
				POP_URE_POPPROCESSORS_PAGE_INDIVIDUALS  => GD_TEMPLATE_BLOCK_INDIVIDUALS_TYPEAHEAD,
			);
			foreach ($pageblocks_typeahead as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_TYPEAHEAD] = $block;

				if ($default_format_users == GD_TEMPLATEFORMAT_TYPEAHEAD) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}

			$pageblocks_details = array(
				POP_URE_POPPROCESSORS_PAGE_COMMUNITIES  => GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_DETAILS,
				POP_URE_POPPROCESSORS_PAGE_ORGANIZATIONS  => GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_DETAILS,
				POP_URE_POPPROCESSORS_PAGE_INDIVIDUALS  => GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_DETAILS,
			);
			foreach ($pageblocks_details as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_DETAILS] = $block;

				if ($default_format_users == GD_TEMPLATEFORMAT_DETAILS) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_fullview = array(
				POP_URE_POPPROCESSORS_PAGE_COMMUNITIES  => GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_FULLVIEW,
				POP_URE_POPPROCESSORS_PAGE_ORGANIZATIONS  => GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_FULLVIEW,
				POP_URE_POPPROCESSORS_PAGE_INDIVIDUALS  => GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_FULLVIEW,
			);
			foreach ($pageblocks_fullview as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_FULLVIEW] = $block;

				if ($default_format_users == GD_TEMPLATEFORMAT_FULLVIEW) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_thumbnail = array(
				POP_URE_POPPROCESSORS_PAGE_COMMUNITIES  => GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_THUMBNAIL,
				POP_URE_POPPROCESSORS_PAGE_ORGANIZATIONS  => GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_THUMBNAIL,
				POP_URE_POPPROCESSORS_PAGE_INDIVIDUALS  => GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_THUMBNAIL,
			);
			foreach ($pageblocks_thumbnail as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_THUMBNAIL] = $block;

				if ($default_format_users == GD_TEMPLATEFORMAT_THUMBNAIL) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_list = array(
				POP_URE_POPPROCESSORS_PAGE_COMMUNITIES  => GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLL_LIST,
				POP_URE_POPPROCESSORS_PAGE_ORGANIZATIONS  => GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_LIST,
				POP_URE_POPPROCESSORS_PAGE_INDIVIDUALS  => GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_LIST,
			);
			foreach ($pageblocks_list as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_LIST] = $block;

				if ($default_format_users == GD_TEMPLATEFORMAT_LIST) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_mycontent = array(
				POP_URE_POPPROCESSORS_PAGE_MYMEMBERS => GD_TEMPLATE_BLOCK_MYMEMBERS_TABLE_EDIT,
			);
			foreach ($pageblocks_mycontent as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_TABLE] = $block;

				if ($default_format_myusers == GD_TEMPLATEFORMAT_TABLE) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_mycontent_previews = array(
				POP_URE_POPPROCESSORS_PAGE_MYMEMBERS => GD_TEMPLATE_BLOCK_MYMEMBERS_SCROLL_FULLVIEW,
			);
			foreach ($pageblocks_mycontent_previews as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_FULLVIEW] = $block;

				if ($default_format_myusers == GD_TEMPLATEFORMAT_FULLVIEW) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}

			// $pageblocks_map = array(
			// 	POP_URE_POPPROCESSORS_PAGE_COMMUNITIES  => GD_TEMPLATE_BLOCK_COMMUNITIES_SCROLLMAP,
			// 	POP_URE_POPPROCESSORS_PAGE_ORGANIZATIONS  => GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLLMAP,
			// 	POP_URE_POPPROCESSORS_PAGE_INDIVIDUALS  => GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLLMAP,
			// );
			// foreach ($pageblocks_map as $page => $block) {
			// 	$ret[$page]['blocks'][GD_TEMPLATEFORMAT_MAP] = $block;
			// }
		}

		// Author page blocks
		elseif ($hierarchy == GD_SETTINGS_HIERARCHY_AUTHOR) {

			$default_format_authorusers = PoPTheme_Wassup_Utils::get_defaultformat_by_screen(POP_SCREEN_AUTHORUSERS);
			
			$pageblocks_details = array(
				POP_URE_POPPROCESSORS_PAGE_MEMBERS  => GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_DETAILS,
			);
			foreach ($pageblocks_details as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_DETAILS] = $block;

				if ($default_format_authorusers == GD_TEMPLATEFORMAT_DETAILS) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_fullview = array(
				POP_URE_POPPROCESSORS_PAGE_MEMBERS  => GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_FULLVIEW,
			);
			foreach ($pageblocks_fullview as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_FULLVIEW] = $block;

				if ($default_format_authorusers == GD_TEMPLATEFORMAT_FULLVIEW) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_thumbnail = array(
				POP_URE_POPPROCESSORS_PAGE_MEMBERS  => GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_THUMBNAIL,
			);
			foreach ($pageblocks_thumbnail as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_THUMBNAIL] = $block;

				if ($default_format_authorusers == GD_TEMPLATEFORMAT_THUMBNAIL) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_list = array(
				POP_URE_POPPROCESSORS_PAGE_MEMBERS  => GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLL_LIST,
			);
			foreach ($pageblocks_list as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_LIST] = $block;

				if ($default_format_authorusers == GD_TEMPLATEFORMAT_LIST) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_carousels = array(
				POP_URE_POPPROCESSORS_PAGE_MEMBERS => GD_TEMPLATE_BLOCK_AUTHORMEMBERS_CAROUSEL,
			);
			foreach ($pageblocks_carousels as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_CAROUSEL] = $block;

				if ($default_format_authorusers == GD_TEMPLATEFORMAT_CAROUSEL) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			// $pageblocks_map = array(
			// 	POP_URE_POPPROCESSORS_PAGE_MEMBERS  => GD_TEMPLATE_BLOCK_AUTHORMEMBERS_SCROLLMAP,
			// );
			// foreach ($pageblocks_map as $page => $block) {
			// 	$ret[$page]['blocks'][GD_TEMPLATEFORMAT_MAP] = $block;
			// }
		}

		// Allow Events Manager to inject the settings for the Map
		$ret = apply_filters('Wassup_URE_Template_SettingsProcessor:page_blocks', $ret, $hierarchy, $include_common);

		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new Wassup_URE_Template_SettingsProcessor();
