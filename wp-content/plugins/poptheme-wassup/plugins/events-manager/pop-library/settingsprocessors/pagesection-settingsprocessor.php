<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class Wassup_EM_PageSectionSettingsProcessor extends Wassup_PageSectionSettingsProcessorBase {

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
				POPTHEME_WASSUP_EM_PAGE_EVENTS => GD_TEMPLATE_BLOCKGROUP_AUTHOREVENTS_SIDEBAR,
				POPTHEME_WASSUP_EM_PAGE_PASTEVENTS => GD_TEMPLATE_BLOCKGROUP_AUTHORPASTEVENTS_SIDEBAR,
				POPTHEME_WASSUP_EM_PAGE_EVENTSCALENDAR => GD_TEMPLATE_BLOCKGROUP_AUTHOREVENTSCALENDAR_SIDEBAR,
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
				POPTHEME_WASSUP_EM_PAGE_EVENTS => GD_TEMPLATE_BLOCKGROUP_TAG_EVENTS_SIDEBAR,
				POPTHEME_WASSUP_EM_PAGE_PASTEVENTS => GD_TEMPLATE_BLOCKGROUP_TAG_PASTEVENTS_SIDEBAR,
				POPTHEME_WASSUP_EM_PAGE_EVENTSCALENDAR => GD_TEMPLATE_BLOCKGROUP_TAG_EVENTS_CALENDAR_SIDEBAR,
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
			if ($post_type == EM_POST_TYPE_EVENT) {

				// For the Single Related: add the specific sidebar, which includes a delegator filter for the corresponding content
				$page_id = GD_TemplateManager_Utils::get_hierarchy_page_id();					
				if (gd_em_single_event_is_future()) {

					$sidebars = array(
						POPTHEME_WASSUP_PAGE_HIGHLIGHTS => GD_TEMPLATE_BLOCKGROUP_SINGLE_EVENT_HIGHLIGHTSSIDEBAR,
						POP_COREPROCESSORS_PAGE_RELATEDCONTENT => GD_TEMPLATE_BLOCKGROUP_SINGLE_EVENT_RELATEDCONTENTSIDEBAR,
						POP_COREPROCESSORS_PAGE_POSTAUTHORS => GD_TEMPLATE_BLOCKGROUP_SINGLE_EVENT_POSTAUTHORSSIDEBAR,
						POP_COREPROCESSORS_PAGE_RECOMMENDEDBY => GD_TEMPLATE_BLOCKGROUP_SINGLE_EVENT_RECOMMENDEDBYSIDEBAR,
					);
					if ($sidebar = $sidebars[$page_id]) {
						$blockgroups[] = $sidebar;
					}
					else {
						$blockgroups[] = GD_TEMPLATE_BLOCKGROUP_SINGLE_EVENT_SIDEBAR;
					}
				}
				else {

					$sidebars = array(
						POPTHEME_WASSUP_PAGE_HIGHLIGHTS => GD_TEMPLATE_BLOCKGROUP_SINGLE_PASTEVENT_HIGHLIGHTSSIDEBAR,
						POP_COREPROCESSORS_PAGE_RELATEDCONTENT => GD_TEMPLATE_BLOCKGROUP_SINGLE_PASTEVENT_RELATEDCONTENTSIDEBAR,
						POP_COREPROCESSORS_PAGE_POSTAUTHORS => GD_TEMPLATE_BLOCKGROUP_SINGLE_PASTEVENT_POSTAUTHORSSIDEBAR,
						POP_COREPROCESSORS_PAGE_RECOMMENDEDBY => GD_TEMPLATE_BLOCKGROUP_SINGLE_PASTEVENT_RECOMMENDEDBYSIDEBAR,
					);
					if ($sidebar = $sidebars[$page_id]) {
						$blockgroups[] = $sidebar;
					}
					else {
						$blockgroups[] = GD_TEMPLATE_BLOCKGROUP_SINGLE_PASTEVENT_SIDEBAR;
					}
				}
				$frames[] = GD_TEMPLATE_BLOCK_PAGECONTROL_SINGLESIDEBAR;
			}

			GD_TemplateManager_Utils::add_blockgroups($ret, $blockgroups, GD_TEMPLATEBLOCKSETTINGS_BLOCKGROUP);
				
			// Add frames only if not fetching data for the block
			if (!$vars['fetching-json-data']) {
				GD_TemplateManager_Utils::add_blocks($ret, $frames, GD_TEMPLATEBLOCKSETTINGS_FRAME);
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

			$blocks = $blockgroups = $replicable = $frames = array();
			$page_id = GD_TemplateManager_Utils::get_hierarchy_page_id();
			switch ($page_id) {

				case POPTHEME_WASSUP_EM_PAGE_EVENTS:

					$blockgroups[] = GD_TEMPLATE_BLOCKGROUP_SECTION_EVENTS_SIDEBAR;
					break;

				case POPTHEME_WASSUP_EM_PAGE_EVENTSCALENDAR:

					// For the permanent Sideinfo's Events Calendar
					if ($fetching_json_data) {

						$blocks[] = $gd_template_settingsmanager->get_page_block($page_id);
						break;
					}

					$blockgroups[] = GD_TEMPLATE_BLOCKGROUP_SECTION_EVENTS_CALENDAR_SIDEBAR;
					break;

				case POPTHEME_WASSUP_EM_PAGE_PASTEVENTS:

					$blockgroups[] = GD_TEMPLATE_BLOCKGROUP_SECTION_PASTEVENTS_SIDEBAR;
					break;

				case POPTHEME_WASSUP_EM_PAGE_MYEVENTS:

					$blockgroups[] = GD_TEMPLATE_BLOCKGROUP_SECTION_MYEVENTS_SIDEBAR;
					break;

				case POPTHEME_WASSUP_EM_PAGE_MYPASTEVENTS:

					$blockgroups[] = GD_TEMPLATE_BLOCKGROUP_SECTION_MYPASTEVENTS_SIDEBAR;
					break;

				// case POPTHEME_WASSUP_EM_PAGE_EVENTS:
				// case POPTHEME_WASSUP_EM_PAGE_EVENTSCALENDAR:
				// case POPTHEME_WASSUP_EM_PAGE_PASTEVENTS:
				// case POPTHEME_WASSUP_EM_PAGE_MYEVENTS:
				// case POPTHEME_WASSUP_EM_PAGE_MYPASTEVENTS:
				case POPTHEME_WASSUP_EM_PAGE_ADDEVENT:
				case POPTHEME_WASSUP_EM_PAGE_ADDEVENTLINK:
				case POPTHEME_WASSUP_EM_PAGE_EDITEVENT:
				case POPTHEME_WASSUP_EM_PAGE_EDITEVENTLINK:

					$blocks[] = GD_TEMPLATE_BLOCK_EMPTYSIDEINFO;
					break;
				
				/*********************************************
				 * Initial load
				 *********************************************/
				case POP_COREPROCESSORS_PAGE_LOADERS_INITIALFRAMES:

					if ($template_id == GD_TEMPLATE_PAGESECTION_SIDEINFO_PAGE) {

						$replicable[] = GD_TEMPLATE_BLOCK_EMPTYSIDEINFO_EVENT_CREATE;
						$replicable[] = GD_TEMPLATE_BLOCK_EMPTYSIDEINFO_EVENTLINK_CREATE;
					}
					break;	
			}

			// Frames
			switch ($page_id) {

				case POPTHEME_WASSUP_EM_PAGE_EVENTS:
				case POPTHEME_WASSUP_EM_PAGE_EVENTSCALENDAR:
				case POPTHEME_WASSUP_EM_PAGE_PASTEVENTS:
				case POPTHEME_WASSUP_EM_PAGE_MYEVENTS:
				case POPTHEME_WASSUP_EM_PAGE_MYPASTEVENTS:

					$frames[] = GD_TEMPLATE_BLOCK_PAGECONTROL_PAGESIDEBAR;
					break;
			}

			GD_TemplateManager_Utils::add_blockgroups($ret, $blockgroups, GD_TEMPLATEBLOCKSETTINGS_BLOCKGROUP);
			GD_TemplateManager_Utils::add_blocks($ret, $blocks, GD_TEMPLATEBLOCKSETTINGS_MAIN);
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

		$blocks = $blockgroups = array();

		// If passing parameter 'tab' then deal with the corresponding page
		$page_id = GD_TemplateManager_Utils::get_hierarchy_page_id();
		switch ($page_id) {

			/*********************************************
			 * Sections
			 *********************************************/
			case POPTHEME_WASSUP_EM_PAGE_EVENTS:
			case POPTHEME_WASSUP_EM_PAGE_EVENTSCALENDAR:
			case POPTHEME_WASSUP_EM_PAGE_PASTEVENTS:

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
					$blocktypes = array(
						POPTHEME_WASSUP_EM_PAGE_EVENTS => POP_HOOK_SETTINGSPROCESSORS_BLOCKTYPE_FEED,
						POPTHEME_WASSUP_EM_PAGE_EVENTSCALENDAR => POP_HOOK_SETTINGSPROCESSORS_BLOCKTYPE_CALENDAR,
						POPTHEME_WASSUP_EM_PAGE_PASTEVENTS => POP_HOOK_SETTINGSPROCESSORS_BLOCKTYPE_FEED,
					);

					// Allow the ThemeStyle to decide if to include block (eg: Swift) or blockgroup (eg: Expansive)
					$type = apply_filters($blocktypes[$page_id], POP_BLOCKTYPE_SETTINGSPROCESSORS_BLOCK);
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

	function add_tag_blockunits(&$ret, $template_id) {

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
			case POPTHEME_WASSUP_EM_PAGE_EVENTS:
			case POPTHEME_WASSUP_EM_PAGE_EVENTSCALENDAR:
			case POPTHEME_WASSUP_EM_PAGE_PASTEVENTS:

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
					$blocktypes = array(
						POPTHEME_WASSUP_EM_PAGE_EVENTS => POP_HOOK_SETTINGSPROCESSORS_BLOCKTYPE_FEED,
						POPTHEME_WASSUP_EM_PAGE_EVENTSCALENDAR => POP_HOOK_SETTINGSPROCESSORS_BLOCKTYPE_CALENDAR,
						POPTHEME_WASSUP_EM_PAGE_PASTEVENTS => POP_HOOK_SETTINGSPROCESSORS_BLOCKTYPE_FEED,
					);

					// Allow the ThemeStyle to decide if to include block (eg: Swift) or blockgroup (eg: Expansive)
					$type = apply_filters($blocktypes[$page_id], POP_BLOCKTYPE_SETTINGSPROCESSORS_BLOCK);
					if ($type == POP_BLOCKTYPE_SETTINGSPROCESSORS_BLOCK) {

						$blocks[] = $gd_template_settingsmanager->get_page_block($page_id, GD_SETTINGS_HIERARCHY_TAG);
					}
					elseif ($type == POP_BLOCKTYPE_SETTINGSPROCESSORS_BLOCKGROUP) {
					
						$blockgroups[] = $gd_template_settingsmanager->get_page_blockgroup($page_id, GD_SETTINGS_HIERARCHY_TAG);	
					}
				}
				break;
		}

		GD_TemplateManager_Utils::add_blockgroups($ret, $blockgroups, GD_TEMPLATEBLOCKSETTINGS_BLOCKGROUP);
		GD_TemplateManager_Utils::add_blocks($ret, $blocks, GD_TEMPLATEBLOCKSETTINGS_MAIN);

		switch ($page_id) {

			case POPTHEME_WASSUP_EM_PAGE_EVENTS:
			case POPTHEME_WASSUP_EM_PAGE_EVENTSCALENDAR:
			case POPTHEME_WASSUP_EM_PAGE_PASTEVENTS:

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

	function add_page_blockunits(&$ret, $template_id) {

		global $gd_template_settingsmanager;

		$vars = GD_TemplateManager_Utils::get_vars();
		$target = $vars['target'];
		$fetching_json = $vars['fetching-json'];
		$fetching_json_data = $vars['fetching-json-data'];
		$submitted_data = $fetching_json_data && doing_post();

		$page_id = GD_TemplateManager_Utils::get_hierarchy_page_id();
		if (!$page_id) return;
		
		$blocks = $blockgroups = $replicable = $blockgroupsreplicable = $frames = array();

		switch ($page_id) {

			/*********************************************
			 * Add/Edit Content
			 *********************************************/
			case POPTHEME_WASSUP_EM_PAGE_ADDEVENT:
			case POPTHEME_WASSUP_EM_PAGE_ADDEVENTLINK:
			case POPTHEME_WASSUP_EM_PAGE_EDITEVENT:
			case POPTHEME_WASSUP_EM_PAGE_EDITEVENTLINK:

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
			 * MODALS
			 *-------------------------------------------*/
			case POP_EM_POPPROCESSORS_PAGE_LOCATIONSMAP:
			
				if ($template_id == GD_TEMPLATE_PAGESECTION_PAGE && $target == GD_URLPARAM_TARGET_MAIN) {

					$blocks[] = GD_TEMPLATE_BLOCK_LOCATIONSMAP;
				}
				elseif ($template_id == GD_TEMPLATE_PAGESECTION_MODALS_PAGE && $target == GD_URLPARAM_TARGET_MODALS) {

					$blocks[] = GD_TEMPLATE_BLOCK_STATICLOCATIONSMAP;
				}
				break;
				
			case POP_EM_POPPROCESSORS_PAGE_ADDLOCATION:

				$is_main = ($template_id == GD_TEMPLATE_PAGESECTION_PAGE && $target == GD_URLPARAM_TARGET_MAIN);
				$is_modal = ($template_id == GD_TEMPLATE_PAGESECTION_MODALS_PAGE && $target == GD_URLPARAM_TARGET_MODALS);
				$add = $is_main || $is_modal;
				if ($add) {

					if ($submitted_data) {

						$blocks[] = $gd_template_settingsmanager->get_page_action($page_id);
						$blocks[] = GD_TEMPLATE_BLOCKDATA_CREATELOCATION;
						break;
					}
					if ($is_main) {

						$blocks[] = $gd_template_settingsmanager->get_page_block($page_id);
					}
					elseif ($is_modal) {

						$blockgroups[] = $gd_template_settingsmanager->get_page_blockgroup($page_id);
					}
				}
				break;
			
			/*********************************************
			 * My Content
			 *********************************************/
			case POPTHEME_WASSUP_EM_PAGE_MYEVENTS:
			case POPTHEME_WASSUP_EM_PAGE_MYPASTEVENTS:

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
			case POPTHEME_WASSUP_EM_PAGE_EVENTS:
			case POPTHEME_WASSUP_EM_PAGE_EVENTSCALENDAR:
			case POPTHEME_WASSUP_EM_PAGE_PASTEVENTS:

				$add = 
					($template_id == GD_TEMPLATE_PAGESECTION_PAGE && $target == GD_URLPARAM_TARGET_MAIN) ||
					($template_id == GD_TEMPLATE_PAGESECTION_QUICKVIEWPAGE && $target == GD_URLPARAM_TARGET_QUICKVIEW);
				if ($add) {

					if ($fetching_json_data) {

						$blocks[] = $gd_template_settingsmanager->get_page_block($page_id);
						break;
					}

					$blocktypes = array(
						POPTHEME_WASSUP_EM_PAGE_EVENTS => POP_HOOK_SETTINGSPROCESSORS_BLOCKTYPE_FEED,
						POPTHEME_WASSUP_EM_PAGE_EVENTSCALENDAR => POP_HOOK_SETTINGSPROCESSORS_BLOCKTYPE_CALENDAR,
						POPTHEME_WASSUP_EM_PAGE_PASTEVENTS => POP_HOOK_SETTINGSPROCESSORS_BLOCKTYPE_FEED,
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
				elseif ($template_id == GD_TEMPLATE_PAGESECTION_NAVIGATOR && $target == GD_URLPARAM_TARGET_NAVIGATOR) {

					$blocks[] = $gd_template_settingsmanager->get_page_block($page_id, null, GD_TEMPLATEFORMAT_NAVIGATOR);
				}
				elseif ($template_id == GD_TEMPLATE_PAGESECTION_ADDONS_PAGE && $target == GD_URLPARAM_TARGET_ADDONS) {

					$blocks[] = $gd_template_settingsmanager->get_page_block($page_id, null, GD_TEMPLATEFORMAT_ADDONS);
				}
				break;
			
			/*********************************************
			 * Others
			 *********************************************/
			case POP_EM_POPPROCESSORS_PAGE_LOCATIONS:

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
						
						$replicable[] = GD_TEMPLATE_BLOCK_EVENT_CREATE;
						$replicable[] = GD_TEMPLATE_BLOCK_EVENTLINK_CREATE;
					}
				}
				elseif ($template_id == GD_TEMPLATE_PAGESECTION_ADDONS_PAGE) {

					if (PoPTheme_Wassup_Utils::get_addcontent_target() == GD_URLPARAM_TARGET_ADDONS) {
						
						$replicable[] = GD_TEMPLATE_BLOCK_EVENT_CREATE;
						$replicable[] = GD_TEMPLATE_BLOCK_EVENTLINK_CREATE;
					}
				}
				elseif ($template_id == GD_TEMPLATE_PAGESECTION_MODALS_PAGE) {

					$replicable[] = GD_TEMPLATE_BLOCK_STATICLOCATIONSMAP;
					$blockgroupsreplicable[] = GD_TEMPLATE_BLOCKGROUP_CREATELOCATION;
				}
				break;
		}

		// Frames: PageSection ControlGroups
		switch ($page_id) {

			case POP_COREPROCESSORS_PAGE_LOADERS_INITIALFRAMES:

				if ($template_id == GD_TEMPLATE_PAGESECTION_PAGE) {

					$frames[] = GD_TEMPLATE_BLOCK_PAGECONTROL_EVENT_CREATE;
					$frames[] = GD_TEMPLATE_BLOCK_PAGECONTROL_EVENTLINK_CREATE;
				}
				break;

			// case POPTHEME_WASSUP_EM_PAGE_ADDEVENT:

			// 	$frames[] = GD_TEMPLATE_BLOCK_PAGECONTROL_EVENT_CREATE;
			// 	break;

			// case POPTHEME_WASSUP_EM_PAGE_ADDEVENTLINK:

			// 	$frames[] = GD_TEMPLATE_BLOCK_PAGECONTROL_EVENTLINK_CREATE;
			// 	break;
				
			case POPTHEME_WASSUP_EM_PAGE_EVENTS:
			case POPTHEME_WASSUP_EM_PAGE_EVENTSCALENDAR:
			case POPTHEME_WASSUP_EM_PAGE_PASTEVENTS:
			case POPTHEME_WASSUP_EM_PAGE_MYEVENTS:
			case POPTHEME_WASSUP_EM_PAGE_MYPASTEVENTS:

				if ($template_id == GD_TEMPLATE_PAGESECTION_PAGE && $target == GD_URLPARAM_TARGET_MAIN) {

					$frames[] = GD_TEMPLATE_BLOCK_PAGEWITHSIDECONTROL;
				}
				elseif ($template_id == GD_TEMPLATE_PAGESECTION_QUICKVIEWPAGE && $target == GD_URLPARAM_TARGET_QUICKVIEW) {

					$frames[] = GD_TEMPLATE_BLOCK_QUICKVIEWPAGEWITHSIDECONTROL;
				}
				break;

			case POPTHEME_WASSUP_EM_PAGE_ADDEVENT:
			case POPTHEME_WASSUP_EM_PAGE_ADDEVENTLINK:
			// case POPTHEME_WASSUP_EM_PAGE_EVENTS:
			// case POPTHEME_WASSUP_EM_PAGE_EVENTSCALENDAR:
			// case POPTHEME_WASSUP_EM_PAGE_PASTEVENTS:
			// case POPTHEME_WASSUP_EM_PAGE_MYEVENTS:
			// case POPTHEME_WASSUP_EM_PAGE_MYPASTEVENTS:
			case POPTHEME_WASSUP_EM_PAGE_EDITEVENT:
			case POPTHEME_WASSUP_EM_PAGE_EDITEVENTLINK:

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

			/*********************************************
			 * Initial load
			 *********************************************/
			case POP_COREPROCESSORS_PAGE_LOADERS_INITIALFRAMES:

				if ($template_id == GD_TEMPLATE_PAGESECTION_PAGETABS_PAGE) {

					if (PoPTheme_Wassup_Utils::get_addcontent_target() == GD_URLPARAM_TARGET_MAIN) {
					
						$replicable[] = GD_TEMPLATE_BLOCK_PAGETABS_EVENT_CREATE;
						$replicable[] = GD_TEMPLATE_BLOCK_PAGETABS_EVENTLINK_CREATE;
					}
				}
				elseif ($template_id == GD_TEMPLATE_PAGESECTION_ADDONTABS_PAGE) {
					
					if (PoPTheme_Wassup_Utils::get_addcontent_target() == GD_URLPARAM_TARGET_ADDONS) {
						
						$replicable[] = GD_TEMPLATE_BLOCK_PAGETABS_EVENT_CREATE;
						$replicable[] = GD_TEMPLATE_BLOCK_PAGETABS_EVENTLINK_CREATE;
					}
				}
				break;	

			case POPTHEME_WASSUP_EM_PAGE_EVENTS:
			case POPTHEME_WASSUP_EM_PAGE_EVENTSCALENDAR:
			case POPTHEME_WASSUP_EM_PAGE_PASTEVENTS:
			case POPTHEME_WASSUP_EM_PAGE_MYEVENTS:
			case POPTHEME_WASSUP_EM_PAGE_MYPASTEVENTS:
			case POPTHEME_WASSUP_EM_PAGE_ADDEVENT:
			case POPTHEME_WASSUP_EM_PAGE_ADDEVENTLINK:
			case POPTHEME_WASSUP_EM_PAGE_EDITEVENT:
			case POPTHEME_WASSUP_EM_PAGE_EDITEVENTLINK:

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
new Wassup_EM_PageSectionSettingsProcessor();
