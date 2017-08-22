<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_Wassup_PageSectionSettingsProcessor extends Wassup_PageSectionSettingsProcessorBase {

	function add_sideinfo_single_blockunits(&$ret, $template_id) {

		$vars = GD_TemplateManager_Utils::get_vars();
		$target = $vars['target'];
		$add = 
			($template_id == GD_TEMPLATE_PAGESECTION_SIDEINFO_SINGLE && $target == GD_URLPARAM_TARGET_MAIN)/* ||
			($template_id == GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWSINGLE && $target == GD_URLPARAM_TARGET_QUICKVIEW)*/;
		if ($add) {

			$blockgroups = $frames = array();
			$post_type = get_post_type();
			if ($post_type == 'post') {

				$cats_blockgroup = array(
					POPTHEME_WASSUP_CAT_HIGHLIGHTS => GD_TEMPLATE_BLOCKGROUP_SINGLE_HIGHLIGHT_SIDEBAR,
					POPTHEME_WASSUP_CAT_WEBPOSTS => GD_TEMPLATE_BLOCKGROUP_SINGLE_WEBPOST_SIDEBAR,
				);

				// Comment Leo 27/07/2016: all "POSTAUTHORSSIDEBAR" are commented, because to get the authors we do: 
				// $ret['include'] = gd_get_postauthors(); (in function add_dataloadqueryargs_singleauthors)
				// and the include cannot be filtered. Once it's there, it's final.
				// (And also, it doesn't look so nice to add the filter for the authors, since most likely there is always only 1 author!)

				// For the Single Related: add the specific sidebar, which includes a delegator filter for the corresponding content
				$page_id = GD_TemplateManager_Utils::get_hierarchy_page_id();
				$webpost_sidebars = array(
					POPTHEME_WASSUP_PAGE_HIGHLIGHTS => GD_TEMPLATE_BLOCKGROUP_SINGLE_WEBPOST_HIGHLIGHTSSIDEBAR,
					POP_COREPROCESSORS_PAGE_RELATEDCONTENT => GD_TEMPLATE_BLOCKGROUP_SINGLE_WEBPOST_RELATEDCONTENTSIDEBAR,
					POP_COREPROCESSORS_PAGE_POSTAUTHORS => GD_TEMPLATE_BLOCKGROUP_SINGLE_WEBPOST_POSTAUTHORSSIDEBAR,
					POP_COREPROCESSORS_PAGE_RECOMMENDEDBY => GD_TEMPLATE_BLOCKGROUP_SINGLE_WEBPOST_RECOMMENDEDBYSIDEBAR,
				);
				if ($webpost_sidebar = $webpost_sidebars[$page_id]) {
					$cats_blockgroup[POPTHEME_WASSUP_CAT_WEBPOSTS] = $webpost_sidebar;
				}
				if ($cat_blockgroup = $cats_blockgroup[gd_get_the_main_category()]) {
					$blockgroups[] = $cat_blockgroup;
				}
			}

			// Frames: PageSection ControlGroups
			if ($template_id == GD_TEMPLATE_PAGESECTION_SIDEINFO_SINGLE && $target == GD_URLPARAM_TARGET_MAIN) {

				$frames[] = GD_TEMPLATE_BLOCK_PAGECONTROL_SINGLESIDEBAR;
			}

			GD_TemplateManager_Utils::add_blockgroups($ret, $blockgroups, GD_TEMPLATEBLOCKSETTINGS_BLOCKGROUP);

			// Add frames only if not fetching data for the block
			if (!$vars['fetching-json-data']) {
				GD_TemplateManager_Utils::add_blocks($ret, $frames, GD_TEMPLATEBLOCKSETTINGS_FRAME);
			}
		}
	}

	function add_sideinfo_tag_blockunits(&$ret, $template_id) {

		global $gd_template_settingsmanager;
		$vars = GD_TemplateManager_Utils::get_vars();
		$fetching_json_data = $vars['fetching-json-data'];
		$target = $vars['target'];
		// If passing parameter 'tab' then deal with the corresponding page
		$page_id = GD_TemplateManager_Utils::get_hierarchy_page_id();
		switch ($page_id) {

			case POP_COREPROCESSORS_PAGE_MAIN:
			case POP_WPAPI_PAGE_ALLCONTENT:
			case POPTHEME_WASSUP_PAGE_WEBPOSTS:
			case POP_COREPROCESSORS_PAGE_SUBSCRIBERS:
			// case POPTHEME_WASSUP_PAGEPLACEHOLDER_TAG:

				$add = 
					($template_id == GD_TEMPLATE_PAGESECTION_SIDEINFO_TAG && $target == GD_URLPARAM_TARGET_MAIN)/* ||
					($template_id == GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWTAG && $target == GD_URLPARAM_TARGET_QUICKVIEW)*/;
				if ($add) {

					$blockgroups = $frames = array();
					
					// $blockgroups[] = GD_TEMPLATE_BLOCKGROUP_TAGSECTION_ALLCONTENT_SIDEBAR;
			 		$page_sidebars = array(
			 			POP_COREPROCESSORS_PAGE_MAIN => GD_TEMPLATE_BLOCKGROUP_TAG_MAINALLCONTENT_SIDEBAR,
			 			POP_WPAPI_PAGE_ALLCONTENT => GD_TEMPLATE_BLOCKGROUP_TAG_ALLCONTENT_SIDEBAR,
			 			POPTHEME_WASSUP_PAGE_WEBPOSTS => GD_TEMPLATE_BLOCKGROUP_TAG_WEBPOSTS_SIDEBAR,
			 			POP_COREPROCESSORS_PAGE_SUBSCRIBERS => GD_TEMPLATE_BLOCKGROUP_TAG_SUBSCRIBERS_SIDEBAR,
			 		);
			 		if ($sidebar = $page_sidebars[$page_id]) {
			 			$blockgroups[] = $sidebar;
			 		}
					$frames[] = GD_TEMPLATE_BLOCK_PAGECONTROL_TAGSIDEBAR;

					GD_TemplateManager_Utils::add_blockgroups($ret, $blockgroups, GD_TEMPLATEBLOCKSETTINGS_BLOCKGROUP);
					
					// Add frames only if not fetching data for the block
					if (!$vars['fetching-json-data']) {
						GD_TemplateManager_Utils::add_blocks($ret, $frames, GD_TEMPLATEBLOCKSETTINGS_FRAME);
					}
				}
				break;
		}
	}

	function add_sideinfo_home_blockunits(&$ret, $template_id) {

		global $gd_template_settingsmanager;
		$vars = GD_TemplateManager_Utils::get_vars();
		$fetching_json_data = $vars['fetching-json-data'];
		$target = $vars['target'];
		$add = 
			($template_id == GD_TEMPLATE_PAGESECTION_SIDEINFO_HOME && $target == GD_URLPARAM_TARGET_MAIN) ||
			($template_id == GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWHOME && $target == GD_URLPARAM_TARGET_QUICKVIEW);
		if ($add) {

			$blocks = $blockgroups = $frames = array();
			
			// Allow GetPoP website to change the sidebar, since it is changing the homepage
			if ($blockgroup = apply_filters('PoPTheme_Wassup_PageSectionSettingsProcessor:sideinfo_home:blockgroup', GD_TEMPLATE_BLOCKGROUP_HOMESECTION_ALLCONTENT_SIDEBAR)) {
				
				$blockgroups[] = $blockgroup;
				$frames[] = GD_TEMPLATE_BLOCK_PAGECONTROL_PAGESIDEBAR;

				GD_TemplateManager_Utils::add_blockgroups($ret, $blockgroups, GD_TEMPLATEBLOCKSETTINGS_BLOCKGROUP);
				
				// Add frames only if not fetching data for the block
				if (!$vars['fetching-json-data']) {
					GD_TemplateManager_Utils::add_blocks($ret, $frames, GD_TEMPLATEBLOCKSETTINGS_FRAME);
				}
			}
			// If no blockgroup, then "close" the sideinfo
			else {
				
				$blocks[] = GD_TEMPLATE_BLOCK_EMPTYSIDEINFO;
				GD_TemplateManager_Utils::add_blocks($ret, $blocks, GD_TEMPLATEBLOCKSETTINGS_MAIN);
			}
		}
	}

	function add_sideinfo_page_blockunits(&$ret, $template_id) {

		global $gd_template_settingsmanager;
		$vars = GD_TemplateManager_Utils::get_vars();
		$fetching_json_data = $vars['fetching-json-data'];
		$target = $vars['target'];
		$add = 
			($template_id == GD_TEMPLATE_PAGESECTION_SIDEINFO_PAGE && $target == GD_URLPARAM_TARGET_MAIN) ||
			($template_id == GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWPAGE && $target == GD_URLPARAM_TARGET_QUICKVIEW);
		if ($add) {

			$blocks = $blockgroups = $replicable = $blockgroupsreplicable = $frames = array();
			$page_id = GD_TemplateManager_Utils::get_hierarchy_page_id();
			switch ($page_id) {
				
				/*********************************************
				 * Initial load
				 *********************************************/
				case POP_COREPROCESSORS_PAGE_LOADERS_INITIALFRAMES:

					if ($template_id == GD_TEMPLATE_PAGESECTION_SIDEINFO_PAGE) {

						if (PoPTheme_Wassup_Utils::get_addcontent_target() == GD_URLPARAM_TARGET_MAIN) {
							
							$replicable[] = GD_TEMPLATE_BLOCK_EMPTYSIDEINFO_WEBPOST_CREATE;
							$replicable[] = GD_TEMPLATE_BLOCK_EMPTYSIDEINFO_WEBPOSTLINK_CREATE;
						}
					}
					break;

				/*********************************************
				 * About
				 *********************************************/
				case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_OURSPONSORS:
				case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_SPONSORUS:

					// $blocks[] = GD_TEMPLATE_BLOCK_MENU_SIDEBAR_ABOUT;
					$blockgroups[] = GD_TEMPLATE_BLOCKGROUP_SINGLEPAGE_ABOUT_SIDEBAR;
					break;

				case POP_WPAPI_PAGE_TRENDINGTAGS:

					// For the permanent Sideinfo's #Trending Topics
					if ($fetching_json_data) {

						$blocks[] = $gd_template_settingsmanager->get_page_block($page_id);
						break;
					}

					$blockgroups[] = GD_TEMPLATE_BLOCKGROUP_SECTION_TRENDINGTAGS_SIDEBAR;
					break;		

				case POP_WPAPI_PAGE_ALLCONTENT:

					$blockgroups[] = GD_TEMPLATE_BLOCKGROUP_SECTION_ALLCONTENT_SIDEBAR;
					break;		

				case POP_WPAPI_PAGE_ALLUSERS:

					$blockgroups[] = GD_TEMPLATE_BLOCKGROUP_SECTION_ALLUSERS_SIDEBAR;
					break;		

				case POPTHEME_WASSUP_PAGE_HIGHLIGHTS:

					$blockgroups[] = GD_TEMPLATE_BLOCKGROUP_SECTION_HIGHLIGHTS_SIDEBAR;
					break;		

				case POPTHEME_WASSUP_PAGE_WEBPOSTS:

					$blockgroups[] = GD_TEMPLATE_BLOCKGROUP_SECTION_WEBPOSTS_SIDEBAR;
					break;

				case POPTHEME_WASSUP_PAGE_WEBPOSTLINKS:

					$blockgroups[] = GD_TEMPLATE_BLOCKGROUP_SECTION_WEBPOSTLINKS_SIDEBAR;
					break;

				case POP_WPAPI_PAGE_TAGS:

					$blockgroups[] = GD_TEMPLATE_BLOCKGROUP_SECTION_TAGS_SIDEBAR;
					break;

				case POP_WPAPI_PAGE_MYCONTENT:

					$blockgroups[] = GD_TEMPLATE_BLOCKGROUP_SECTION_MYCONTENT_SIDEBAR;
					break;

				case POPTHEME_WASSUP_PAGE_MYHIGHLIGHTS:

					$blockgroups[] = GD_TEMPLATE_BLOCKGROUP_SECTION_MYHIGHLIGHTS_SIDEBAR;
					break;

				case POPTHEME_WASSUP_PAGE_MYWEBPOSTS:

					$blockgroups[] = GD_TEMPLATE_BLOCKGROUP_SECTION_MYWEBPOSTS_SIDEBAR;
					break;

				case POPTHEME_WASSUP_PAGE_MYWEBPOSTLINKS:

					$blockgroups[] = GD_TEMPLATE_BLOCKGROUP_SECTION_MYWEBPOSTLINKS_SIDEBAR;
					break;

				case POP_WPAPI_PAGE_SEARCHPOSTS:

					$blockgroups[] = GD_TEMPLATE_BLOCKGROUP_SECTION_SEARCHPOSTS_SIDEBAR;
					break;

				case POP_WPAPI_PAGE_SEARCHUSERS:

					$blockgroups[] = GD_TEMPLATE_BLOCKGROUP_SECTION_SEARCHUSERS_SIDEBAR;
					break;

				case POP_COREPROCESSORS_PAGE_FOLLOWERS:
				case POP_COREPROCESSORS_PAGE_FOLLOWINGUSERS:
				case POP_COREPROCESSORS_PAGE_POSTAUTHORS:
				case POP_COREPROCESSORS_PAGE_SUBSCRIBERS:
				case POP_COREPROCESSORS_PAGE_SUBSCRIBEDTO:
				case POP_COREPROCESSORS_PAGE_RECOMMENDEDBY:
				case POP_COREPROCESSORS_PAGE_RECOMMENDEDPOSTS:
				case POP_COREPROCESSORS_PAGE_RELATEDCONTENT:
				
				case POP_COREPROCESSORS_PAGE_INVITENEWUSERS:
				case POPTHEME_WASSUP_PAGE_ACCOUNTFAQ:
				case POP_WPAPI_PAGE_ADDCOMMENT:
				case POPTHEME_WASSUP_PAGE_ADDCONTENT:
				case POPTHEME_WASSUP_PAGE_ADDCONTENTFAQ:
				case POPTHEME_WASSUP_PAGE_ADDHIGHLIGHT:
				case POPTHEME_WASSUP_PAGE_ADDWEBPOST:
				case POPTHEME_WASSUP_PAGE_ADDWEBPOSTLINK:
				case POP_WPAPI_PAGE_CHANGEPASSWORDPROFILE:
				case POP_WPAPI_PAGE_COMMENTS:
				case POP_COREPROCESSORS_PAGE_DESCRIPTION:
				case POP_COREPROCESSORS_PAGE_DOWNVOTEDBY:
				case POP_WPAPI_PAGE_EDITAVATAR:
				case POPTHEME_WASSUP_PAGE_EDITHIGHLIGHT:
				case POPTHEME_WASSUP_PAGE_EDITWEBPOST:
				case POPTHEME_WASSUP_PAGE_EDITWEBPOSTLINK:
				case POP_WPAPI_PAGE_EDITPROFILE:
				case POP_COREPROCESSORS_PAGE_MAIN:
				case POP_COREPROCESSORS_PAGE_MYPROFILE:
				case POP_COREPROCESSORS_PAGE_SUMMARY:
				case POP_COREPROCESSORS_PAGE_UPVOTEDBY:
				case POP_COREPROCESSORS_PAGE_MYPREFERENCES:

					$blocks[] = GD_TEMPLATE_BLOCK_EMPTYSIDEINFO;
					break;		
			}

			// Frames
			switch ($page_id) {
				
				case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_OURSPONSORS:
				case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_SPONSORUS:
				case POP_WPAPI_PAGE_TRENDINGTAGS:
				case POP_WPAPI_PAGE_ALLCONTENT:
				case POP_WPAPI_PAGE_ALLUSERS:
				case POPTHEME_WASSUP_PAGE_HIGHLIGHTS:
				case POPTHEME_WASSUP_PAGE_WEBPOSTS:
				case POPTHEME_WASSUP_PAGE_WEBPOSTLINKS:
				case POP_WPAPI_PAGE_TAGS:
				case POP_WPAPI_PAGE_MYCONTENT:
				case POPTHEME_WASSUP_PAGE_MYHIGHLIGHTS:
				case POPTHEME_WASSUP_PAGE_MYWEBPOSTS:
				case POPTHEME_WASSUP_PAGE_MYWEBPOSTLINKS:
				case POP_WPAPI_PAGE_SEARCHPOSTS:
				case POP_WPAPI_PAGE_SEARCHUSERS:

					$frames[] = GD_TEMPLATE_BLOCK_PAGECONTROL_PAGESIDEBAR;
					break;
			}

			GD_TemplateManager_Utils::add_blocks($ret, $blocks, GD_TEMPLATEBLOCKSETTINGS_MAIN);
			GD_TemplateManager_Utils::add_blocks($ret, $replicable, GD_TEMPLATEBLOCKSETTINGS_REPLICABLE);
			GD_TemplateManager_Utils::add_blockgroups($ret, $blockgroups, GD_TEMPLATEBLOCKSETTINGS_BLOCKGROUP);
			GD_TemplateManager_Utils::add_blockgroups($ret, $blockgroupsreplicable, GD_TEMPLATEBLOCKSETTINGS_BLOCKGROUPREPLICABLE);
			
			// Add frames only if not fetching data for the block
			if (!$vars['fetching-json-data']) {
				GD_TemplateManager_Utils::add_blocks($ret, $frames, GD_TEMPLATEBLOCKSETTINGS_FRAME);
			}
		}
	}

	function add_sideinfo_empty_blockunits(&$ret, $template_id) {

		$vars = GD_TemplateManager_Utils::get_vars();
		$target = $vars['target'];
		$add = 
			($template_id == GD_TEMPLATE_PAGESECTION_SIDEINFO_EMPTY && $target == GD_URLPARAM_TARGET_MAIN) ||
			($template_id == GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWEMPTY && $target == GD_URLPARAM_TARGET_QUICKVIEW);
		if ($add) {

			$blocks = array();
			$blocks[] = GD_TEMPLATE_BLOCK_EMPTYSIDEINFO;
			GD_TemplateManager_Utils::add_blocks($ret, $blocks, GD_TEMPLATEBLOCKSETTINGS_MAIN);
		}
	}

	function add_single_blockunits(&$ret, $template_id) {

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
			case POP_COREPROCESSORS_PAGE_DESCRIPTION:
			case POP_COREPROCESSORS_PAGE_RELATEDCONTENT:
			case POPTHEME_WASSUP_PAGE_HIGHLIGHTS:
			case POP_COREPROCESSORS_PAGE_POSTAUTHORS:
			case POP_COREPROCESSORS_PAGE_RECOMMENDEDBY:

			// Comment Leo 09/11/2015: No need to add this information for the Upvote/Downvote, it's too much
			// case POP_COREPROCESSORS_PAGE_UPVOTEDBY:
			// case POP_COREPROCESSORS_PAGE_DOWNVOTEDBY:

				$add = 
					($template_id == GD_TEMPLATE_PAGESECTION_SINGLE && $target == GD_URLPARAM_TARGET_MAIN) ||
					($template_id == GD_TEMPLATE_PAGESECTION_QUICKVIEWSINGLE && $target == GD_URLPARAM_TARGET_QUICKVIEW) ||
					($template_id == GD_TEMPLATE_PAGESECTION_ADDONS_SINGLE && $target == GD_URLPARAM_TARGET_ADDONS) ||
					($template_id == GD_TEMPLATE_PAGESECTION_MODALS_SINGLE && $target == GD_URLPARAM_TARGET_MODALS);
				if ($add) {
			
					if ($fetching_json_data) {

						$blocks[] = $gd_template_settingsmanager->get_page_block($page_id, GD_SETTINGS_HIERARCHY_SINGLE);
						break;
					}

					$exceptions = array(
						POP_COREPROCESSORS_PAGE_DESCRIPTION,
					);
					if (in_array($page_id, $exceptions)) {
						$blockgroups[] = $gd_template_settingsmanager->get_page_blockgroup($page_id, GD_SETTINGS_HIERARCHY_SINGLE);	
					}
					else {

						// Allow the ThemeStyle to decide if to include block (eg: Swift) or blockgroup (eg: Expansive)
						$type = apply_filters(POP_HOOK_SETTINGSPROCESSORS_BLOCKTYPE_FEED, POP_BLOCKTYPE_SETTINGSPROCESSORS_BLOCK);
						if ($type == POP_BLOCKTYPE_SETTINGSPROCESSORS_BLOCK) {

							$blocks[] = $gd_template_settingsmanager->get_page_block($page_id, GD_SETTINGS_HIERARCHY_SINGLE);
						}
						elseif ($type == POP_BLOCKTYPE_SETTINGSPROCESSORS_BLOCKGROUP) {
						
							$blockgroups[] = $gd_template_settingsmanager->get_page_blockgroup($page_id, GD_SETTINGS_HIERARCHY_SINGLE);	
						}
						// $blockgroups[] = $gd_template_settingsmanager->get_page_blockgroup($page_id, GD_SETTINGS_HIERARCHY_SINGLE);
					}
				}
				break;
		}

		// Frames
		switch ($page_id) {

				// Add the "Close" button at the end of the single post
			case POP_COREPROCESSORS_PAGE_DESCRIPTION:

				// Frames: PageSection ControlGroups
				if ($template_id == GD_TEMPLATE_PAGESECTION_SINGLE && $target == GD_URLPARAM_TARGET_MAIN) {

					$frames[] = GD_TEMPLATE_BLOCK_SINGLECONTROL;
				}
				elseif ($template_id == GD_TEMPLATE_PAGESECTION_QUICKVIEWSINGLE && $target == GD_URLPARAM_TARGET_QUICKVIEW) {

					$frames[] = GD_TEMPLATE_BLOCK_QUICKVIEWSINGLECONTROL;
				}
				break;
			
			default:

				if ($template_id == GD_TEMPLATE_PAGESECTION_SINGLE && $target == GD_URLPARAM_TARGET_MAIN) {

					$frames[] = GD_TEMPLATE_BLOCK_PAGEWITHSIDECONTROL;
				}
				elseif ($template_id == GD_TEMPLATE_PAGESECTION_QUICKVIEWSINGLE && $target == GD_URLPARAM_TARGET_QUICKVIEW) {

					$frames[] = GD_TEMPLATE_BLOCK_QUICKVIEWPAGECONTROL;//GD_TEMPLATE_BLOCK_QUICKVIEWPAGEWITHSIDECONTROL;
				}
				break;
		}

		GD_TemplateManager_Utils::add_blockgroups($ret, $blockgroups, GD_TEMPLATEBLOCKSETTINGS_BLOCKGROUP);
		GD_TemplateManager_Utils::add_blocks($ret, $blocks, GD_TEMPLATEBLOCKSETTINGS_MAIN);
			
		// Add frames only if not fetching data for the block
		if (!$vars['fetching-json-data']) {
			GD_TemplateManager_Utils::add_blocks($ret, $frames, GD_TEMPLATEBLOCKSETTINGS_FRAME);
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
			case POP_COREPROCESSORS_PAGE_MAIN:

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
					
					$blockgroups[] = GD_TEMPLATE_BLOCKGROUP_AUTHOR;
				}
				break;

			case POP_COREPROCESSORS_PAGE_DESCRIPTION:
			case POP_COREPROCESSORS_PAGE_SUMMARY:
			case POP_WPAPI_PAGE_SEARCHPOSTS:
			case POP_WPAPI_PAGE_ALLCONTENT:
			case POPTHEME_WASSUP_PAGE_WEBPOSTLINKS:
			case POPTHEME_WASSUP_PAGE_HIGHLIGHTS:
			case POPTHEME_WASSUP_PAGE_WEBPOSTS:
			case POP_COREPROCESSORS_PAGE_FOLLOWERS:
			case POP_COREPROCESSORS_PAGE_FOLLOWINGUSERS:
			case POP_COREPROCESSORS_PAGE_SUBSCRIBEDTO:
			case POP_COREPROCESSORS_PAGE_RECOMMENDEDPOSTS:
			case POP_WPAPI_PAGE_ALLUSERS:

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

					$exceptions = array(
						POP_COREPROCESSORS_PAGE_DESCRIPTION,
						POP_COREPROCESSORS_PAGE_SUMMARY,
					);
					if (in_array($page_id, $exceptions)) {
						$blockgroups[] = $gd_template_settingsmanager->get_page_blockgroup($page_id, GD_SETTINGS_HIERARCHY_AUTHOR);	
					}
					else {

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
				}
				break;
		}

		// Frames: PageSection ControlGroups
		if ($template_id == GD_TEMPLATE_PAGESECTION_AUTHOR && $target == GD_URLPARAM_TARGET_MAIN) {

			$frames[] = GD_TEMPLATE_BLOCK_AUTHORCONTROL;
		}
		elseif ($template_id == GD_TEMPLATE_PAGESECTION_QUICKVIEWAUTHOR && $target == GD_URLPARAM_TARGET_QUICKVIEW) {

			$frames[] = GD_TEMPLATE_BLOCK_QUICKVIEWAUTHORCONTROL;
		}

		GD_TemplateManager_Utils::add_blockgroups($ret, $blockgroups, GD_TEMPLATEBLOCKSETTINGS_BLOCKGROUP);
		GD_TemplateManager_Utils::add_blocks($ret, $blocks, GD_TEMPLATEBLOCKSETTINGS_MAIN);
			
		// Add frames only if not fetching data for the block
		if (!$vars['fetching-json-data']) {
			GD_TemplateManager_Utils::add_blocks($ret, $frames, GD_TEMPLATEBLOCKSETTINGS_FRAME);
		}
	}

	function add_home_blockunits(&$ret, $template_id) {

		global $gd_template_settingsmanager;
		$vars = GD_TemplateManager_Utils::get_vars();
		$fetching_json_data = $vars['fetching-json-data'];
		$target = $vars['target'];
		$blocks = $blockgroups = $frames = array();

		$add = 
			($template_id == GD_TEMPLATE_PAGESECTION_HOME && $target == GD_URLPARAM_TARGET_MAIN) ||
			($template_id == GD_TEMPLATE_PAGESECTION_QUICKVIEWHOME && $target == GD_URLPARAM_TARGET_QUICKVIEW) ||
			($template_id == GD_TEMPLATE_PAGESECTION_ADDONS_HOME && $target == GD_URLPARAM_TARGET_ADDONS) ||
			($template_id == GD_TEMPLATE_PAGESECTION_MODALS_HOME && $target == GD_URLPARAM_TARGET_MODALS);
		if ($add) {
			
			if ($fetching_json_data) {

				// Comment Leo 20/06/2017: $page_id is POPTHEME_WASSUP_PAGEPLACEHOLDER_HOME, however the settings-processor is set using POP_WPAPI_PAGE_ALLCONTENT,
				// so there was an exception and a quick fix it so use this other page. Still gotta fix properly
				$page_id = POP_WPAPI_PAGE_ALLCONTENT; //GD_TemplateManager_Utils::get_hierarchy_page_id();
				$blocks[] = $gd_template_settingsmanager->get_page_block($page_id);
			}
			else {

				$blockgroups[] = GD_TEMPLATE_BLOCKGROUP_HOME;
			}

			// Frames: PageSection ControlGroups
			if ($template_id == GD_TEMPLATE_PAGESECTION_HOME && $target == GD_URLPARAM_TARGET_MAIN) {

				// Allow Verticals to not add the frame on the homepage
				if ($frame = apply_filters('PoPTheme_Wassup_PageSectionSettingsProcessor:home:frame', GD_TEMPLATE_BLOCK_HOMECONTROL)) {
					$frames[] = $frame;
				}
			}
			elseif ($template_id == GD_TEMPLATE_PAGESECTION_QUICKVIEWHOME && $target == GD_URLPARAM_TARGET_QUICKVIEW) {

				$frames[] = GD_TEMPLATE_BLOCK_QUICKVIEWHOMECONTROL;
			}

			GD_TemplateManager_Utils::add_blockgroups($ret, $blockgroups, GD_TEMPLATEBLOCKSETTINGS_BLOCKGROUP);
			GD_TemplateManager_Utils::add_blocks($ret, $blocks, GD_TEMPLATEBLOCKSETTINGS_MAIN);
				
			// Add frames only if not fetching data for the block
			if (!$vars['fetching-json-data']) {
				GD_TemplateManager_Utils::add_blocks($ret, $frames, GD_TEMPLATEBLOCKSETTINGS_FRAME);
			}
		}
	}

	function add_tag_blockunits(&$ret, $template_id) {

		global $gd_template_settingsmanager;
		$vars = GD_TemplateManager_Utils::get_vars();
		$fetching_json_data = $vars['fetching-json-data'];
		$target = $vars['target'];
		$blocks = $blockgroups = $frames = array();

		// If passing parameter 'tab' then deal with the corresponding page
		$page_id = GD_TemplateManager_Utils::get_hierarchy_page_id();
		switch ($page_id) {

			// case POP_WPAPI_PAGE_ALLCONTENT:
			case POP_COREPROCESSORS_PAGE_MAIN:
			// case POPTHEME_WASSUP_PAGEPLACEHOLDER_TAG:

				$add = 
					($template_id == GD_TEMPLATE_PAGESECTION_TAG && $target == GD_URLPARAM_TARGET_MAIN) ||
					($template_id == GD_TEMPLATE_PAGESECTION_QUICKVIEWTAG && $target == GD_URLPARAM_TARGET_QUICKVIEW) ||
					// ($template_id == GD_TEMPLATE_PAGESECTION_ADDONS_TAG && $target == GD_URLPARAM_TARGET_ADDONS) ||
					($template_id == GD_TEMPLATE_PAGESECTION_MODALS_TAG && $target == GD_URLPARAM_TARGET_MODALS);
				if ($add) {

					if ($fetching_json_data) {

						$blocks[] = $gd_template_settingsmanager->get_page_block($page_id, GD_SETTINGS_HIERARCHY_TAG);
					}
					else {

						$blockgroups[] = GD_TEMPLATE_BLOCKGROUP_TAG;
					}
				}
				elseif ($template_id == GD_TEMPLATE_PAGESECTION_NAVIGATOR && $target == GD_URLPARAM_TARGET_NAVIGATOR) {

					$blocks[] = $gd_template_settingsmanager->get_page_block($page_id, null, GD_TEMPLATEFORMAT_NAVIGATOR);
				}
				// elseif ($template_id == GD_TEMPLATE_PAGESECTION_ADDONS_TAG && $target == GD_URLPARAM_TARGET_ADDONS) {

				// 	$blocks[] = $gd_template_settingsmanager->get_page_block($page_id, null, GD_TEMPLATEFORMAT_ADDONS);
				// }
				break;

			// case POP_COREPROCESSORS_PAGE_MAIN:
			case POP_WPAPI_PAGE_ALLCONTENT:
			case POPTHEME_WASSUP_PAGE_WEBPOSTS:
			case POP_COREPROCESSORS_PAGE_SUBSCRIBERS:
			// case POPTHEME_WASSUP_PAGEPLACEHOLDER_TAG:

				$add = 
					($template_id == GD_TEMPLATE_PAGESECTION_TAG && $target == GD_URLPARAM_TARGET_MAIN) ||
					($template_id == GD_TEMPLATE_PAGESECTION_QUICKVIEWTAG && $target == GD_URLPARAM_TARGET_QUICKVIEW) ||
					($template_id == GD_TEMPLATE_PAGESECTION_ADDONS_TAG && $target == GD_URLPARAM_TARGET_ADDONS) ||
					($template_id == GD_TEMPLATE_PAGESECTION_MODALS_TAG && $target == GD_URLPARAM_TARGET_MODALS);
				if ($add) {

					if ($fetching_json_data) {

						$blocks[] = $gd_template_settingsmanager->get_page_block($page_id, GD_SETTINGS_HIERARCHY_TAG);
					}
					else {

						// Allow the ThemeStyle to decide if to include block (eg: Swift) or blockgroup (eg: Expansive)
						$type = apply_filters(POP_HOOK_SETTINGSPROCESSORS_BLOCKTYPE_FEED, POP_BLOCKTYPE_SETTINGSPROCESSORS_BLOCK);
						if ($type == POP_BLOCKTYPE_SETTINGSPROCESSORS_BLOCK) {

							$blocks[] = $gd_template_settingsmanager->get_page_block($page_id, GD_SETTINGS_HIERARCHY_TAG);
						}
						elseif ($type == POP_BLOCKTYPE_SETTINGSPROCESSORS_BLOCKGROUP) {
						
							$blockgroups[] = $gd_template_settingsmanager->get_page_blockgroup($page_id, GD_SETTINGS_HIERARCHY_TAG);	
						}
					}
				}
				break;
		}

		GD_TemplateManager_Utils::add_blockgroups($ret, $blockgroups, GD_TEMPLATEBLOCKSETTINGS_BLOCKGROUP);
		GD_TemplateManager_Utils::add_blocks($ret, $blocks, GD_TEMPLATEBLOCKSETTINGS_MAIN);

		switch ($page_id) {

			case POP_COREPROCESSORS_PAGE_MAIN:
			case POP_WPAPI_PAGE_ALLCONTENT:
			case POPTHEME_WASSUP_PAGE_WEBPOSTS:
			case POP_COREPROCESSORS_PAGE_SUBSCRIBERS:

				// Frames: PageSection ControlGroups
				if ($template_id == GD_TEMPLATE_PAGESECTION_TAG && $target == GD_URLPARAM_TARGET_MAIN) {

					$frames[] = GD_TEMPLATE_BLOCK_TAGCONTROL;
				}
				elseif ($template_id == GD_TEMPLATE_PAGESECTION_QUICKVIEWTAG && $target == GD_URLPARAM_TARGET_QUICKVIEW) {

					$frames[] = GD_TEMPLATE_BLOCK_QUICKVIEWTAGCONTROL;
				}
				break;
		}

		// Add frames only if not fetching data for the block
		if (!$fetching_json_data) {
			GD_TemplateManager_Utils::add_blocks($ret, $frames, GD_TEMPLATEBLOCKSETTINGS_FRAME);
		}
	}

	function add_error_blockunits(&$ret, $template_id) {

		$vars = GD_TemplateManager_Utils::get_vars();
		$target = $vars['target'];

		$add = 
			($template_id == GD_TEMPLATE_PAGESECTION_404 && $target == GD_URLPARAM_TARGET_MAIN) ||
			($template_id == GD_TEMPLATE_PAGESECTION_QUICKVIEW404 && $target == GD_URLPARAM_TARGET_QUICKVIEW) ||
			($template_id == GD_TEMPLATE_PAGESECTION_ADDONS_404 && $target == GD_URLPARAM_TARGET_ADDONS) ||
			($template_id == GD_TEMPLATE_PAGESECTION_MODALS_404 && $target == GD_URLPARAM_TARGET_MODALS);
		if ($add) {

			// First add the Home Block, then the usual main block
			$blockgroups = array(
				GD_TEMPLATE_BLOCKGROUP_404
			);
			GD_TemplateManager_Utils::add_blockgroups($ret, $blockgroups, GD_TEMPLATEBLOCKSETTINGS_BLOCKGROUP);
		}
	}

	function add_page_blockunits(&$ret, $template_id) {

		global $gd_template_settingsmanager;

		$vars = GD_TemplateManager_Utils::get_vars();
		$target = $vars['target'];
		$fetching_json = $vars['fetching-json'];
		$fetching_json_data = $vars['fetching-json-data'];
		$submitted_data = $fetching_json_data && doing_post();

		$page_id = GD_TemplateManager_Utils::get_hierarchy_page_id();
		
		// Comment Leo 27/07/2016: function add_page_blockunits is invoked also for error pages, which produce no $page_id
		// Because POP_FRONTENDENGINE_PAGE_EXTERNAL has no value, the switch actually matches the "false" value from POP_FRONTENDENGINE_PAGE_EXTERNAL with the "null" value from $page_id
		// and it enters the condition. To avoid that problem, make sure there is a $page_id
		if (!$page_id) return;

		$blocks = $blockgroups = $replicable = $blockgroupsreplicable = $frames = array();

		switch ($page_id) {

			/*********************************************
			 * Add/Edit Content
			 *********************************************/
			case POPTHEME_WASSUP_PAGE_ADDWEBPOSTLINK:
			case POPTHEME_WASSUP_PAGE_EDITWEBPOSTLINK:
			case POPTHEME_WASSUP_PAGE_ADDHIGHLIGHT:
			case POPTHEME_WASSUP_PAGE_EDITHIGHLIGHT:
			case POPTHEME_WASSUP_PAGE_ADDWEBPOST:
			case POPTHEME_WASSUP_PAGE_EDITWEBPOST:
			
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

			case POP_WPAPI_PAGE_ADDCOMMENT:

				$add = 
					($template_id == GD_TEMPLATE_PAGESECTION_PAGE && $target == GD_URLPARAM_TARGET_MAIN) ||
					($template_id == GD_TEMPLATE_PAGESECTION_ADDONS_PAGE && $target == GD_URLPARAM_TARGET_ADDONS);
				if ($add) {

					if ($submitted_data) {

						$blocks[] = $gd_template_settingsmanager->get_page_action($page_id);
						$blocks[] = GD_TEMPLATE_BLOCKDATA_ADDCOMMENT;
						break;
					}

					// Add different blockgroups for the page and the addons (to be replicated)
					// $blockgroups[] = $gd_template_settingsmanager->get_page_blockgroup($page_id);
					$blockgroups[] = ($template_id == GD_TEMPLATE_PAGESECTION_PAGE) ? GD_TEMPLATE_BLOCKGROUP_ADDCOMMENT : GD_TEMPLATE_BLOCKGROUP_REPLICATEADDCOMMENT;
				}
				break;

			/**-------------------------------------------
			 * MODALS
			 *-------------------------------------------*/
			case POPTHEME_WASSUP_PAGE_ADDCONTENTFAQ:
			case POPTHEME_WASSUP_PAGE_ACCOUNTFAQ:
			
				$add = 
					($template_id == GD_TEMPLATE_PAGESECTION_PAGE && $target == GD_URLPARAM_TARGET_MAIN) ||
					($template_id == GD_TEMPLATE_PAGESECTION_MODALS_PAGE && $target == GD_URLPARAM_TARGET_MODALS);
				if ($add) {

					$blocks[] = $gd_template_settingsmanager->get_page_block($page_id);
				}
				break;

			case POP_COREPROCESSORS_PAGE_INVITENEWUSERS:
			
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
			 * OPERATIONAL
			 *-------------------------------------------*/
			case POP_COREPROCESSORS_PAGE_LOADERS_INITIALIZEDOMAIN:
			case POP_COREPROCESSORS_PAGE_LOGGEDINUSERDATA:
			case POP_COREPROCESSORS_PAGE_FOLLOWUSER:
			case POP_COREPROCESSORS_PAGE_UNFOLLOWUSER:
			case POP_COREPROCESSORS_PAGE_RECOMMENDPOST:
			case POP_COREPROCESSORS_PAGE_UNRECOMMENDPOST:
			case POP_COREPROCESSORS_PAGE_SUBSCRIBETOTAG:
			case POP_COREPROCESSORS_PAGE_UNSUBSCRIBEFROMTAG:
			case POP_COREPROCESSORS_PAGE_UPVOTEPOST:
			case POP_COREPROCESSORS_PAGE_UNDOUPVOTEPOST:
			case POP_COREPROCESSORS_PAGE_DOWNVOTEPOST:
			case POP_COREPROCESSORS_PAGE_UNDODOWNVOTEPOST:

				if ($template_id == GD_TEMPLATE_PAGESECTION_OPERATIONAL) {
			
					$blockgroups[] = $gd_template_settingsmanager->get_page_blockgroup($page_id);
					break;
				}
				break;

			case POP_FRONTENDENGINE_PAGE_EXTERNAL:
			case POP_WPAPI_PAGE_LOADERS_POSTS_FIELDS:
			case POP_WPAPI_PAGE_LOADERS_USERS_FIELDS:
			case POP_WPAPI_PAGE_LOADERS_COMMENTS_FIELDS:
			case POP_WPAPI_PAGE_LOADERS_TAGS_FIELDS:
			case POP_WPAPI_PAGE_LOADERS_POSTS_LAYOUTS:
			case POP_WPAPI_PAGE_LOADERS_USERS_LAYOUTS:
			case POP_WPAPI_PAGE_LOADERS_COMMENTS_LAYOUTS:
			case POP_WPAPI_PAGE_LOADERS_TAGS_LAYOUTS:

				// Added in Components instead of Operational because GD_TEMPLATE_BLOCK_LATESTCOUNTS is added as a unique block,
				// and this is the block requesting the data served by POP_WPAPI_PAGE_LOADERS_POSTS_LAYOUTS 
				// if ($template_id == GD_TEMPLATE_PAGESECTION_OPERATIONAL) {
				if ($template_id == GD_TEMPLATE_PAGESECTION_COMPONENTS) {
			
					$blocks[] = $gd_template_settingsmanager->get_page_block($page_id);
					break;
				}
				break;


			/**-------------------------------------------
			 * HOVER
			 *-------------------------------------------*/
			/*********************************************
			 * Login
			 *********************************************/
			case POP_WPAPI_PAGE_LOGIN:

				$add = ($template_id == GD_TEMPLATE_PAGESECTION_HOVER);
				if ($add) {
					
					if ($submitted_data) {

						$blocks[] = $gd_template_settingsmanager->get_page_action($page_id);
						
						// Fetch all the data from the logged in user (Follows Users, etc)
						$blocks = array_merge(
							$blocks,
							GD_Template_Processor_UserAccountUtils::get_loggedinuserdata_blocks()
						);
						break;
					}
					$blockgroups[] = $gd_template_settingsmanager->get_page_blockgroup($page_id);
				}
				break;

			case POP_WPAPI_PAGE_LOSTPWD:
			case POP_WPAPI_PAGE_LOSTPWDRESET:
			case POP_WPAPI_PAGE_LOGOUT:
			case POP_COREPROCESSORS_PAGE_SETTINGS:

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
			 * MAIN
			 *-------------------------------------------*/

			/*********************************************
			 * Edit Profile
			 *********************************************/
			case POP_WPAPI_PAGE_CHANGEPASSWORDPROFILE:

			/*********************************************
			 * Profiles Create/Update
			 *********************************************/
			case POP_WPAPI_PAGE_EDITAVATAR:
			case POP_COREPROCESSORS_PAGE_MYPREFERENCES:

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
			 * Sections
			 *********************************************/
			case POP_WPAPI_PAGE_SEARCHPOSTS:
			case POP_WPAPI_PAGE_SEARCHUSERS:
			
			/*********************************************
			 * My Content
			 *********************************************/
			case POP_WPAPI_PAGE_MYCONTENT:
			case POPTHEME_WASSUP_PAGE_MYWEBPOSTLINKS:
			case POPTHEME_WASSUP_PAGE_MYHIGHLIGHTS:
			case POPTHEME_WASSUP_PAGE_MYWEBPOSTS:
			// case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_MYRESOURCES:

				if ($template_id == GD_TEMPLATE_PAGESECTION_PAGE) {

					if ($fetching_json_data) {

						$blocks[] = $gd_template_settingsmanager->get_page_block($page_id);
						break;
					}

					$blocktypes = array(
						POP_WPAPI_PAGE_SEARCHPOSTS => POP_HOOK_SETTINGSPROCESSORS_BLOCKTYPE_FEED,
						POP_WPAPI_PAGE_SEARCHUSERS => POP_HOOK_SETTINGSPROCESSORS_BLOCKTYPE_FEED,
						POP_WPAPI_PAGE_MYCONTENT => POP_HOOK_SETTINGSPROCESSORS_BLOCKTYPE_TABLE,
						POPTHEME_WASSUP_PAGE_MYWEBPOSTLINKS => POP_HOOK_SETTINGSPROCESSORS_BLOCKTYPE_TABLE,
						POPTHEME_WASSUP_PAGE_MYHIGHLIGHTS => POP_HOOK_SETTINGSPROCESSORS_BLOCKTYPE_TABLE,
						POPTHEME_WASSUP_PAGE_MYWEBPOSTS => POP_HOOK_SETTINGSPROCESSORS_BLOCKTYPE_TABLE,
					);
					// Allow the ThemeStyle to decide if to include block (eg: Swift) or blockgroup (eg: Expansive)
					$type = apply_filters($blocktypes[$page_id], POP_BLOCKTYPE_SETTINGSPROCESSORS_BLOCK);
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
			case POP_WPAPI_PAGE_ALLCONTENT:
			case POPTHEME_WASSUP_PAGE_WEBPOSTLINKS:
			case POPTHEME_WASSUP_PAGE_HIGHLIGHTS:
			case POPTHEME_WASSUP_PAGE_WEBPOSTS:			
			case POP_WPAPI_PAGE_ALLUSERS:

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
					// $blockgroups[] = $gd_template_settingsmanager->get_page_blockgroup($page_id);
				}
				elseif ($template_id == GD_TEMPLATE_PAGESECTION_NAVIGATOR && $target == GD_URLPARAM_TARGET_NAVIGATOR) {

					$blocks[] = $gd_template_settingsmanager->get_page_block($page_id, null, GD_TEMPLATEFORMAT_NAVIGATOR);
				}
				elseif ($template_id == GD_TEMPLATE_PAGESECTION_ADDONS_PAGE && $target == GD_URLPARAM_TARGET_ADDONS) {

					$blocks[] = $gd_template_settingsmanager->get_page_block($page_id, null, GD_TEMPLATEFORMAT_ADDONS);
				}
				break;

			case POP_WPAPI_PAGE_TAGS:
			case POP_WPAPI_PAGE_TRENDINGTAGS:

				$add = 
					($template_id == GD_TEMPLATE_PAGESECTION_PAGE && $target == GD_URLPARAM_TARGET_MAIN) ||
					($template_id == GD_TEMPLATE_PAGESECTION_QUICKVIEWPAGE && $target == GD_URLPARAM_TARGET_QUICKVIEW) ||
					($template_id == GD_TEMPLATE_PAGESECTION_NAVIGATOR && $target == GD_URLPARAM_TARGET_NAVIGATOR) ||
					($template_id == GD_TEMPLATE_PAGESECTION_ADDONS_PAGE && $target == GD_URLPARAM_TARGET_ADDONS);
				if ($add) {
			
					$blocks[] = $gd_template_settingsmanager->get_page_block($page_id);
					break;
				}
				break;


			/*********************************************
			 * About pages
			 *********************************************/
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_SPONSORUS:
			
				if ($template_id == GD_TEMPLATE_PAGESECTION_PAGE) {

					$blocks[] = $gd_template_settingsmanager->get_page_block($page_id);
				}
				break;

			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_OURSPONSORS:

				if ($template_id == GD_TEMPLATE_PAGESECTION_PAGE) {

					$blockgroups[] = $gd_template_settingsmanager->get_page_blockgroup($page_id);
				}
				break;

			// Always return something, since these blocks are not included in any offcanvas
			/*********************************************
			 * Add Content pages
			 *********************************************/
			case POPTHEME_WASSUP_PAGE_ADDCONTENT:

			/*********************************************
			 * Others
			 *********************************************/
			case POP_WPAPI_PAGE_COMMENTS:

				if ($template_id == GD_TEMPLATE_PAGESECTION_PAGE) {

					$blocks[] = $gd_template_settingsmanager->get_page_block($page_id);
				}
				break;

			/*********************************************
			 * Initial load
			 *********************************************/
			case POP_COREPROCESSORS_PAGE_LOADERS_INITIALFRAMES:

				if ($template_id == GD_TEMPLATE_PAGESECTION_PAGE) {

					if (PoPTheme_Wassup_Utils::get_addcontent_target() == GD_URLPARAM_TARGET_MAIN) {
						
						$replicable[] = GD_TEMPLATE_BLOCK_WEBPOST_CREATE;
						$replicable[] = GD_TEMPLATE_BLOCK_WEBPOSTLINK_CREATE;
					}
				}
				elseif ($template_id == GD_TEMPLATE_PAGESECTION_ADDONS_PAGE) {

					if (PoPTheme_Wassup_Utils::get_addcontent_target() == GD_URLPARAM_TARGET_ADDONS) {
						
						$replicable[] = GD_TEMPLATE_BLOCK_WEBPOST_CREATE;
						$replicable[] = GD_TEMPLATE_BLOCK_WEBPOSTLINK_CREATE;
					}
					$replicable[] = GD_TEMPLATE_BLOCK_HIGHLIGHT_CREATE;
					$blockgroupsreplicable[] = GD_TEMPLATE_BLOCKGROUP_REPLICATEADDCOMMENT;
				}
				elseif ($template_id == GD_TEMPLATE_PAGESECTION_MODALS_PAGE) {

					$replicable[] = GD_TEMPLATE_BLOCK_ADDCONTENTFAQ;
					$replicable[] = GD_TEMPLATE_BLOCK_ACCOUNTFAQ;
					$replicable[] = GD_TEMPLATE_BLOCK_INVITENEWUSERS;
				}
				elseif ($template_id == GD_TEMPLATE_PAGESECTION_HOVER) {

					$replicable[] = GD_TEMPLATE_BLOCK_LOGOUT;
					$replicable[] = GD_TEMPLATE_BLOCK_LOSTPWD;
					$replicable[] = GD_TEMPLATE_BLOCK_LOSTPWDRESET;
					$replicable[] = GD_TEMPLATE_BLOCK_SETTINGS;
					$blockgroupsreplicable[] = GD_TEMPLATE_BLOCKGROUP_LOGIN;
				}
				break;
		}

		// Frames: PageSection ControlGroups
		switch ($page_id) {

			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_SPONSORUS:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_OURSPONSORS:

				if ($template_id == GD_TEMPLATE_PAGESECTION_PAGE && $target == GD_URLPARAM_TARGET_MAIN) {
					
					$frames[] = GD_TEMPLATE_BLOCK_PAGEWITHSIDECONTROL;
				}
				elseif ($template_id == GD_TEMPLATE_PAGESECTION_QUICKVIEWPAGE && $target == GD_URLPARAM_TARGET_QUICKVIEW) {

					$frames[] = GD_TEMPLATE_BLOCK_QUICKVIEWPAGECONTROL;
				}
				break;

			case POP_COREPROCESSORS_PAGE_LOADERS_INITIALFRAMES:

				if ($template_id == GD_TEMPLATE_PAGESECTION_PAGE) {

					$frames[] = GD_TEMPLATE_BLOCK_PAGECONTROL_WEBPOSTLINK_CREATE;
					$frames[] = GD_TEMPLATE_BLOCK_PAGECONTROL_HIGHLIGHT_CREATE;
					$frames[] = GD_TEMPLATE_BLOCK_PAGECONTROL_WEBPOST_CREATE;
				}
				break;

			case POP_WPAPI_PAGE_ALLCONTENT:
			case POP_WPAPI_PAGE_ALLUSERS:
			case POPTHEME_WASSUP_PAGE_HIGHLIGHTS:
			case POPTHEME_WASSUP_PAGE_WEBPOSTS:
			case POPTHEME_WASSUP_PAGE_WEBPOSTLINKS:
			case POP_WPAPI_PAGE_TAGS:
			case POP_WPAPI_PAGE_TRENDINGTAGS:
			case POP_WPAPI_PAGE_MYCONTENT:
			case POPTHEME_WASSUP_PAGE_MYHIGHLIGHTS:
			case POPTHEME_WASSUP_PAGE_MYWEBPOSTS:
			case POPTHEME_WASSUP_PAGE_MYWEBPOSTLINKS:
			case POP_WPAPI_PAGE_SEARCHPOSTS:
			case POP_WPAPI_PAGE_SEARCHUSERS:

				if ($template_id == GD_TEMPLATE_PAGESECTION_PAGE && $target == GD_URLPARAM_TARGET_MAIN) {

					$frames[] = GD_TEMPLATE_BLOCK_PAGEWITHSIDECONTROL;
				}
				elseif ($template_id == GD_TEMPLATE_PAGESECTION_QUICKVIEWPAGE && $target == GD_URLPARAM_TARGET_QUICKVIEW) {

					$frames[] = GD_TEMPLATE_BLOCK_QUICKVIEWPAGECONTROL;//GD_TEMPLATE_BLOCK_QUICKVIEWPAGEWITHSIDECONTROL;
				}
				break;

			case POP_COREPROCESSORS_PAGE_INVITENEWUSERS:
			case POPTHEME_WASSUP_PAGE_ACCOUNTFAQ:
			case POP_WPAPI_PAGE_ADDCOMMENT:
			case POPTHEME_WASSUP_PAGE_ADDCONTENT:
			case POPTHEME_WASSUP_PAGE_ADDCONTENTFAQ:
			case POPTHEME_WASSUP_PAGE_ADDHIGHLIGHT:
			case POPTHEME_WASSUP_PAGE_ADDWEBPOST:
			case POPTHEME_WASSUP_PAGE_ADDWEBPOSTLINK:
			case POP_WPAPI_PAGE_CHANGEPASSWORDPROFILE:
			case POP_WPAPI_PAGE_COMMENTS:
			case POP_COREPROCESSORS_PAGE_DESCRIPTION:
			case POP_COREPROCESSORS_PAGE_DOWNVOTEDBY:
			case POP_WPAPI_PAGE_EDITAVATAR:
			case POPTHEME_WASSUP_PAGE_EDITHIGHLIGHT:
			case POPTHEME_WASSUP_PAGE_EDITWEBPOST:
			case POPTHEME_WASSUP_PAGE_EDITWEBPOSTLINK:
			case POP_WPAPI_PAGE_EDITPROFILE:
			// case POP_COREPROCESSORS_PAGE_FOLLOWERS:
			// case POP_COREPROCESSORS_PAGE_FOLLOWINGUSERS:
			case POP_COREPROCESSORS_PAGE_MAIN:
			case POP_COREPROCESSORS_PAGE_MYPROFILE:
			// case POP_COREPROCESSORS_PAGE_POSTAUTHORS:
			// case POP_COREPROCESSORS_PAGE_RECOMMENDEDBY:
			// case POP_COREPROCESSORS_PAGE_RECOMMENDEDPOSTS:
			// case POP_COREPROCESSORS_PAGE_RELATEDCONTENT:
			case POP_COREPROCESSORS_PAGE_SUMMARY:
			case POP_COREPROCESSORS_PAGE_UPVOTEDBY:
			case POP_COREPROCESSORS_PAGE_MYPREFERENCES:

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
		GD_TemplateManager_Utils::add_blockgroups($ret, $blockgroupsreplicable, GD_TEMPLATEBLOCKSETTINGS_BLOCKGROUPREPLICABLE);
			
		// Add frames only if not fetching data for the block
		if (!$vars['fetching-json-data']) {
			GD_TemplateManager_Utils::add_blocks($ret, $frames, GD_TEMPLATEBLOCKSETTINGS_FRAME);
		}
	}

	function add_pagetab_blockunits(&$ret, $template_id) {

		$vars = GD_TemplateManager_Utils::get_vars();
		$target = $vars['target'];
		
		$blocks = $replicable = array();
		$page_id = GD_TemplateManager_Utils::get_hierarchy_page_id();

		switch ($page_id) {
			
			case POP_WPAPI_PAGE_ADDCOMMENT:
			case POPTHEME_WASSUP_PAGE_ADDWEBPOSTLINK:
			case POPTHEME_WASSUP_PAGE_ADDHIGHLIGHT:
			case POPTHEME_WASSUP_PAGE_ADDWEBPOST:

				$add = 
					($template_id == GD_TEMPLATE_PAGESECTION_PAGETABS_PAGE && $target == GD_URLPARAM_TARGET_MAIN) ||
					($template_id == GD_TEMPLATE_PAGESECTION_ADDONTABS_PAGE && $target == GD_URLPARAM_TARGET_ADDONS);
				if ($add) {

					$tabs = array(
						POP_WPAPI_PAGE_ADDCOMMENT => GD_TEMPLATE_BLOCK_ADDONTABS_ADDCOMMENT,
						POPTHEME_WASSUP_PAGE_ADDWEBPOSTLINK => GD_TEMPLATE_BLOCK_PAGETABS_WEBPOSTLINK_CREATE,
						POPTHEME_WASSUP_PAGE_ADDHIGHLIGHT => GD_TEMPLATE_BLOCK_PAGETABS_HIGHLIGHT_CREATE,
						POPTHEME_WASSUP_PAGE_ADDWEBPOST => GD_TEMPLATE_BLOCK_PAGETABS_WEBPOST_CREATE,
					);
					$blocks[] = $tabs[$page_id];
				}
				break;

			/*********************************************
			 * Initial load
			 *********************************************/
			case POP_COREPROCESSORS_PAGE_LOADERS_INITIALFRAMES:

				if ($template_id == GD_TEMPLATE_PAGESECTION_PAGETABS_PAGE) {

					if (PoPTheme_Wassup_Utils::get_addcontent_target() == GD_URLPARAM_TARGET_MAIN) {
					
						$replicable[] = GD_TEMPLATE_BLOCK_PAGETABS_WEBPOST_CREATE;
						$replicable[] = GD_TEMPLATE_BLOCK_PAGETABS_WEBPOSTLINK_CREATE;
					}
				}
				elseif ($template_id == GD_TEMPLATE_PAGESECTION_ADDONTABS_PAGE) {

					$replicable[] = GD_TEMPLATE_BLOCK_ADDONTABS_ADDCOMMENT;
					$replicable[] = GD_TEMPLATE_BLOCK_PAGETABS_HIGHLIGHT_CREATE;
					
					if (PoPTheme_Wassup_Utils::get_addcontent_target() == GD_URLPARAM_TARGET_ADDONS) {
						
						$replicable[] = GD_TEMPLATE_BLOCK_PAGETABS_WEBPOST_CREATE;
						$replicable[] = GD_TEMPLATE_BLOCK_PAGETABS_WEBPOSTLINK_CREATE;
					}
				}
				break;

			case POP_COREPROCESSORS_PAGE_INVITENEWUSERS:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_SPONSORUS:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_OURSPONSORS:
			case POPTHEME_WASSUP_PAGE_ACCOUNTFAQ:
			case POPTHEME_WASSUP_PAGE_ADDCONTENT:
			case POPTHEME_WASSUP_PAGE_ADDCONTENTFAQ:
			case POP_WPAPI_PAGE_ALLCONTENT:
			case POP_WPAPI_PAGE_ALLUSERS:
			case POP_WPAPI_PAGE_CHANGEPASSWORDPROFILE:
			case POP_WPAPI_PAGE_COMMENTS:
			case POP_COREPROCESSORS_PAGE_DESCRIPTION:
			case POP_COREPROCESSORS_PAGE_DOWNVOTEDBY:
			case POP_WPAPI_PAGE_EDITAVATAR:
			case POPTHEME_WASSUP_PAGE_EDITHIGHLIGHT:
			case POPTHEME_WASSUP_PAGE_EDITWEBPOST:
			case POPTHEME_WASSUP_PAGE_EDITWEBPOSTLINK:
			case POP_WPAPI_PAGE_EDITPROFILE:
			case POP_COREPROCESSORS_PAGE_FOLLOWERS:
			case POP_COREPROCESSORS_PAGE_FOLLOWINGUSERS:
			case POP_COREPROCESSORS_PAGE_SUBSCRIBEDTO:
			case POPTHEME_WASSUP_PAGE_HIGHLIGHTS:
			case POPTHEME_WASSUP_PAGE_WEBPOSTS:
			case POPTHEME_WASSUP_PAGE_WEBPOSTLINKS:
			case POP_COREPROCESSORS_PAGE_MAIN:
			case POP_WPAPI_PAGE_TAGS:
			case POP_WPAPI_PAGE_TRENDINGTAGS:
			case POP_WPAPI_PAGE_MYCONTENT:
			case POPTHEME_WASSUP_PAGE_MYHIGHLIGHTS:
			case POPTHEME_WASSUP_PAGE_MYWEBPOSTS:
			case POPTHEME_WASSUP_PAGE_MYWEBPOSTLINKS:
			case POP_COREPROCESSORS_PAGE_MYPROFILE:
			case POP_COREPROCESSORS_PAGE_POSTAUTHORS:
			case POP_COREPROCESSORS_PAGE_SUBSCRIBERS:
			case POP_COREPROCESSORS_PAGE_RECOMMENDEDBY:
			case POP_COREPROCESSORS_PAGE_RECOMMENDEDPOSTS:
			case POP_COREPROCESSORS_PAGE_RELATEDCONTENT:
			case POP_WPAPI_PAGE_SEARCHPOSTS:
			case POP_WPAPI_PAGE_SEARCHUSERS:
			case POP_COREPROCESSORS_PAGE_SUMMARY:
			case POP_COREPROCESSORS_PAGE_UPVOTEDBY:
			case POP_COREPROCESSORS_PAGE_MYPREFERENCES:

				$add = 
					($template_id == GD_TEMPLATE_PAGESECTION_PAGETABS_PAGE && $target == GD_URLPARAM_TARGET_MAIN) ||
					($template_id == GD_TEMPLATE_PAGESECTION_ADDONTABS_PAGE && $target == GD_URLPARAM_TARGET_ADDONS);
				if ($add) {

					$blocks[] = GD_TEMPLATE_BLOCK_PAGETABS_PAGE;
				}
				break;	
		}

		GD_TemplateManager_Utils::add_blocks($ret, $blocks, GD_TEMPLATEBLOCKSETTINGS_MAIN);
		GD_TemplateManager_Utils::add_blocks($ret, $replicable, GD_TEMPLATEBLOCKSETTINGS_REPLICABLE);
	}

	function add_hometab_blockunits(&$ret, $template_id) {

		$vars = GD_TemplateManager_Utils::get_vars();
		$target = $vars['target'];

		$add = 
			($template_id == GD_TEMPLATE_PAGESECTION_PAGETABS_HOME && $target == GD_URLPARAM_TARGET_MAIN) ||
			($template_id == GD_TEMPLATE_PAGESECTION_ADDONTABS_HOME && $target == GD_URLPARAM_TARGET_ADDONS);
		if ($add) {

			$blocks = array();
			$blocks[] = GD_TEMPLATE_BLOCK_PAGETABS_HOME;
			GD_TemplateManager_Utils::add_blocks($ret, $blocks, GD_TEMPLATEBLOCKSETTINGS_MAIN);
		}
	}

	function add_tagtab_blockunits(&$ret, $template_id) {

		$vars = GD_TemplateManager_Utils::get_vars();
		$target = $vars['target'];

		$add = 
			($template_id == GD_TEMPLATE_PAGESECTION_PAGETABS_TAG && $target == GD_URLPARAM_TARGET_MAIN) ||
			($template_id == GD_TEMPLATE_PAGESECTION_ADDONTABS_TAG && $target == GD_URLPARAM_TARGET_ADDONS);
		if ($add) {

			$blocks = array();
			$blocks[] = GD_TEMPLATE_BLOCK_PAGETABS_TAG;
			GD_TemplateManager_Utils::add_blocks($ret, $blocks, GD_TEMPLATEBLOCKSETTINGS_MAIN);
		}
	}

	function add_singletab_blockunits(&$ret, $template_id) {

		$vars = GD_TemplateManager_Utils::get_vars();
		$target = $vars['target'];

		$add = 
			($template_id == GD_TEMPLATE_PAGESECTION_PAGETABS_SINGLE && $target == GD_URLPARAM_TARGET_MAIN) ||
			($template_id == GD_TEMPLATE_PAGESECTION_ADDONTABS_SINGLE && $target == GD_URLPARAM_TARGET_ADDONS);
		if ($add) {

			$blocks = array();
			$blocks[] = GD_TEMPLATE_BLOCK_PAGETABS_SINGLE;
			GD_TemplateManager_Utils::add_blocks($ret, $blocks, GD_TEMPLATEBLOCKSETTINGS_MAIN);
		}
	}

	function add_authortab_blockunits(&$ret, $template_id) {

		$vars = GD_TemplateManager_Utils::get_vars();
		$target = $vars['target'];

		$add = 
			($template_id == GD_TEMPLATE_PAGESECTION_PAGETABS_AUTHOR && $target == GD_URLPARAM_TARGET_MAIN) ||
			($template_id == GD_TEMPLATE_PAGESECTION_ADDONTABS_AUTHOR && $target == GD_URLPARAM_TARGET_ADDONS);
		if ($add) {

			$blocks = array();
			$blocks[] = GD_TEMPLATE_BLOCK_PAGETABS_AUTHOR;
			GD_TemplateManager_Utils::add_blocks($ret, $blocks, GD_TEMPLATEBLOCKSETTINGS_MAIN);
		}
	}

	function add_errortab_blockunits(&$ret, $template_id) {

		$vars = GD_TemplateManager_Utils::get_vars();
		$target = $vars['target'];

		$add = 
			($template_id == GD_TEMPLATE_PAGESECTION_PAGETABS_404 && $target == GD_URLPARAM_TARGET_MAIN) ||
			($template_id == GD_TEMPLATE_PAGESECTION_ADDONTABS_404 && $target == GD_URLPARAM_TARGET_ADDONS);
		if ($add) {

			$blocks = array();
			$blocks[] = GD_TEMPLATE_BLOCK_PAGETABS_404;
			GD_TemplateManager_Utils::add_blocks($ret, $blocks, GD_TEMPLATEBLOCKSETTINGS_MAIN);
		}
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_PageSectionSettingsProcessor();
