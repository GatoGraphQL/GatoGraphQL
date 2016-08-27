<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_Wassup_CategoryProcessors_PageSectionSettingsProcessor extends Wassup_PageSectionSettingsProcessorBase {

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
				POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS00 => GD_TEMPLATE_BLOCKGROUP_AUTHORWEBPOSTS_SIDEBAR,
				POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS01 => GD_TEMPLATE_BLOCKGROUP_AUTHORWEBPOSTS_SIDEBAR,
				POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS02 => GD_TEMPLATE_BLOCKGROUP_AUTHORWEBPOSTS_SIDEBAR,
				POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS03 => GD_TEMPLATE_BLOCKGROUP_AUTHORWEBPOSTS_SIDEBAR,
				POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS04 => GD_TEMPLATE_BLOCKGROUP_AUTHORWEBPOSTS_SIDEBAR,
				POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS05 => GD_TEMPLATE_BLOCKGROUP_AUTHORWEBPOSTS_SIDEBAR,
				POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS06 => GD_TEMPLATE_BLOCKGROUP_AUTHORWEBPOSTS_SIDEBAR,
				POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS07 => GD_TEMPLATE_BLOCKGROUP_AUTHORWEBPOSTS_SIDEBAR,
				POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS08 => GD_TEMPLATE_BLOCKGROUP_AUTHORWEBPOSTS_SIDEBAR,
				POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS09 => GD_TEMPLATE_BLOCKGROUP_AUTHORWEBPOSTS_SIDEBAR,
			);
			if ($sidebar = $page_sidebars[$page_id]) {
				$blockgroups[] = $sidebar;
			}

			// // Frames: PageSection ControlGroups
			// if ($template_id == GD_TEMPLATE_PAGESECTION_SIDEINFO_AUTHOR && $target == GD_URLPARAM_TARGET_MAIN) {

			// 	$frames[] = GD_TEMPLATE_BLOCK_PAGECONTROL_AUTHORSIDEBAR;
			// }

			GD_TemplateManager_Utils::add_blockgroups($ret, $blockgroups, GD_TEMPLATEBLOCKSETTINGS_BLOCKGROUP);

			// // Add frames only if not fetching data for the block
			// if (!$vars['fetching-json-data']) {

			// 	GD_TemplateManager_Utils::add_blocks($ret, $frames, GD_TEMPLATEBLOCKSETTINGS_FRAME);
			// }
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
				
				case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS00:
				case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS01:
				case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS02:
				case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS03:
				case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS04:
				case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS05:
				case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS06:
				case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS07:
				case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS08:
				case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS09:

					$blockgroups[] = GD_TEMPLATE_BLOCKGROUP_SECTION_WEBPOSTS_SIDEBAR;
					break;

				case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_MYCATEGORYPOSTS00:
				case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_MYCATEGORYPOSTS01:
				case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_MYCATEGORYPOSTS02:
				case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_MYCATEGORYPOSTS03:
				case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_MYCATEGORYPOSTS04:
				case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_MYCATEGORYPOSTS05:
				case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_MYCATEGORYPOSTS06:
				case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_MYCATEGORYPOSTS07:
				case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_MYCATEGORYPOSTS08:
				case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_MYCATEGORYPOSTS09:

					$blockgroups[] = GD_TEMPLATE_BLOCKGROUP_SECTION_MYWEBPOSTS_SIDEBAR;
					break;
			}

			// Frames
			switch ($page_id) {
				
				case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS00:
				case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS01:
				case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS02:
				case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS03:
				case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS04:
				case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS05:
				case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS06:
				case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS07:
				case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS08:
				case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS09:
				case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_MYCATEGORYPOSTS00:
				case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_MYCATEGORYPOSTS01:
				case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_MYCATEGORYPOSTS02:
				case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_MYCATEGORYPOSTS03:
				case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_MYCATEGORYPOSTS04:
				case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_MYCATEGORYPOSTS05:
				case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_MYCATEGORYPOSTS06:
				case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_MYCATEGORYPOSTS07:
				case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_MYCATEGORYPOSTS08:
				case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_MYCATEGORYPOSTS09:

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
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS00:
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS01:
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS02:
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS03:
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS04:
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS05:
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS06:
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS07:
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS08:
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS09:

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

			/**-------------------------------------------
			 * MAIN
			 *-------------------------------------------*/
			/*********************************************
			 * My Content
			 *********************************************/
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_MYCATEGORYPOSTS00:
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_MYCATEGORYPOSTS01:
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_MYCATEGORYPOSTS02:
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_MYCATEGORYPOSTS03:
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_MYCATEGORYPOSTS04:
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_MYCATEGORYPOSTS05:
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_MYCATEGORYPOSTS06:
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_MYCATEGORYPOSTS07:
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_MYCATEGORYPOSTS08:
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_MYCATEGORYPOSTS09:

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
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS00:
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS01:
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS02:
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS03:
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS04:
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS05:
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS06:
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS07:
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS08:
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS09:

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

			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS00:
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS01:
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS02:
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS03:
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS04:
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS05:
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS06:
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS07:
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS08:
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS09:
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_MYCATEGORYPOSTS00:
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_MYCATEGORYPOSTS01:
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_MYCATEGORYPOSTS02:
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_MYCATEGORYPOSTS03:
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_MYCATEGORYPOSTS04:
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_MYCATEGORYPOSTS05:
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_MYCATEGORYPOSTS06:
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_MYCATEGORYPOSTS07:
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_MYCATEGORYPOSTS08:
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_MYCATEGORYPOSTS09:

				if ($template_id == GD_TEMPLATE_PAGESECTION_PAGE && $target == GD_URLPARAM_TARGET_MAIN) {

					$frames[] = GD_TEMPLATE_BLOCK_PAGEWITHSIDECONTROL;
				}
				elseif ($template_id == GD_TEMPLATE_PAGESECTION_QUICKVIEWPAGE && $target == GD_URLPARAM_TARGET_QUICKVIEW) {

					$frames[] = GD_TEMPLATE_BLOCK_QUICKVIEWPAGEWITHSIDECONTROL;
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

			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS00:
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS01:
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS02:
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS03:
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS04:
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS05:
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS06:
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS07:
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS08:
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_CATEGORYPOSTS09:
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_MYCATEGORYPOSTS00:
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_MYCATEGORYPOSTS01:
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_MYCATEGORYPOSTS02:
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_MYCATEGORYPOSTS03:
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_MYCATEGORYPOSTS04:
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_MYCATEGORYPOSTS05:
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_MYCATEGORYPOSTS06:
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_MYCATEGORYPOSTS07:
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_MYCATEGORYPOSTS08:
			case POPTHEME_WASSUP_CATEGORYPROCESSORS_PAGE_MYCATEGORYPOSTS09:

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
new PoPTheme_Wassup_CategoryProcessors_PageSectionSettingsProcessor();
