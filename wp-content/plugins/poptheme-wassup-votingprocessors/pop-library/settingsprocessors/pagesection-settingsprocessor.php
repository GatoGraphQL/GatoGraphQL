<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class VotingProcessors_PageSectionSettingsProcessor extends Wassup_PageSectionSettingsProcessorBase {

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
				POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES => GD_TEMPLATE_BLOCKGROUP_AUTHOROPINIONATEDVOTES_SIDEBAR,
				POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_PRO => GD_TEMPLATE_BLOCKGROUP_AUTHOROPINIONATEDVOTES_PRO_SIDEBAR,
				POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_NEUTRAL => GD_TEMPLATE_BLOCKGROUP_AUTHOROPINIONATEDVOTES_NEUTRAL_SIDEBAR,
				POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_AGAINST => GD_TEMPLATE_BLOCKGROUP_AUTHOROPINIONATEDVOTES_AGAINST_SIDEBAR,
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
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_BYORGANIZATIONS:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_BYINDIVIDUALS:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_PRO:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_AGAINST:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_NEUTRAL:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_PRO_GENERAL:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_AGAINST_GENERAL:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_NEUTRAL_GENERAL:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_PRO_ARTICLE:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_AGAINST_ARTICLE:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_NEUTRAL_ARTICLE:

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
			
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_BYORGANIZATIONS:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_BYINDIVIDUALS:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_PRO:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_AGAINST:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_NEUTRAL:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_PRO_GENERAL:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_AGAINST_GENERAL:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_NEUTRAL_GENERAL:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_PRO_ARTICLE:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_AGAINST_ARTICLE:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_NEUTRAL_ARTICLE:

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
				POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES => GD_TEMPLATE_BLOCKGROUP_TAG_OPINIONATEDVOTES_SIDEBAR,
				POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_PRO => GD_TEMPLATE_BLOCKGROUP_TAG_OPINIONATEDVOTES_STANCE_SIDEBAR,
				POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_NEUTRAL => GD_TEMPLATE_BLOCKGROUP_TAG_OPINIONATEDVOTES_STANCE_SIDEBAR,
				POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_AGAINST => GD_TEMPLATE_BLOCKGROUP_TAG_OPINIONATEDVOTES_STANCE_SIDEBAR,
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

			$blockgroups = array();
			$post_type = get_post_type();
			if ($post_type == 'post') {

				$cats_blockgroup = array(
					POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES => GD_TEMPLATE_BLOCKGROUP_SINGLE_OPINIONATEDVOTE_SIDEBAR,
				);

				if ($cat_blockgroup = $cats_blockgroup[gd_get_the_main_category()]) {
					$blockgroups[] = $cat_blockgroup;
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
				
				case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_MYOPINIONATEDVOTES:

					$blockgroups[] = GD_TEMPLATE_BLOCKGROUP_SECTION_MYOPINIONATEDVOTES_SIDEBAR;
					break;

				case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES:
					
					$blockgroups[] = GD_TEMPLATE_BLOCKGROUP_SECTION_OPINIONATEDVOTES_SIDEBAR;
					break;

				case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_BYORGANIZATIONS:
				case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_BYINDIVIDUALS:
					
					$blockgroups[] = GD_TEMPLATE_BLOCKGROUP_SECTION_OPINIONATEDVOTES_AUTHORROLE_SIDEBAR;
					break;

				case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_PRO:
				case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_AGAINST:
				case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_NEUTRAL:
					
				case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_PRO_ARTICLE:
				case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_AGAINST_ARTICLE:
				case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_NEUTRAL_ARTICLE:
					
					$blockgroups[] = GD_TEMPLATE_BLOCKGROUP_SECTION_OPINIONATEDVOTES_STANCE_SIDEBAR;
					break;

				case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_PRO_GENERAL:
				case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_AGAINST_GENERAL:
				case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_NEUTRAL_GENERAL:
					
					$blockgroups[] = GD_TEMPLATE_BLOCKGROUP_SECTION_OPINIONATEDVOTES_GENERALSTANCE_SIDEBAR;
					break;		
			}

			// Frames
			switch ($page_id) {
				
				case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_MYOPINIONATEDVOTES:
				case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES:
				case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_BYORGANIZATIONS:
				case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_BYINDIVIDUALS:
				case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_PRO:
				case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_AGAINST:
				case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_NEUTRAL:
				case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_PRO_GENERAL:
				case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_AGAINST_GENERAL:
				case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_NEUTRAL_GENERAL:
				case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_PRO_ARTICLE:
				case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_AGAINST_ARTICLE:
				case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_NEUTRAL_ARTICLE:

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

	function add_single_blockunits(&$ret, $template_id) {

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
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_PRO:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_NEUTRAL:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_AGAINST:

				if ($template_id == GD_TEMPLATE_PAGESECTION_SINGLE && $target == GD_URLPARAM_TARGET_MAIN) {
			
					if ($fetching_json_data) {

						$blocks[] = $gd_template_settingsmanager->get_page_block($page_id, GD_SETTINGS_HIERARCHY_SINGLE);
						break;
					}

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
				elseif (
					($template_id == GD_TEMPLATE_PAGESECTION_QUICKVIEWSINGLE && $target == GD_URLPARAM_TARGET_QUICKVIEW) ||
					($template_id == GD_TEMPLATE_PAGESECTION_ADDONS_SINGLE && $target == GD_URLPARAM_TARGET_ADDONS) ||
					($template_id == GD_TEMPLATE_PAGESECTION_MODALS_SINGLE && $target == GD_URLPARAM_TARGET_MODALS)
				) {
			
					// Fixed format, so if initially setting the format with the settings it doesn't override this format
					$blocks[] = $gd_template_settingsmanager->get_page_block($page_id, GD_SETTINGS_HIERARCHY_SINGLE, GD_TEMPLATEFORMAT_LIST);
				}
				break;
		}

		GD_TemplateManager_Utils::add_blockgroups($ret, $blockgroups, GD_TEMPLATEBLOCKSETTINGS_BLOCKGROUP);
		GD_TemplateManager_Utils::add_blocks($ret, $blocks, GD_TEMPLATEBLOCKSETTINGS_MAIN);
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
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_PRO:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_NEUTRAL:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_AGAINST:

				if ($template_id == GD_TEMPLATE_PAGESECTION_AUTHOR && $target == GD_URLPARAM_TARGET_MAIN) {
			
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
				elseif (
					($template_id == GD_TEMPLATE_PAGESECTION_QUICKVIEWAUTHOR && $target == GD_URLPARAM_TARGET_QUICKVIEW) ||
					($template_id == GD_TEMPLATE_PAGESECTION_ADDONS_AUTHOR && $target == GD_URLPARAM_TARGET_ADDONS) ||
					($template_id == GD_TEMPLATE_PAGESECTION_MODALS_AUTHOR && $target == GD_URLPARAM_TARGET_MODALS)
				) {
					// Fixed format, so if initially setting the format with the settings it doesn't override this format
					$blocks[] = $gd_template_settingsmanager->get_page_block($page_id, GD_SETTINGS_HIERARCHY_AUTHOR, GD_TEMPLATEFORMAT_LIST);
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
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_ADDOPINIONATEDVOTE:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_EDITOPINIONATEDVOTE:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_ADDOREDITOPINIONATEDVOTE:

				$add = 
					($template_id == GD_TEMPLATE_PAGESECTION_PAGE && $target == GD_URLPARAM_TARGET_MAIN) ||
					($template_id == GD_TEMPLATE_PAGESECTION_QUICKVIEWPAGE && $target == GD_URLPARAM_TARGET_QUICKVIEW) ||
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
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_MYOPINIONATEDVOTES:

				if ($template_id == GD_TEMPLATE_PAGESECTION_PAGE) {

					// $blocks[] = $gd_template_settingsmanager->get_page_block($page_id);
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
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_BYORGANIZATIONS:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_BYINDIVIDUALS:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_PRO:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_AGAINST:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_NEUTRAL:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_PRO_GENERAL:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_AGAINST_GENERAL:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_NEUTRAL_GENERAL:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_PRO_ARTICLE:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_AGAINST_ARTICLE:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_NEUTRAL_ARTICLE:

				if ($template_id == GD_TEMPLATE_PAGESECTION_PAGE && $target == GD_URLPARAM_TARGET_MAIN) {

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
				elseif ($template_id == GD_TEMPLATE_PAGESECTION_QUICKVIEWPAGE && $target == GD_URLPARAM_TARGET_QUICKVIEW) {

					// Fixed format, so if initially setting the format with the settings it doesn't override this format
					$blocks[] = $gd_template_settingsmanager->get_page_block($page_id, null, GD_TEMPLATEFORMAT_LIST);
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

				if ($template_id == GD_TEMPLATE_PAGESECTION_ADDONS_PAGE) {

					$replicable[] = GD_TEMPLATE_BLOCK_OPINIONATEDVOTE_CREATE;
				}
				break;
		}

		// Frames: PageSection ControlGroups
		switch ($page_id) {

			case POP_COREPROCESSORS_PAGE_LOADERS_INITIALFRAMES:

				if ($template_id == GD_TEMPLATE_PAGESECTION_PAGE) {

					$frames[] = GD_TEMPLATE_BLOCK_PAGECONTROL_OPINIONATEDVOTE_CREATE;
				}
				break;

			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_BYORGANIZATIONS:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_BYINDIVIDUALS:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_PRO:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_AGAINST:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_NEUTRAL:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_PRO_GENERAL:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_AGAINST_GENERAL:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_NEUTRAL_GENERAL:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_PRO_ARTICLE:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_AGAINST_ARTICLE:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_NEUTRAL_ARTICLE:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_MYOPINIONATEDVOTES:

				if ($template_id == GD_TEMPLATE_PAGESECTION_PAGE && $target == GD_URLPARAM_TARGET_MAIN) {

					$frames[] = GD_TEMPLATE_BLOCK_PAGEWITHSIDECONTROL;
				}
				elseif ($template_id == GD_TEMPLATE_PAGESECTION_QUICKVIEWPAGE && $target == GD_URLPARAM_TARGET_QUICKVIEW) {

					$frames[] = GD_TEMPLATE_BLOCK_QUICKVIEWPAGECONTROL;//GD_TEMPLATE_BLOCK_QUICKVIEWPAGEWITHSIDECONTROL;
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
		
		$blocks = $replicable = array();
		$page_id = GD_TemplateManager_Utils::get_hierarchy_page_id();

		switch ($page_id) {
			
			/*********************************************
			 * Initial load
			 *********************************************/
			case POP_COREPROCESSORS_PAGE_LOADERS_INITIALFRAMES:

				if ($template_id == GD_TEMPLATE_PAGESECTION_ADDONTABS_PAGE) {

					$replicable[] = GD_TEMPLATE_BLOCK_PAGETABS_OPINIONATEDVOTE_CREATE;
				}
				break;

			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_BYORGANIZATIONS:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_BYINDIVIDUALS:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_PRO:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_AGAINST:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_NEUTRAL:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_PRO_GENERAL:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_AGAINST_GENERAL:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_NEUTRAL_GENERAL:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_PRO_ARTICLE:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_AGAINST_ARTICLE:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_NEUTRAL_ARTICLE:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_MYOPINIONATEDVOTES:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_ADDOPINIONATEDVOTE:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_EDITOPINIONATEDVOTE:
			case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_ADDOREDITOPINIONATEDVOTE:

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
new VotingProcessors_PageSectionSettingsProcessor();
