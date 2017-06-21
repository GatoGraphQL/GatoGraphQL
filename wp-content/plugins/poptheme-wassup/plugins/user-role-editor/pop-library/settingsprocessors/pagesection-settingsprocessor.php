<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class Wassup_URE_PageSectionSettingsProcessor extends Wassup_PageSectionSettingsProcessorBase {

	function add_sideinfo_author_blockunits(&$ret, $template_id) {

		$vars = GD_TemplateManager_Utils::get_vars();
		$target = $vars['target'];
		$add = 
			($template_id == GD_TEMPLATE_PAGESECTION_SIDEINFO_AUTHOR && $target == GD_URLPARAM_TARGET_MAIN)/* ||
			($template_id == GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWAUTHOR && $target == GD_URLPARAM_TARGET_QUICKVIEW)*/;
		if ($add) {

			$blockgroups = $frames = array();
			$page_id = GD_TemplateManager_Utils::get_hierarchy_page_id();
			$page_sidebars = array(
				POP_COREPROCESSORS_PAGE_MAIN => GD_TEMPLATE_BLOCKGROUP_AUTHORMAINALLCONTENT_SIDEBAR,
				POP_WPAPI_PAGE_ALLCONTENT => GD_TEMPLATE_BLOCKGROUP_AUTHORALLCONTENT_SIDEBAR,
				POP_COREPROCESSORS_PAGE_DESCRIPTION => GD_TEMPLATE_BLOCKGROUP_AUTHOR_SIDEBAR,
				POP_COREPROCESSORS_PAGE_FOLLOWERS => GD_TEMPLATE_BLOCKGROUP_AUTHORFOLLOWERS_SIDEBAR,
				POP_COREPROCESSORS_PAGE_FOLLOWINGUSERS => GD_TEMPLATE_BLOCKGROUP_AUTHORFOLLOWINGUSERS_SIDEBAR,
				POP_COREPROCESSORS_PAGE_SUBSCRIBEDTO => GD_TEMPLATE_BLOCKGROUP_AUTHORSUBSCRIBEDTOTAGS_SIDEBAR,
				POP_COREPROCESSORS_PAGE_RECOMMENDEDPOSTS => GD_TEMPLATE_BLOCKGROUP_AUTHORRECOMMENDEDPOSTS_SIDEBAR,
				POPTHEME_WASSUP_PAGE_WEBPOSTS => GD_TEMPLATE_BLOCKGROUP_AUTHORWEBPOSTS_SIDEBAR,
				POP_URE_POPPROCESSORS_PAGE_MEMBERS => GD_TEMPLATE_BLOCKGROUP_AUTHORMEMBERS_SIDEBAR,
			);
			if ($sidebar = $page_sidebars[$page_id]) {
				$blockgroups[] = $sidebar;
			}
			// else {
				
			// 	$blockgroups[] = GD_TEMPLATE_BLOCKGROUP_AUTHOR_SIDEBAR;
			// 	// $author = $vars['global-state']['author']/*global $author*/;
			// 	// if (gd_ure_is_organization($author)) {
					
			// 	// 	$blockgroups[] = GD_TEMPLATE_BLOCKGROUP_AUTHOR_SIDEBAR_ORGANIZATION;
			// 	// }
			// 	// elseif (gd_ure_is_individual($author)) {

			// 	// 	$blockgroups[] = GD_TEMPLATE_BLOCKGROUP_AUTHOR_SIDEBAR_INDIVIDUAL;
			// 	// }
			// 	// else {
					
			// 	// 	$blocks[] = GD_TEMPLATE_BLOCK_EMPTYSIDEINFO;
			// 	// }
			// }
			// if (gd_ure_is_organization($author)) {

			// 	// For the Single Related: add the specific sidebar, which includes a delegator filter for the corresponding content
			// 	$page_sidebars = array(
			// 		POP_COREPROCESSORS_PAGE_MAIN => GD_TEMPLATE_BLOCKGROUP_AUTHORMAINALLCONTENT_SIDEBAR_ORGANIZATION,
			// 		POP_WPAPI_PAGE_ALLCONTENT => GD_TEMPLATE_BLOCKGROUP_AUTHORALLCONTENT_SIDEBAR_ORGANIZATION,
			// 		POP_COREPROCESSORS_PAGE_DESCRIPTION => GD_TEMPLATE_BLOCKGROUP_AUTHOR_SIDEBAR_ORGANIZATION,
			// 		POP_COREPROCESSORS_PAGE_FOLLOWERS => GD_TEMPLATE_BLOCKGROUP_AUTHORFOLLOWERS_SIDEBAR_ORGANIZATION,
			// 		POP_COREPROCESSORS_PAGE_FOLLOWINGUSERS => GD_TEMPLATE_BLOCKGROUP_AUTHORFOLLOWINGUSERS_SIDEBAR_ORGANIZATION,
			// 		POP_COREPROCESSORS_PAGE_RECOMMENDEDPOSTS => GD_TEMPLATE_BLOCKGROUP_AUTHORRECOMMENDEDPOSTS_SIDEBAR_ORGANIZATION,
			// 		POPTHEME_WASSUP_PAGE_WEBPOSTS => GD_TEMPLATE_BLOCKGROUP_AUTHORWEBPOSTS_SIDEBAR_ORGANIZATION,
			// 		POP_URE_POPPROCESSORS_PAGE_MEMBERS => GD_TEMPLATE_BLOCKGROUP_AUTHORMEMBERS_SIDEBAR_ORGANIZATION,
			// 	);
			// 	if ($sidebar = $page_sidebars[$page_id]) {
			// 		$blockgroups[] = $sidebar;
			// 	}
			// 	else {
			// 		$blockgroups[] = GD_TEMPLATE_BLOCKGROUP_AUTHOR_SIDEBAR_ORGANIZATION;
			// 	}
			// }
			// elseif (gd_ure_is_individual($author)) {
				
			// 	// For the Single Related: add the specific sidebar, which includes a delegator filter for the corresponding content
			// 	$page_sidebars = array(
			// 		POP_COREPROCESSORS_PAGE_MAIN => GD_TEMPLATE_BLOCKGROUP_AUTHORMAINALLCONTENT_SIDEBAR_INDIVIDUAL,
			// 		POP_WPAPI_PAGE_ALLCONTENT => GD_TEMPLATE_BLOCKGROUP_AUTHORALLCONTENT_SIDEBAR_INDIVIDUAL,
			// 		POP_COREPROCESSORS_PAGE_DESCRIPTION => GD_TEMPLATE_BLOCKGROUP_AUTHOR_SIDEBAR_INDIVIDUAL,
			// 		POP_COREPROCESSORS_PAGE_FOLLOWERS => GD_TEMPLATE_BLOCKGROUP_AUTHORFOLLOWERS_SIDEBAR_INDIVIDUAL,
			// 		POP_COREPROCESSORS_PAGE_FOLLOWINGUSERS => GD_TEMPLATE_BLOCKGROUP_AUTHORFOLLOWINGUSERS_SIDEBAR_INDIVIDUAL,
			// 		POP_COREPROCESSORS_PAGE_RECOMMENDEDPOSTS => GD_TEMPLATE_BLOCKGROUP_AUTHORRECOMMENDEDPOSTS_SIDEBAR_INDIVIDUAL,
			// 		POPTHEME_WASSUP_PAGE_WEBPOSTS => GD_TEMPLATE_BLOCKGROUP_AUTHORWEBPOSTS_SIDEBAR_INDIVIDUAL,
			// 	);
			// 	if ($sidebar = $page_sidebars[$page_id]) {
			// 		$blockgroups[] = $sidebar;
			// 	}
			// 	else {
			// 		$blockgroups[] = GD_TEMPLATE_BLOCKGROUP_AUTHOR_SIDEBAR_INDIVIDUAL;
			// 	}
			// }
			// else {
				
			// 	$blocks[] = GD_TEMPLATE_BLOCK_EMPTYSIDEINFO;
			// }

			// Frames: PageSection ControlGroups
			if ($template_id == GD_TEMPLATE_PAGESECTION_SIDEINFO_AUTHOR && $target == GD_URLPARAM_TARGET_MAIN) {

				$frames[] = GD_TEMPLATE_BLOCK_PAGECONTROL_AUTHORSIDEBAR;
			}

			GD_TemplateManager_Utils::add_blockgroups($ret, $blockgroups, GD_TEMPLATEBLOCKSETTINGS_BLOCKGROUP);

			// Add frames only if not fetching data for the block
			if (!$vars['fetching-json-data']) {

				GD_TemplateManager_Utils::add_blocks($ret, $frames, GD_TEMPLATEBLOCKSETTINGS_FRAME);
			}
		}
	}

	// function add_sideinfo_tag_blockunits(&$ret, $template_id) {

	// 	$vars = GD_TemplateManager_Utils::get_vars();
	// 	$target = $vars['target'];
	// 	$add = 
	// 		($template_id == GD_TEMPLATE_PAGESECTION_SIDEINFO_TAG && $target == GD_URLPARAM_TARGET_MAIN)/* ||
	// 		($template_id == GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWTAG && $target == GD_URLPARAM_TARGET_QUICKVIEW)*/;
	// 	if ($add) {

	// 		$blockgroups = $frames = array();
	// 		$page_id = GD_TemplateManager_Utils::get_hierarchy_page_id();
	// 		$page_sidebars = array(
	// 			POP_COREPROCESSORS_PAGE_MAIN => GD_TEMPLATE_BLOCKGROUP_TAGMAINALLCONTENT_SIDEBAR,
	// 			POP_WPAPI_PAGE_ALLCONTENT => GD_TEMPLATE_BLOCKGROUP_TAGALLCONTENT_SIDEBAR,
	// 			POPTHEME_WASSUP_PAGE_WEBPOSTS => GD_TEMPLATE_BLOCKGROUP_TAGWEBPOSTS_SIDEBAR,
	// 		);
	// 		if ($sidebar = $page_sidebars[$page_id]) {
	// 			$blockgroups[] = $sidebar;
	// 		}

	// 		// Frames: PageSection ControlGroups
	// 		if ($template_id == GD_TEMPLATE_PAGESECTION_SIDEINFO_TAG && $target == GD_URLPARAM_TARGET_MAIN) {

	// 			$frames[] = GD_TEMPLATE_BLOCK_PAGECONTROL_TAGSIDEBAR;
	// 		}

	// 		GD_TemplateManager_Utils::add_blockgroups($ret, $blockgroups, GD_TEMPLATEBLOCKSETTINGS_BLOCKGROUP);

	// 		// Add frames only if not fetching data for the block
	// 		if (!$vars['fetching-json-data']) {

	// 			GD_TemplateManager_Utils::add_blocks($ret, $frames, GD_TEMPLATEBLOCKSETTINGS_FRAME);
	// 		}
	// 	}
	// }

	function add_sideinfo_page_blockunits(&$ret, $template_id) {

		$vars = GD_TemplateManager_Utils::get_vars();
		$target = $vars['target'];
		$add = 
			($template_id == GD_TEMPLATE_PAGESECTION_SIDEINFO_PAGE && $target == GD_URLPARAM_TARGET_MAIN) ||
			($template_id == GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWPAGE && $target == GD_URLPARAM_TARGET_QUICKVIEW);
		if ($add) {

			$blocks = $blockgroups = $frames = array();
			$page_id = GD_TemplateManager_Utils::get_hierarchy_page_id();
			switch ($page_id) {
				
				case POP_URE_POPPROCESSORS_PAGE_COMMUNITIES:
				case POP_URE_POPPROCESSORS_PAGE_ORGANIZATIONS:

					$blockgroups[] = GD_TEMPLATE_BLOCKGROUP_SECTION_ORGANIZATIONS_SIDEBAR;
					break;

				case POP_URE_POPPROCESSORS_PAGE_INDIVIDUALS:

					$blockgroups[] = GD_TEMPLATE_BLOCKGROUP_SECTION_INDIVIDUALS_SIDEBAR;
					break;

				case POP_URE_POPPROCESSORS_PAGE_MYMEMBERS:

					$blockgroups[] = GD_TEMPLATE_BLOCKGROUP_SECTION_MYMEMBERS_SIDEBAR;
					break;

				// case POP_URE_POPPROCESSORS_PAGE_MEMBERS:
				case POP_URE_POPPROCESSORS_PAGE_INVITENEWMEMBERS:
				case POP_URE_POPPROCESSORS_PAGE_EDITMEMBERSHIP:
				case POP_URE_POPPROCESSORS_PAGE_EDITPROFILEORGANIZATION:
				case POP_URE_POPPROCESSORS_PAGE_EDITPROFILEINDIVIDUAL:
				case POP_URE_POPPROCESSORS_PAGE_MYCOMMUNITIES:

					$blocks[] = GD_TEMPLATE_BLOCK_EMPTYSIDEINFO;
					break;
			}

			// Frames
			switch ($page_id) {
				
				case POP_URE_POPPROCESSORS_PAGE_COMMUNITIES:
				case POP_URE_POPPROCESSORS_PAGE_ORGANIZATIONS:
				case POP_URE_POPPROCESSORS_PAGE_INDIVIDUALS:
				case POP_URE_POPPROCESSORS_PAGE_MYMEMBERS:

					$frames[] = GD_TEMPLATE_BLOCK_PAGECONTROL_PAGESIDEBAR;
					break;
			}

			GD_TemplateManager_Utils::add_blockgroups($ret, $blockgroups, GD_TEMPLATEBLOCKSETTINGS_BLOCKGROUP);
			GD_TemplateManager_Utils::add_blocks($ret, $blocks, GD_TEMPLATEBLOCKSETTINGS_MAIN);

			// Add frames only if not fetching data for the block
			if (!$vars['fetching-json-data']) {
				GD_TemplateManager_Utils::add_blocks($ret, $frames, GD_TEMPLATEBLOCKSETTINGS_FRAME);
			}		
		}
	}

	function add_author_blockunits(&$ret, $template_id) {

		global $gd_template_settingsmanager;
		$vars = GD_TemplateManager_Utils::get_vars();
		$fetching_json_data = $vars['fetching-json-data'];
		$target = $vars['target'];

		$blocks = $blockgroups = $frames = array();

		// If passing parameter 'tab' then deal with the corresponding page
		$page_id = GD_TemplateManager_Utils::get_hierarchy_page_id();
		switch ($page_id) {

			/*********************************************
			 * Sections
			 *********************************************/
			case POP_URE_POPPROCESSORS_PAGE_MEMBERS:

				$add = 
					($template_id == GD_TEMPLATE_PAGESECTION_AUTHOR && $target == GD_URLPARAM_TARGET_MAIN) ||
					($template_id == GD_TEMPLATE_PAGESECTION_QUICKVIEWAUTHOR && $target == GD_URLPARAM_TARGET_QUICKVIEW) ||
					($template_id == GD_TEMPLATE_PAGESECTION_ADDONS_AUTHOR && $target == GD_URLPARAM_TARGET_ADDONS) ||
					($template_id == GD_TEMPLATE_PAGESECTION_MODALS_AUTHOR && $target == GD_URLPARAM_TARGET_MODALS);
				if ($add) {
			
					if ($fetching_json_data) {

						$blocks[] = $gd_template_settingsmanager->get_page_block($page_id, GD_SETTINGS_HIERARCHY_AUTHOR);
						break;
					}
					// Allow the ThemeStyle to decide if to include block (eg: Swift) or blockgroup (eg: Expansive)
					$type = apply_filters(POP_HOOK_SETTINGSPROCESSORS_BLOCKTYPE_FEED, POP_BLOCKTYPE_SETTINGSPROCESSORS_BLOCK);
					if ($type == POP_BLOCKTYPE_SETTINGSPROCESSORS_BLOCK) {

						$blocks[] = $gd_template_settingsmanager->get_page_block($page_id, GD_SETTINGS_HIERARCHY_AUTHOR);
					}
					elseif ($type == POP_BLOCKTYPE_SETTINGSPROCESSORS_BLOCKGROUP) {
					
						$blockgroups[] = $gd_template_settingsmanager->get_page_blockgroup($page_id, GD_SETTINGS_HIERARCHY_AUTHOR);	
					}
					// $blockgroups[] = $gd_template_settingsmanager->get_page_blockgroup($page_id, GD_SETTINGS_HIERARCHY_AUTHOR);
				}
				break;
		}

		GD_TemplateManager_Utils::add_blockgroups($ret, $blockgroups, GD_TEMPLATEBLOCKSETTINGS_BLOCKGROUP);
		GD_TemplateManager_Utils::add_blocks($ret, $blocks, GD_TEMPLATEBLOCKSETTINGS_MAIN);
	}

	function add_page_blockunits(&$ret, $template_id) {

		global $gd_template_settingsmanager;

		$vars = GD_TemplateManager_Utils::get_vars();
		$target = $vars['target'];
		$fetching_json = $vars['fetching-json'];
		$fetching_json_data = $vars['fetching-json-data'];
		$submitted_data = $fetching_json_data && doing_post();

		$page_id = GD_TemplateManager_Utils::get_hierarchy_page_id();
		if (!$page_id) return;
		
		$blocks = $blockgroups = $replicable = $frames = array();

		switch ($page_id) {

			/*********************************************
			 * Community members
			 *********************************************/
			case POP_URE_POPPROCESSORS_PAGE_EDITMEMBERSHIP:
			
				$add = 
					($template_id == GD_TEMPLATE_PAGESECTION_PAGE && $target == GD_URLPARAM_TARGET_MAIN) ||
					($template_id == GD_TEMPLATE_PAGESECTION_ADDONS_PAGE && $target == GD_URLPARAM_TARGET_ADDONS);
				if ($add) {

					if ($submitted_data) {

						$blocks[] = $gd_template_settingsmanager->get_page_action($page_id);
						break;
					}
					$blocks[] = $gd_template_settingsmanager->get_page_block($page_id);
				}
				break;

			/**-------------------------------------------
			 * HOVER
			 *-------------------------------------------*/
			/*********************************************
			 * Login
			 *********************************************/
			case POP_URE_POPPROCESSORS_PAGE_ADDPROFILEORGANIZATION:
			case POP_URE_POPPROCESSORS_PAGE_ADDPROFILEINDIVIDUAL:

				$add = ($template_id == GD_TEMPLATE_PAGESECTION_HOVER);
				if ($add) {

					if ($submitted_data) {

						$blocks[] = $gd_template_settingsmanager->get_page_action($page_id);
						break;
					}
					$blocks[] = $gd_template_settingsmanager->get_page_block($page_id);
				}
				break;

			/**-------------------------------------------
			 * MODALS
			 *-------------------------------------------*/
			case POP_URE_POPPROCESSORS_PAGE_INVITENEWMEMBERS:
			
				$add = 
					($template_id == GD_TEMPLATE_PAGESECTION_PAGE && $target == GD_URLPARAM_TARGET_MAIN) ||
					($template_id == GD_TEMPLATE_PAGESECTION_MODALS_PAGE && $target == GD_URLPARAM_TARGET_MODALS);
				if ($add) {

					if ($submitted_data) {

						$blocks[] = $gd_template_settingsmanager->get_page_action($page_id);
						break;
					}

					$blocks[] = $gd_template_settingsmanager->get_page_block($page_id);
				}
				break;


			/**-------------------------------------------
			 * MAIN
			 *-------------------------------------------*/

			/*********************************************
			 * Edit Profile
			 *********************************************/
			case POP_URE_POPPROCESSORS_PAGE_MYCOMMUNITIES:

			/*********************************************
			 * Profiles Create/Update
			 *********************************************/
			case POP_URE_POPPROCESSORS_PAGE_EDITPROFILEORGANIZATION:
			case POP_URE_POPPROCESSORS_PAGE_EDITPROFILEINDIVIDUAL:

				$add = ($template_id == GD_TEMPLATE_PAGESECTION_PAGE);
				if ($add) {

					if ($submitted_data) {

						$blocks[] = $gd_template_settingsmanager->get_page_action($page_id);
						break;
					}

					$blocks[] = $gd_template_settingsmanager->get_page_block($page_id);
				}
				break;

			/*********************************************
			 * User Account
			 *********************************************/
			case POP_URE_POPPROCESSORS_PAGE_MYMEMBERS:
			
				if ($template_id == GD_TEMPLATE_PAGESECTION_PAGE) {

					if ($fetching_json_data) {

						$blocks[] = $gd_template_settingsmanager->get_page_block($page_id);
						break;
					}
					
					// Allow the ThemeStyle to decide if to include block (eg: Swift) or blockgroup (eg: Expansive)
					$type = apply_filters(POP_HOOK_SETTINGSPROCESSORS_BLOCKTYPE_FEED, POP_BLOCKTYPE_SETTINGSPROCESSORS_BLOCK);
					if ($type == POP_BLOCKTYPE_SETTINGSPROCESSORS_BLOCK) {

						$blocks[] = $gd_template_settingsmanager->get_page_block($page_id);
					}
					elseif ($type == POP_BLOCKTYPE_SETTINGSPROCESSORS_BLOCKGROUP) {
					
						$blockgroups[] = $gd_template_settingsmanager->get_page_blockgroup($page_id);	
					}
					// $blockgroups[] = $gd_template_settingsmanager->get_page_blockgroup($page_id);
				}
				break;

			/*********************************************
			 * Sections
			 *********************************************/
			case POP_URE_POPPROCESSORS_PAGE_COMMUNITIES:
			case POP_URE_POPPROCESSORS_PAGE_ORGANIZATIONS:
			case POP_URE_POPPROCESSORS_PAGE_INDIVIDUALS:

				$add = 
					($template_id == GD_TEMPLATE_PAGESECTION_PAGE && $target == GD_URLPARAM_TARGET_MAIN) ||
					($template_id == GD_TEMPLATE_PAGESECTION_QUICKVIEWPAGE && $target == GD_URLPARAM_TARGET_QUICKVIEW);
				if ($add) {

					if ($fetching_json_data) {

						$blocks[] = $gd_template_settingsmanager->get_page_block($page_id);
						break;
					}
					
					// Allow the ThemeStyle to decide if to include block (eg: Swift) or blockgroup (eg: Expansive)
					$type = apply_filters(POP_HOOK_SETTINGSPROCESSORS_BLOCKTYPE_FEED, POP_BLOCKTYPE_SETTINGSPROCESSORS_BLOCK);
					if ($type == POP_BLOCKTYPE_SETTINGSPROCESSORS_BLOCK) {

						$blocks[] = $gd_template_settingsmanager->get_page_block($page_id);
					}
					elseif ($type == POP_BLOCKTYPE_SETTINGSPROCESSORS_BLOCKGROUP) {
					
						$blockgroups[] = $gd_template_settingsmanager->get_page_blockgroup($page_id);	
					}
				}
				elseif ($template_id == GD_TEMPLATE_PAGESECTION_NAVIGATOR && $target == GD_URLPARAM_TARGET_NAVIGATOR) {

					$blocks[] = $gd_template_settingsmanager->get_page_block($page_id, null, GD_TEMPLATEFORMAT_NAVIGATOR);
				}
				elseif ($template_id == GD_TEMPLATE_PAGESECTION_ADDONS_PAGE && $target == GD_URLPARAM_TARGET_ADDONS) {

					$blocks[] = $gd_template_settingsmanager->get_page_block($page_id, null, GD_TEMPLATEFORMAT_ADDONS);
				}
				break;

			/*********************************************
			 * Initial load
			 *********************************************/
			case POP_COREPROCESSORS_PAGE_LOADERS_INITIALFRAMES:

				if ($template_id == GD_TEMPLATE_PAGESECTION_HOVER) {

					$replicable[] = GD_TEMPLATE_BLOCK_PROFILEORGANIZATION_CREATE;
					$replicable[] = GD_TEMPLATE_BLOCK_PROFILEINDIVIDUAL_CREATE;
				}
				elseif ($template_id == GD_TEMPLATE_PAGESECTION_MODALS_PAGE) {

					$replicable[] = GD_TEMPLATE_BLOCK_INVITENEWMEMBERS;
				}
				break;
		}

		// Frames: PageSection ControlGroups
		switch ($page_id) {

			case POP_URE_POPPROCESSORS_PAGE_COMMUNITIES:
			case POP_URE_POPPROCESSORS_PAGE_ORGANIZATIONS:
			case POP_URE_POPPROCESSORS_PAGE_INDIVIDUALS:
			case POP_URE_POPPROCESSORS_PAGE_MYMEMBERS:

				if ($template_id == GD_TEMPLATE_PAGESECTION_PAGE && $target == GD_URLPARAM_TARGET_MAIN) {

					$frames[] = GD_TEMPLATE_BLOCK_PAGEWITHSIDECONTROL;
				}
				elseif ($template_id == GD_TEMPLATE_PAGESECTION_QUICKVIEWPAGE && $target == GD_URLPARAM_TARGET_QUICKVIEW) {

					$frames[] = GD_TEMPLATE_BLOCK_QUICKVIEWPAGECONTROL;//GD_TEMPLATE_BLOCK_QUICKVIEWPAGEWITHSIDECONTROL;
				}
				break;

			// case POP_URE_POPPROCESSORS_PAGE_MEMBERS:
			case POP_URE_POPPROCESSORS_PAGE_INVITENEWMEMBERS:
			case POP_URE_POPPROCESSORS_PAGE_EDITMEMBERSHIP:
			case POP_URE_POPPROCESSORS_PAGE_EDITPROFILEORGANIZATION:
			case POP_URE_POPPROCESSORS_PAGE_EDITPROFILEINDIVIDUAL:
			case POP_URE_POPPROCESSORS_PAGE_MYCOMMUNITIES:

				if ($template_id == GD_TEMPLATE_PAGESECTION_PAGE && $target == GD_URLPARAM_TARGET_MAIN) {

					$frames[] = GD_TEMPLATE_BLOCK_PAGECONTROL;
				}
				elseif ($template_id == GD_TEMPLATE_PAGESECTION_QUICKVIEWPAGE && $target == GD_URLPARAM_TARGET_QUICKVIEW) {

					$frames[] = GD_TEMPLATE_BLOCK_QUICKVIEWPAGECONTROL;
				}
				break;
		}

		GD_TemplateManager_Utils::add_blocks($ret, $blocks, GD_TEMPLATEBLOCKSETTINGS_MAIN);
		GD_TemplateManager_Utils::add_blockgroups($ret, $blockgroups, GD_TEMPLATEBLOCKSETTINGS_BLOCKGROUP);
		GD_TemplateManager_Utils::add_blocks($ret, $replicable, GD_TEMPLATEBLOCKSETTINGS_REPLICABLE);
			
		// Add frames only if not fetching data for the block
		if (!$vars['fetching-json-data']) {
			GD_TemplateManager_Utils::add_blocks($ret, $frames, GD_TEMPLATEBLOCKSETTINGS_FRAME);
		}
	}

	function add_pagetab_blockunits(&$ret, $template_id) {

		$vars = GD_TemplateManager_Utils::get_vars();
		$target = $vars['target'];
		
		$blocks = array();
		$page_id = GD_TemplateManager_Utils::get_hierarchy_page_id();

		switch ($page_id) {
			
			case POP_URE_POPPROCESSORS_PAGE_COMMUNITIES:
			case POP_URE_POPPROCESSORS_PAGE_ORGANIZATIONS:
			case POP_URE_POPPROCESSORS_PAGE_INDIVIDUALS:
			case POP_URE_POPPROCESSORS_PAGE_MEMBERS:
			case POP_URE_POPPROCESSORS_PAGE_MYMEMBERS:
			case POP_URE_POPPROCESSORS_PAGE_INVITENEWMEMBERS:
			case POP_URE_POPPROCESSORS_PAGE_EDITMEMBERSHIP:
			case POP_URE_POPPROCESSORS_PAGE_EDITPROFILEORGANIZATION:
			case POP_URE_POPPROCESSORS_PAGE_EDITPROFILEINDIVIDUAL:
			case POP_URE_POPPROCESSORS_PAGE_MYCOMMUNITIES:

				$add = 
					($template_id == GD_TEMPLATE_PAGESECTION_PAGETABS_PAGE && $target == GD_URLPARAM_TARGET_MAIN) ||
					($template_id == GD_TEMPLATE_PAGESECTION_ADDONTABS_PAGE && $target == GD_URLPARAM_TARGET_ADDONS);
				if ($add) {

					$blocks[] = GD_TEMPLATE_BLOCK_PAGETABS_PAGE;
				}
				break;		
		}

		GD_TemplateManager_Utils::add_blocks($ret, $blocks, GD_TEMPLATEBLOCKSETTINGS_MAIN);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new Wassup_URE_PageSectionSettingsProcessor();
