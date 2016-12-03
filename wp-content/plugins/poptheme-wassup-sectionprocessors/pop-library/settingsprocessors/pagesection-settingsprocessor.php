<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_Wassup_SectionProcessors_PageSectionSettingsProcessor extends Wassup_PageSectionSettingsProcessorBase {

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
				POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_LOCATIONPOSTS => GD_TEMPLATE_BLOCKGROUP_AUTHORLOCATIONPOSTS_SIDEBAR,
				POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_STORIES => GD_TEMPLATE_BLOCKGROUP_AUTHORSTORIES_SIDEBAR,
				POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ANNOUNCEMENTS => GD_TEMPLATE_BLOCKGROUP_AUTHORANNOUNCEMENTS_SIDEBAR,
				POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_DISCUSSIONS => GD_TEMPLATE_BLOCKGROUP_AUTHORDISCUSSIONS_SIDEBAR,
				POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_FEATURED => GD_TEMPLATE_BLOCKGROUP_AUTHORFEATURED_SIDEBAR,
			);
			if ($sidebar = $page_sidebars[$page_id]) {
				$blockgroups[] = $sidebar;
			}

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

	function add_sideinfo_tag_blockunits(&$ret, $template_id) {

		$vars = GD_TemplateManager_Utils::get_vars();
		$target = $vars['target'];
		$add = 
			($template_id == GD_TEMPLATE_PAGESECTION_SIDEINFO_TAG && $target == GD_URLPARAM_TARGET_MAIN)/* ||
			($template_id == GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWTAG && $target == GD_URLPARAM_TARGET_QUICKVIEW)*/;
		if ($add) {

			$blockgroups = $frames = array();
			$page_id = GD_TemplateManager_Utils::get_hierarchy_page_id();
			$page_sidebars = array(
				POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_LOCATIONPOSTS => GD_TEMPLATE_BLOCKGROUP_TAG_LOCATIONPOSTS_SIDEBAR,
				POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_STORIES => GD_TEMPLATE_BLOCKGROUP_TAG_STORIES_SIDEBAR,
				POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ANNOUNCEMENTS => GD_TEMPLATE_BLOCKGROUP_TAG_ANNOUNCEMENTS_SIDEBAR,
				POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_DISCUSSIONS => GD_TEMPLATE_BLOCKGROUP_TAG_DISCUSSIONS_SIDEBAR,
				POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_FEATURED => GD_TEMPLATE_BLOCKGROUP_TAG_FEATURED_SIDEBAR,
			);
			if ($sidebar = $page_sidebars[$page_id]) {
				$blockgroups[] = $sidebar;
			}

			// Frames: PageSection ControlGroups
			if ($template_id == GD_TEMPLATE_PAGESECTION_SIDEINFO_TAG && $target == GD_URLPARAM_TARGET_MAIN) {

				$frames[] = GD_TEMPLATE_BLOCK_PAGECONTROL_TAGSIDEBAR;
			}

			GD_TemplateManager_Utils::add_blockgroups($ret, $blockgroups, GD_TEMPLATEBLOCKSETTINGS_BLOCKGROUP);

			// Add frames only if not fetching data for the block
			if (!$vars['fetching-json-data']) {

				GD_TemplateManager_Utils::add_blocks($ret, $frames, GD_TEMPLATEBLOCKSETTINGS_FRAME);
			}
		}
	}

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

				// For the Single Related: add the specific sidebar, which includes a delegator filter for the corresponding content
				$sidebar = null;
				$cat = gd_get_the_main_category();
				if ($page_id = GD_TemplateManager_Utils::get_hierarchy_page_id()) {
					switch ($page_id) {

						case POPTHEME_WASSUP_PAGE_HIGHLIGHTS:
					
							$sidebars = array(
								POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_LOCATIONPOSTS => GD_TEMPLATE_BLOCKGROUP_SINGLE_LOCATIONPOST_HIGHLIGHTSSIDEBAR,
								POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_STORIES => GD_TEMPLATE_BLOCKGROUP_SINGLE_STORY_HIGHLIGHTSSIDEBAR,
								POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_ANNOUNCEMENTS => GD_TEMPLATE_BLOCKGROUP_SINGLE_ANNOUNCEMENT_HIGHLIGHTSSIDEBAR,
								POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_DISCUSSIONS => GD_TEMPLATE_BLOCKGROUP_SINGLE_DISCUSSION_HIGHLIGHTSSIDEBAR,
								POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_FEATURED => GD_TEMPLATE_BLOCKGROUP_SINGLE_FEATURED_HIGHLIGHTSSIDEBAR,
							);
							$sidebar = $sidebars[$cat];
							break;

						case POP_COREPROCESSORS_PAGE_RELATEDCONTENT:
					
							$sidebars = array(
								POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_LOCATIONPOSTS => GD_TEMPLATE_BLOCKGROUP_SINGLE_LOCATIONPOST_RELATEDCONTENTSIDEBAR,
								POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_STORIES => GD_TEMPLATE_BLOCKGROUP_SINGLE_STORY_RELATEDCONTENTSIDEBAR,
								POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_ANNOUNCEMENTS => GD_TEMPLATE_BLOCKGROUP_SINGLE_ANNOUNCEMENT_RELATEDCONTENTSIDEBAR,
								POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_DISCUSSIONS => GD_TEMPLATE_BLOCKGROUP_SINGLE_DISCUSSION_RELATEDCONTENTSIDEBAR,
								POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_FEATURED => GD_TEMPLATE_BLOCKGROUP_SINGLE_FEATURED_RELATEDCONTENTSIDEBAR,
							);
							$sidebar = $sidebars[$cat];
							break;

						// case POP_COREPROCESSORS_PAGE_POSTAUTHORS:
					
						// 	$sidebars = array(
						// 		POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_LOCATIONPOSTS => GD_TEMPLATE_BLOCKGROUP_SINGLE_LOCATIONPOST_POSTAUTHORSSIDEBAR,
						// 		POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_STORIES => GD_TEMPLATE_BLOCKGROUP_SINGLE_STORY_POSTAUTHORSSIDEBAR,
						// 		POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_ANNOUNCEMENTS => GD_TEMPLATE_BLOCKGROUP_SINGLE_ANNOUNCEMENT_POSTAUTHORSSIDEBAR,
						// 		POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_DISCUSSIONS => GD_TEMPLATE_BLOCKGROUP_SINGLE_DISCUSSION_POSTAUTHORSSIDEBAR,
						// 		POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_FEATURED => GD_TEMPLATE_BLOCKGROUP_SINGLE_FEATURED_POSTAUTHORSSIDEBAR,
						// 	);
						// 	$sidebar = $sidebars[$cat];
						// 	break;

						case POP_COREPROCESSORS_PAGE_RECOMMENDEDBY:
					
							$sidebars = array(
								POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_LOCATIONPOSTS => GD_TEMPLATE_BLOCKGROUP_SINGLE_LOCATIONPOST_RECOMMENDEDBYSIDEBAR,
								POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_STORIES => GD_TEMPLATE_BLOCKGROUP_SINGLE_STORY_RECOMMENDEDBYSIDEBAR,
								POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_ANNOUNCEMENTS => GD_TEMPLATE_BLOCKGROUP_SINGLE_ANNOUNCEMENT_RECOMMENDEDBYSIDEBAR,
								POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_DISCUSSIONS => GD_TEMPLATE_BLOCKGROUP_SINGLE_DISCUSSION_RECOMMENDEDBYSIDEBAR,
								POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_FEATURED => GD_TEMPLATE_BLOCKGROUP_SINGLE_FEATURED_RECOMMENDEDBYSIDEBAR,
							);
							$sidebar = $sidebars[$cat];
							break;
					}
				}
				if (!$sidebar) {

					// If there is no tab, it must be the Single Post description
					$cats_blockgroup = array(
						POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_LOCATIONPOSTS => GD_TEMPLATE_BLOCKGROUP_SINGLE_LOCATIONPOST_SIDEBAR,
						POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_STORIES => GD_TEMPLATE_BLOCKGROUP_SINGLE_STORY_SIDEBAR,
						POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_ANNOUNCEMENTS => GD_TEMPLATE_BLOCKGROUP_SINGLE_ANNOUNCEMENT_SIDEBAR,
						POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_DISCUSSIONS => GD_TEMPLATE_BLOCKGROUP_SINGLE_DISCUSSION_SIDEBAR,
						POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_FEATURED => GD_TEMPLATE_BLOCKGROUP_SINGLE_FEATURED_SIDEBAR,
						// Also adding the Blog, which has no tabs
						POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_BLOG => GD_TEMPLATE_BLOCKGROUP_SINGLE_BLOG_SIDEBAR,
					);
					$sidebar = $cats_blockgroup[$cat];
				}
				if ($sidebar) {

					$blockgroups[] = $sidebar;
					$frames[] = GD_TEMPLATE_BLOCK_PAGECONTROL_SINGLESIDEBAR;
				}
			}

			GD_TemplateManager_Utils::add_blockgroups($ret, $blockgroups, GD_TEMPLATEBLOCKSETTINGS_BLOCKGROUP);
				
			// Add frames only if not fetching data for the block
			if (!$vars['fetching-json-data']) {
				GD_TemplateManager_Utils::add_blocks($ret, $frames, GD_TEMPLATEBLOCKSETTINGS_FRAME);
			}
		}
	}

	function add_sideinfo_page_blockunits(&$ret, $template_id) {

		$vars = GD_TemplateManager_Utils::get_vars();
		$target = $vars['target'];
		$add = 
			($template_id == GD_TEMPLATE_PAGESECTION_SIDEINFO_PAGE && $target == GD_URLPARAM_TARGET_MAIN) ||
			($template_id == GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWPAGE && $target == GD_URLPARAM_TARGET_QUICKVIEW);
		if ($add) {

			$blocks = $replicable = $frames = array();
			$page_id = GD_TemplateManager_Utils::get_hierarchy_page_id();
			switch ($page_id) {
				
				/*********************************************
				 * About
				 *********************************************/
				case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_HOWTOUSEWEBSITE:
				case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_CONTENTGUIDELINES:
				case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_OURMISSION:
				case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_WHOWEARE:
				case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_OURSTORY:
				case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_VOLUNTEERWITHUS:
				case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_INTHEMEDIA:

					$blocks[] = GD_TEMPLATE_BLOCK_MENU_SIDEBAR_ABOUT;
					break;

				case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_LOCATIONPOSTS:

					$blockgroups[] = GD_TEMPLATE_BLOCKGROUP_SECTION_LOCATIONPOSTS_SIDEBAR;
					break;

				case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_STORIES:

					$blockgroups[] = GD_TEMPLATE_BLOCKGROUP_SECTION_STORIES_SIDEBAR;
					break;

				case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ANNOUNCEMENTS:

					$blockgroups[] = GD_TEMPLATE_BLOCKGROUP_SECTION_ANNOUNCEMENTS_SIDEBAR;
					break;

				case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_DISCUSSIONS:

					$blockgroups[] = GD_TEMPLATE_BLOCKGROUP_SECTION_DISCUSSIONS_SIDEBAR;
					break;

				case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_FEATURED:

					$blockgroups[] = GD_TEMPLATE_BLOCKGROUP_SECTION_FEATURED_SIDEBAR;
					break;

				case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_BLOG:

					$blockgroups[] = GD_TEMPLATE_BLOCKGROUP_SECTION_BLOG_SIDEBAR;
					break;

				case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_MYLOCATIONPOSTS:

					$blockgroups[] = GD_TEMPLATE_BLOCKGROUP_SECTION_MYLOCATIONPOSTS_SIDEBAR;
					break;

				case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_MYSTORIES:

					$blockgroups[] = GD_TEMPLATE_BLOCKGROUP_SECTION_MYSTORIES_SIDEBAR;
					break;

				case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_MYDISCUSSIONS:

					$blockgroups[] = GD_TEMPLATE_BLOCKGROUP_SECTION_MYDISCUSSIONS_SIDEBAR;
					break;

				case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_MYANNOUNCEMENTS:

					$blockgroups[] = GD_TEMPLATE_BLOCKGROUP_SECTION_MYANNOUNCEMENTS_SIDEBAR;
					break;

				// case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_RESOURCES:
				case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_THOUGHTS:
				case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT:
				case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDLOCATIONPOST:
				case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_EDITLOCATIONPOST:
				case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDLOCATIONPOSTLINK:
				case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_EDITLOCATIONPOSTLINK:
				case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDSTORY:
				case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_EDITSTORY:
				case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDSTORYLINK:
				case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_EDITSTORYLINK:
				case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDDISCUSSION:
				case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_EDITDISCUSSION:
				case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDDISCUSSIONLINK:
				case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_EDITDISCUSSIONLINK:
				case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDFEATURED:
				case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_EDITFEATURED:
				case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDANNOUNCEMENT:
				case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_EDITANNOUNCEMENT:
				case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDANNOUNCEMENTLINK:
				case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_EDITANNOUNCEMENTLINK:
				// case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_MYRESOURCES:

					$blocks[] = GD_TEMPLATE_BLOCK_EMPTYSIDEINFO;
					break;	

				/*********************************************
				 * Initial load
				 *********************************************/
				case POP_COREPROCESSORS_PAGE_LOADERS_INITIALFRAMES:

					if ($template_id == GD_TEMPLATE_PAGESECTION_SIDEINFO_PAGE) {

						$replicable[] = GD_TEMPLATE_BLOCK_EMPTYSIDEINFO_LOCATIONPOST_CREATE;
						$replicable[] = GD_TEMPLATE_BLOCK_EMPTYSIDEINFO_LOCATIONPOSTLINK_CREATE;
						$replicable[] = GD_TEMPLATE_BLOCK_EMPTYSIDEINFO_STORY_CREATE;
						$replicable[] = GD_TEMPLATE_BLOCK_EMPTYSIDEINFO_STORYLINK_CREATE;
						$replicable[] = GD_TEMPLATE_BLOCK_EMPTYSIDEINFO_ANNOUNCEMENT_CREATE;
						$replicable[] = GD_TEMPLATE_BLOCK_EMPTYSIDEINFO_ANNOUNCEMENTLINK_CREATE;
						$replicable[] = GD_TEMPLATE_BLOCK_EMPTYSIDEINFO_DISCUSSION_CREATE;
						$replicable[] = GD_TEMPLATE_BLOCK_EMPTYSIDEINFO_DISCUSSIONLINK_CREATE;
					}
					break;	
			}

			// Frames
			switch ($page_id) {
				
				case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_HOWTOUSEWEBSITE:
				case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_CONTENTGUIDELINES:
				case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_OURMISSION:
				case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_WHOWEARE:
				case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_OURSTORY:
				case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_VOLUNTEERWITHUS:
				case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_INTHEMEDIA:
				case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_LOCATIONPOSTS:
				case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_STORIES:
				case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ANNOUNCEMENTS:
				case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_DISCUSSIONS:
				case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_FEATURED:
				case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_BLOG:
				case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_MYLOCATIONPOSTS:
				case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_MYSTORIES:
				case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_MYDISCUSSIONS:
				case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_MYANNOUNCEMENTS:

					$frames[] = GD_TEMPLATE_BLOCK_PAGECONTROL_PAGESIDEBAR;
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
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_LOCATIONPOSTS:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_STORIES:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ANNOUNCEMENTS:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_DISCUSSIONS:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_FEATURED:

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
			 * Add/Edit Content
			 *********************************************/
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDLOCATIONPOST:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_EDITLOCATIONPOST:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDLOCATIONPOSTLINK:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_EDITLOCATIONPOSTLINK:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDSTORY:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_EDITSTORY:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDSTORYLINK:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_EDITSTORYLINK:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDANNOUNCEMENT:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_EDITANNOUNCEMENT:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDANNOUNCEMENTLINK:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_EDITANNOUNCEMENTLINK:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDDISCUSSION:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_EDITDISCUSSION:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDDISCUSSIONLINK:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_EDITDISCUSSIONLINK:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDFEATURED:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_EDITFEATURED:

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
			 * MAIN
			 *-------------------------------------------*/
			/*********************************************
			 * My Content
			 *********************************************/
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_MYLOCATIONPOSTS:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_MYSTORIES:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_MYANNOUNCEMENTS:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_MYDISCUSSIONS:

				if ($template_id == GD_TEMPLATE_PAGESECTION_PAGE) {

					if ($fetching_json_data) {

						$blocks[] = $gd_template_settingsmanager->get_page_block($page_id);
						break;
					}

					// Allow the ThemeStyle to decide if to include block (eg: Swift) or blockgroup (eg: Expansive)
					$type = apply_filters(POP_HOOK_SETTINGSPROCESSORS_BLOCKTYPE_TABLE, POP_BLOCKTYPE_SETTINGSPROCESSORS_BLOCK);
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
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_LOCATIONPOSTS:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_STORIES:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ANNOUNCEMENTS:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_DISCUSSIONS:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_FEATURED:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_BLOG:

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

			/*********************************************
			 * About pages
			 *********************************************/
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_HOWTOUSEWEBSITE:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_CONTENTGUIDELINES:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_OURMISSION:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_OURSTORY:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_VOLUNTEERWITHUS:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_INTHEMEDIA:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_THOUGHTS:
			
				$add = 
					($template_id == GD_TEMPLATE_PAGESECTION_PAGE && $target == GD_URLPARAM_TARGET_MAIN) ||
					($template_id == GD_TEMPLATE_PAGESECTION_QUICKVIEWPAGE && $target == GD_URLPARAM_TARGET_QUICKVIEW);
				if ($add) {

					$blocks[] = $gd_template_settingsmanager->get_page_block($page_id);
				}
				break;

			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_WHOWEARE:

				$add = 
					($template_id == GD_TEMPLATE_PAGESECTION_PAGE && $target == GD_URLPARAM_TARGET_MAIN) ||
					($template_id == GD_TEMPLATE_PAGESECTION_QUICKVIEWPAGE && $target == GD_URLPARAM_TARGET_QUICKVIEW);
				if ($add) {

					$blockgroups[] = $gd_template_settingsmanager->get_page_blockgroup($page_id);
				}
				break;

			/*********************************************
			 * Initial load
			 *********************************************/
			case POP_COREPROCESSORS_PAGE_LOADERS_INITIALFRAMES:

				if ($template_id == GD_TEMPLATE_PAGESECTION_PAGE) {

					if (PoPTheme_Wassup_Utils::get_addcontent_target() == GD_URLPARAM_TARGET_MAIN) {
						
						$replicable[] = GD_TEMPLATE_BLOCK_LOCATIONPOST_CREATE;
						$replicable[] = GD_TEMPLATE_BLOCK_LOCATIONPOSTLINK_CREATE;
						$replicable[] = GD_TEMPLATE_BLOCK_STORY_CREATE;
						$replicable[] = GD_TEMPLATE_BLOCK_STORYLINK_CREATE;
						$replicable[] = GD_TEMPLATE_BLOCK_ANNOUNCEMENT_CREATE;
						$replicable[] = GD_TEMPLATE_BLOCK_ANNOUNCEMENTLINK_CREATE;
						$replicable[] = GD_TEMPLATE_BLOCK_DISCUSSION_CREATE;
						$replicable[] = GD_TEMPLATE_BLOCK_DISCUSSIONLINK_CREATE;
					}
				}
				elseif ($template_id == GD_TEMPLATE_PAGESECTION_ADDONS_PAGE) {

					if (PoPTheme_Wassup_Utils::get_addcontent_target() == GD_URLPARAM_TARGET_ADDONS) {
						
						$replicable[] = GD_TEMPLATE_BLOCK_LOCATIONPOST_CREATE;
						$replicable[] = GD_TEMPLATE_BLOCK_LOCATIONPOSTLINK_CREATE;
						$replicable[] = GD_TEMPLATE_BLOCK_STORY_CREATE;
						$replicable[] = GD_TEMPLATE_BLOCK_STORYLINK_CREATE;
						$replicable[] = GD_TEMPLATE_BLOCK_ANNOUNCEMENT_CREATE;
						$replicable[] = GD_TEMPLATE_BLOCK_ANNOUNCEMENTLINK_CREATE;
						$replicable[] = GD_TEMPLATE_BLOCK_DISCUSSION_CREATE;
						$replicable[] = GD_TEMPLATE_BLOCK_DISCUSSIONLINK_CREATE;
					}
				}
				break;
		}

		// Frames: PageSection ControlGroups
		switch ($page_id) {

			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_HOWTOUSEWEBSITE:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_CONTENTGUIDELINES:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_OURMISSION:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_WHOWEARE:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_OURSTORY:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_VOLUNTEERWITHUS:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_INTHEMEDIA:

				if ($template_id == GD_TEMPLATE_PAGESECTION_PAGE && $target == GD_URLPARAM_TARGET_MAIN) {
					
					$frames[] = GD_TEMPLATE_BLOCK_PAGEWITHSIDECONTROL;
				}
				elseif ($template_id == GD_TEMPLATE_PAGESECTION_QUICKVIEWPAGE && $target == GD_URLPARAM_TARGET_QUICKVIEW) {

					$frames[] = GD_TEMPLATE_BLOCK_QUICKVIEWPAGECONTROL;
				}
				break;

			case POP_COREPROCESSORS_PAGE_LOADERS_INITIALFRAMES:

				if ($template_id == GD_TEMPLATE_PAGESECTION_PAGE) {

					$frames[] = GD_TEMPLATE_BLOCK_PAGECONTROL_LOCATIONPOST_CREATE;
					$frames[] = GD_TEMPLATE_BLOCK_PAGECONTROL_LOCATIONPOSTLINK_CREATE;
					$frames[] = GD_TEMPLATE_BLOCK_PAGECONTROL_STORY_CREATE;
					$frames[] = GD_TEMPLATE_BLOCK_PAGECONTROL_STORYLINK_CREATE;
					$frames[] = GD_TEMPLATE_BLOCK_PAGECONTROL_ANNOUNCEMENT_CREATE;
					$frames[] = GD_TEMPLATE_BLOCK_PAGECONTROL_ANNOUNCEMENTLINK_CREATE;
					$frames[] = GD_TEMPLATE_BLOCK_PAGECONTROL_DISCUSSION_CREATE;
					$frames[] = GD_TEMPLATE_BLOCK_PAGECONTROL_DISCUSSIONLINK_CREATE;
				}
				break;

			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_LOCATIONPOSTS:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_STORIES:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ANNOUNCEMENTS:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_DISCUSSIONS:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_FEATURED:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_BLOG:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_MYLOCATIONPOSTS:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_MYSTORIES:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_MYDISCUSSIONS:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_MYANNOUNCEMENTS:

				if ($template_id == GD_TEMPLATE_PAGESECTION_PAGE && $target == GD_URLPARAM_TARGET_MAIN) {

					$frames[] = GD_TEMPLATE_BLOCK_PAGEWITHSIDECONTROL;
				}
				elseif ($template_id == GD_TEMPLATE_PAGESECTION_QUICKVIEWPAGE && $target == GD_URLPARAM_TARGET_QUICKVIEW) {

					$frames[] = GD_TEMPLATE_BLOCK_QUICKVIEWPAGECONTROL;//GD_TEMPLATE_BLOCK_QUICKVIEWPAGEWITHSIDECONTROL;
				}
				break;

			// case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_RESOURCES:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_THOUGHTS:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDLOCATIONPOST:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_EDITLOCATIONPOST:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDLOCATIONPOSTLINK:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_EDITLOCATIONPOSTLINK:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDSTORY:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_EDITSTORY:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDSTORYLINK:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_EDITSTORYLINK:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDDISCUSSION:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_EDITDISCUSSION:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDDISCUSSIONLINK:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_EDITDISCUSSIONLINK:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDFEATURED:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_EDITFEATURED:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDANNOUNCEMENT:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_EDITANNOUNCEMENT:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDANNOUNCEMENTLINK:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_EDITANNOUNCEMENTLINK:
			// case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_MYRESOURCES:

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

	function add_tag_blockunits(&$ret, $template_id) {

		global $gd_template_settingsmanager;

		$vars = GD_TemplateManager_Utils::get_vars();
		$target = $vars['target'];
		$fetching_json = $vars['fetching-json'];
		$fetching_json_data = $vars['fetching-json-data'];
		$submitted_data = $fetching_json_data && doing_post();

		$page_id = GD_TemplateManager_Utils::get_hierarchy_page_id();
		if (!$page_id) return;
		
		$blocks = $blockgroups = $frames = array();

		switch ($page_id) {
			
			/*********************************************
			 * Sections
			 *********************************************/
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_LOCATIONPOSTS:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_STORIES:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ANNOUNCEMENTS:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_DISCUSSIONS:
			// case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_FEATURED:
			// case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_BLOG:

				$add = 
					($template_id == GD_TEMPLATE_PAGESECTION_TAG && $target == GD_URLPARAM_TARGET_MAIN) ||
					($template_id == GD_TEMPLATE_PAGESECTION_QUICKVIEWTAG && $target == GD_URLPARAM_TARGET_QUICKVIEW) ||
					($template_id == GD_TEMPLATE_PAGESECTION_ADDONS_TAG && $target == GD_URLPARAM_TARGET_ADDONS) ||
					($template_id == GD_TEMPLATE_PAGESECTION_MODALS_TAG && $target == GD_URLPARAM_TARGET_MODALS);
				if ($add) {

					if ($fetching_json_data) {

						$blocks[] = $gd_template_settingsmanager->get_page_block($page_id, GD_SETTINGS_HIERARCHY_TAG);
						break;
					}

					// Allow the ThemeStyle to decide if to include block (eg: Swift) or blockgroup (eg: Expansive)
					$type = apply_filters(POP_HOOK_SETTINGSPROCESSORS_BLOCKTYPE_FEED, POP_BLOCKTYPE_SETTINGSPROCESSORS_BLOCK);
					if ($type == POP_BLOCKTYPE_SETTINGSPROCESSORS_BLOCK) {

						$blocks[] = $gd_template_settingsmanager->get_page_block($page_id, GD_SETTINGS_HIERARCHY_TAG);
					}
					elseif ($type == POP_BLOCKTYPE_SETTINGSPROCESSORS_BLOCKGROUP) {
					
						$blockgroups[] = $gd_template_settingsmanager->get_page_blockgroup($page_id, GD_SETTINGS_HIERARCHY_TAG);	
					}
				}
				break;
		}

		GD_TemplateManager_Utils::add_blocks($ret, $blocks, GD_TEMPLATEBLOCKSETTINGS_MAIN);
		GD_TemplateManager_Utils::add_blockgroups($ret, $blockgroups, GD_TEMPLATEBLOCKSETTINGS_BLOCKGROUP);

		switch ($page_id) {
			
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_LOCATIONPOSTS:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_STORIES:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ANNOUNCEMENTS:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_DISCUSSIONS:

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

	function add_pagetab_blockunits(&$ret, $template_id) {

		$vars = GD_TemplateManager_Utils::get_vars();
		$target = $vars['target'];
		
		$blocks = $replicable = array();
		$page_id = GD_TemplateManager_Utils::get_hierarchy_page_id();

		switch ($page_id) {

			/*********************************************
			 * Initial load
			 *********************************************/
			case POP_COREPROCESSORS_PAGE_LOADERS_INITIALFRAMES:

				if ($template_id == GD_TEMPLATE_PAGESECTION_PAGETABS_PAGE) {

					if (PoPTheme_Wassup_Utils::get_addcontent_target() == GD_URLPARAM_TARGET_MAIN) {
						
						$replicable[] = GD_TEMPLATE_BLOCK_PAGETABS_LOCATIONPOST_CREATE;
						$replicable[] = GD_TEMPLATE_BLOCK_PAGETABS_LOCATIONPOSTLINK_CREATE;
						$replicable[] = GD_TEMPLATE_BLOCK_PAGETABS_STORY_CREATE;
						$replicable[] = GD_TEMPLATE_BLOCK_PAGETABS_STORYLINK_CREATE;
						$replicable[] = GD_TEMPLATE_BLOCK_PAGETABS_ANNOUNCEMENT_CREATE;
						$replicable[] = GD_TEMPLATE_BLOCK_PAGETABS_ANNOUNCEMENTLINK_CREATE;
						$replicable[] = GD_TEMPLATE_BLOCK_PAGETABS_DISCUSSION_CREATE;
						$replicable[] = GD_TEMPLATE_BLOCK_PAGETABS_DISCUSSIONLINK_CREATE;
					}
				}
				elseif ($template_id == GD_TEMPLATE_PAGESECTION_ADDONTABS_PAGE) {

					if (PoPTheme_Wassup_Utils::get_addcontent_target() == GD_URLPARAM_TARGET_ADDONS) {
						
						$replicable[] = GD_TEMPLATE_BLOCK_PAGETABS_LOCATIONPOST_CREATE;
						$replicable[] = GD_TEMPLATE_BLOCK_PAGETABS_LOCATIONPOSTLINK_CREATE;
						$replicable[] = GD_TEMPLATE_BLOCK_PAGETABS_STORY_CREATE;
						$replicable[] = GD_TEMPLATE_BLOCK_PAGETABS_STORYLINK_CREATE;
						$replicable[] = GD_TEMPLATE_BLOCK_PAGETABS_ANNOUNCEMENT_CREATE;
						$replicable[] = GD_TEMPLATE_BLOCK_PAGETABS_ANNOUNCEMENTLINK_CREATE;
						$replicable[] = GD_TEMPLATE_BLOCK_PAGETABS_DISCUSSION_CREATE;
						$replicable[] = GD_TEMPLATE_BLOCK_PAGETABS_DISCUSSIONLINK_CREATE;
					}
				}
				break;

			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_LOCATIONPOSTS:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_STORIES:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ANNOUNCEMENTS:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_DISCUSSIONS:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_FEATURED:
			// case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_RESOURCES:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_THOUGHTS:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_BLOG:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_HOWTOUSEWEBSITE:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_CONTENTGUIDELINES:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_OURMISSION:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_WHOWEARE:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_OURSTORY:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_VOLUNTEERWITHUS:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_INTHEMEDIA:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_MYLOCATIONPOSTS:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDLOCATIONPOST:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_EDITLOCATIONPOST:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDLOCATIONPOSTLINK:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_EDITLOCATIONPOSTLINK:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_MYSTORIES:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDSTORY:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_EDITSTORY:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDSTORYLINK:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_EDITSTORYLINK:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_MYDISCUSSIONS:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDDISCUSSION:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_EDITDISCUSSION:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDDISCUSSIONLINK:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_EDITDISCUSSIONLINK:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDFEATURED:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_EDITFEATURED:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_MYANNOUNCEMENTS:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDANNOUNCEMENT:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_EDITANNOUNCEMENT:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDANNOUNCEMENTLINK:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_EDITANNOUNCEMENTLINK:
			// case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_MYRESOURCES:

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
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_SectionProcessors_PageSectionSettingsProcessor();
