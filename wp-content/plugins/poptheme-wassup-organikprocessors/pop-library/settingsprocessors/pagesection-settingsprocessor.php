<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_Wassup_OrganikProcessors_PageSectionSettingsProcessor extends Wassup_PageSectionSettingsProcessorBase {

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
				POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_FARMS => GD_TEMPLATE_BLOCKGROUP_AUTHORFARMS_SIDEBAR,
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
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_FARMS:

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

		GD_TemplateManager_Utils::add_blockgroups($ret, $blockgroups, GD_TEMPLATEBLOCKSETTINGS_BLOCKGROUP);
		GD_TemplateManager_Utils::add_blocks($ret, $blocks, GD_TEMPLATEBLOCKSETTINGS_MAIN);

		switch ($page_id) {
			
			case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_FARMS:

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
				POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_FARMS => GD_TEMPLATE_BLOCKGROUP_TAGFARMS_SIDEBAR,
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
			if (get_post_type() == 'post') {

				// For the Single Related: add the specific sidebar, which includes a delegator filter for the corresponding content
				$sidebar = null;
				$page_id = GD_TemplateManager_Utils::get_hierarchy_page_id();
				$farm_sidebars = array(
					POPTHEME_WASSUP_PAGE_HIGHLIGHTS => GD_TEMPLATE_BLOCKGROUP_SINGLE_FARM_HIGHLIGHTSSIDEBAR,
					POP_COREPROCESSORS_PAGE_RELATEDCONTENT => GD_TEMPLATE_BLOCKGROUP_SINGLE_FARM_RELATEDCONTENTSIDEBAR,
					POP_COREPROCESSORS_PAGE_POSTAUTHORS => GD_TEMPLATE_BLOCKGROUP_SINGLE_FARM_POSTAUTHORSSIDEBAR,
					POP_COREPROCESSORS_PAGE_RECOMMENDEDBY => GD_TEMPLATE_BLOCKGROUP_SINGLE_FARM_RECOMMENDEDBYSIDEBAR,
				);
				if (!($sidebar = $farm_sidebars[$page_id])) {
					
					// If there is no tab, it must be the Single Post description
					$cats_blockgroup = array(
						POPTHEME_WASSUP_ORGANIKPROCESSORS_CAT_FARMS => GD_TEMPLATE_BLOCKGROUP_SINGLE_FARM_SIDEBAR,
					);
					$sidebar = $cats_blockgroup[gd_get_the_main_category()];
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

			$blocks = $blockgroups = $frames = array();
			$page_id = GD_TemplateManager_Utils::get_hierarchy_page_id();
			switch ($page_id) {
				
				case POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_FARMS:

					$blockgroups[] = GD_TEMPLATE_BLOCKGROUP_SECTION_FARMS_SIDEBAR;
					break;

				case POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_MYFARMS:

					$blockgroups[] = GD_TEMPLATE_BLOCKGROUP_SECTION_MYFARMS_SIDEBAR;
					break;

				case POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_ADDFARM:
				case POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_EDITFARM:
				case POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_ADDFARMLINK:
				case POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_EDITFARMLINK:

					$blocks[] = GD_TEMPLATE_BLOCK_EMPTYSIDEINFO;
					break;	
			}

			// Frames
			switch ($page_id) {
				
				case POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_FARMS:
				case POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_MYFARMS:

					$frames[] = GD_TEMPLATE_BLOCK_PAGECONTROL_PAGESIDEBAR;
					break;
			}

			GD_TemplateManager_Utils::add_blocks($ret, $blocks, GD_TEMPLATEBLOCKSETTINGS_MAIN);
			GD_TemplateManager_Utils::add_blockgroups($ret, $blockgroups, GD_TEMPLATEBLOCKSETTINGS_BLOCKGROUP);
			
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
			case POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_FARMS:

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
		
		$blocks = $blockgroups = $frames = array();

		switch ($page_id) {

			/*********************************************
			 * Initial load
			 *********************************************/
			case POP_COREPROCESSORS_PAGE_LOADERS_INITIALFRAMES:

				if ($template_id == GD_TEMPLATE_PAGESECTION_PAGE) {

					if (PoPTheme_Wassup_Utils::get_addcontent_target() == GD_URLPARAM_TARGET_MAIN) {
						
						$replicable[] = GD_TEMPLATE_BLOCK_FARM_CREATE;
						$replicable[] = GD_TEMPLATE_BLOCK_FARMLINK_CREATE;
					}
				}
				elseif ($template_id == GD_TEMPLATE_PAGESECTION_ADDONS_PAGE) {

					if (PoPTheme_Wassup_Utils::get_addcontent_target() == GD_URLPARAM_TARGET_ADDONS) {
						
						$replicable[] = GD_TEMPLATE_BLOCK_FARM_CREATE;
						$replicable[] = GD_TEMPLATE_BLOCK_FARMLINK_CREATE;
					}
				}
				break;
			
			/*********************************************
			 * Add/Edit Content
			 *********************************************/
			case POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_ADDFARM:
			case POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_EDITFARM:
			case POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_ADDFARMLINK:
			case POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_EDITFARMLINK:

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
			case POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_MYFARMS:

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
			case POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_FARMS:

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
		}

		// Frames: PageSection ControlGroups
		switch ($page_id) {

			case POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_FARMS:
			case POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_MYFARMS:

				if ($template_id == GD_TEMPLATE_PAGESECTION_PAGE && $target == GD_URLPARAM_TARGET_MAIN) {

					$frames[] = GD_TEMPLATE_BLOCK_PAGEWITHSIDECONTROL;
				}
				elseif ($template_id == GD_TEMPLATE_PAGESECTION_QUICKVIEWPAGE && $target == GD_URLPARAM_TARGET_QUICKVIEW) {

					$frames[] = GD_TEMPLATE_BLOCK_QUICKVIEWPAGEWITHSIDECONTROL;
				}
				break;

			case POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_ADDFARM:
			case POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_EDITFARM:
			case POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_ADDFARMLINK:
			case POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_EDITFARMLINK:

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

			/*********************************************
			 * Initial load
			 *********************************************/
			case POP_COREPROCESSORS_PAGE_LOADERS_INITIALFRAMES:

				if ($template_id == GD_TEMPLATE_PAGESECTION_PAGETABS_PAGE) {

					if (PoPTheme_Wassup_Utils::get_addcontent_target() == GD_URLPARAM_TARGET_MAIN) {
						
						$replicable[] = GD_TEMPLATE_BLOCK_PAGETABS_FARM_CREATE;
						$replicable[] = GD_TEMPLATE_BLOCK_PAGETABS_FARMLINK_CREATE;
					}
				}
				elseif ($template_id == GD_TEMPLATE_PAGESECTION_ADDONTABS_PAGE) {

					if (PoPTheme_Wassup_Utils::get_addcontent_target() == GD_URLPARAM_TARGET_ADDONS) {
						
						$replicable[] = GD_TEMPLATE_BLOCK_PAGETABS_FARM_CREATE;
						$replicable[] = GD_TEMPLATE_BLOCK_PAGETABS_FARMLINK_CREATE;
					}
				}
				break;

			case POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_FARMS:
			case POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_MYFARMS:
			case POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_ADDFARM:
			case POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_EDITFARM:
			case POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_ADDFARMLINK:
			case POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_EDITFARMLINK:

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
new PoPTheme_Wassup_OrganikProcessors_PageSectionSettingsProcessor();
