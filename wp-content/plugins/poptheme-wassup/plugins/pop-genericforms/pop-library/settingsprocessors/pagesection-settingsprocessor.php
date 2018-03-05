<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class Wassup_GF_PageSectionSettingsProcessor extends Wassup_PageSectionSettingsProcessorBase {

	function add_sideinfo_page_blockunits(&$ret, $template_id) {

		global $gd_template_settingsmanager;
		$vars = GD_TemplateManager_Utils::get_vars();
		$fetching_json_data = $vars['fetching-json-data'];
		$submitted_data = $fetching_json_data && doing_post();
		$target = $vars['target'];
		$add = 
			($template_id == GD_TEMPLATE_PAGESECTION_SIDEINFO_PAGE && $target == GD_URLPARAM_TARGET_MAIN) ||
			($template_id == GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWPAGE && $target == GD_URLPARAM_TARGET_QUICKVIEW);
		if ($add) {

			$blocks = array();
			$page_id = GD_TemplateManager_Utils::get_hierarchy_page_id();
			switch ($page_id) {

				case POP_GENERICFORMS_PAGE_NEWSLETTER:

					// For the Sideinfo's Newsletter in GetPoP
					if ($submitted_data) {

						$blocks[] = $gd_template_settingsmanager->get_page_action($page_id);
						break;
					}
					break;		
			}

			GD_TemplateManager_Utils::add_blocks($ret, $blocks, GD_TEMPLATEBLOCKSETTINGS_MAIN);
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
		
		$blocks = $blockgroups = $replicable = $frames = array();

		switch ($page_id) {

			/**-------------------------------------------
			 * ADDONS/MAIN
			 *-------------------------------------------*/
			case POP_GENERICFORMS_PAGE_CONTACTUSER:
			case POP_GENERICFORMS_PAGE_VOLUNTEER:
			case POP_GENERICFORMS_PAGE_FLAG:

				if ($template_id == GD_TEMPLATE_PAGESECTION_PAGE && $target == GD_URLPARAM_TARGET_MAIN) {

					if ($submitted_data) {

						$blocks[] = $gd_template_settingsmanager->get_page_action($page_id);
						break;
					}
					$blockgroups[] = $gd_template_settingsmanager->get_page_blockgroup($page_id);
				}
				elseif ($template_id == GD_TEMPLATE_PAGESECTION_ADDONS_PAGE && $target == GD_URLPARAM_TARGET_ADDONS) {

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
			case POP_GENERICFORMS_PAGE_SHAREBYEMAIL:
			
				// if ($template_id == GD_TEMPLATE_PAGESECTION_OPERATIONAL) {
				if ($template_id == GD_TEMPLATE_PAGESECTION_COMPONENTS) {

					if ($submitted_data) {

						$blocks[] = $gd_template_settingsmanager->get_page_action($page_id);
					}
				}
				break;

			/**-------------------------------------------
			 * HOVER
			 *-------------------------------------------*/
			case POP_GENERICFORMS_PAGE_CONTACTUS:

				$add = 
					($template_id == GD_TEMPLATE_PAGESECTION_HOVER) || 
					// Only allow to add the Contact us form in the Main when doing JSON, so that calling https://www.mesym.com/en/contact-us/ only brings it in the hover, but the form can still be placed in the Main, as with getPoP, and process the requests.
					($fetching_json_data && ($template_id == GD_TEMPLATE_PAGESECTION_PAGE && $target == GD_URLPARAM_TARGET_MAIN));
				if ($add) {

					if ($submitted_data) {

						$blocks[] = $gd_template_settingsmanager->get_page_action($page_id);
						break;
					}
					$blocks[] = $gd_template_settingsmanager->get_page_block($page_id);
				}
				break;


			/*********************************************
			 * Newsletter: it appears in both main (homepage) and hover pageSections:
			 * (GD_TEMPLATE_BLOCK_NEWSLETTER and GD_TEMPLATE_BLOCKCODE_NEWSLETTER)
			 * But dismiss the block for main, only action is needed
			 *********************************************/
			case POP_GENERICFORMS_PAGE_NEWSLETTER:

				$add = 
					($template_id == GD_TEMPLATE_PAGESECTION_HOVER) ||
					($template_id == GD_TEMPLATE_PAGESECTION_PAGE);
				if ($add && $submitted_data) {

					$blocks[] = $gd_template_settingsmanager->get_page_action($page_id);
					break;
				}
				elseif ($template_id == GD_TEMPLATE_PAGESECTION_HOVER) {

					$blocks[] = GD_TEMPLATE_BLOCKCODE_NEWSLETTER;
					break;
				}	
				break;

			case POP_GENERICFORMS_PAGE_NEWSLETTERUNSUBSCRIPTION:

				$add = 
					($template_id == GD_TEMPLATE_PAGESECTION_HOVER)/* ||
					($template_id == GD_TEMPLATE_PAGESECTION_PAGE)*/;
				if ($add) {

					if ($submitted_data) {

						$blocks[] = $gd_template_settingsmanager->get_page_action($page_id);
						break;
					}
					$blocks[] = $gd_template_settingsmanager->get_page_block($page_id);
				}
				break;

			/*********************************************
			 * Initial load
			 *********************************************/
			case POP_COREPROCESSORS_PAGE_LOADERS_INITIALFRAMES:

				if ($template_id == GD_TEMPLATE_PAGESECTION_ADDONS_PAGE) {

					$replicable[] = GD_TEMPLATE_BLOCK_CONTACTUSER;
					
					// Only if enabled
					if (defined('POP_GENERICFORMS_PAGE_VOLUNTEER')) {
						$replicable[] = GD_TEMPLATE_BLOCK_VOLUNTEER;
					}
					$replicable[] = GD_TEMPLATE_BLOCK_FLAG;
				}
				elseif ($template_id == GD_TEMPLATE_PAGESECTION_HOVER) {

					$replicable[] = GD_TEMPLATE_BLOCKCODE_NEWSLETTER;
					$replicable[] = GD_TEMPLATE_BLOCK_CONTACTUS;
				}
				break;
		}

		// Frames: PageSection ControlGroups
		switch ($page_id) {

			case POP_GENERICFORMS_PAGE_CONTACTUSER:
			case POP_GENERICFORMS_PAGE_VOLUNTEER:
			case POP_GENERICFORMS_PAGE_FLAG:
			case POP_GENERICFORMS_PAGE_SHAREBYEMAIL:
			case POP_GENERICFORMS_PAGE_CONTACTUS:
			case POP_GENERICFORMS_PAGE_NEWSLETTER:
			case POP_GENERICFORMS_PAGE_NEWSLETTERUNSUBSCRIPTION:

				if ($template_id == GD_TEMPLATE_PAGESECTION_PAGE) {

					$frames[] = GD_TEMPLATE_BLOCK_PAGECONTROL;
				}
				break;
		}

		GD_TemplateManager_Utils::add_blocks($ret, $blocks, GD_TEMPLATEBLOCKSETTINGS_MAIN);
		GD_TemplateManager_Utils::add_blockgroups($ret, $blockgroups, GD_TEMPLATEBLOCKSETTINGS_BLOCKGROUP);
		GD_TemplateManager_Utils::add_blocks($ret, $replicable, GD_TEMPLATEBLOCKSETTINGS_REPLICABLE);

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
			
			case POP_GENERICFORMS_PAGE_CONTACTUSER:
			case POP_GENERICFORMS_PAGE_VOLUNTEER:
			case POP_GENERICFORMS_PAGE_FLAG:
			case POP_GENERICFORMS_PAGE_SHAREBYEMAIL:

				if ($template_id == GD_TEMPLATE_PAGESECTION_ADDONTABS_PAGE && $target == GD_URLPARAM_TARGET_ADDONS) {

					$tabs = array(
						POP_GENERICFORMS_PAGE_CONTACTUSER => GD_TEMPLATE_BLOCK_ADDONTABS_CONTACTUSER,
						POP_GENERICFORMS_PAGE_VOLUNTEER => GD_TEMPLATE_BLOCK_ADDONTABS_VOLUNTEER,
						POP_GENERICFORMS_PAGE_FLAG => GD_TEMPLATE_BLOCK_ADDONTABS_FLAG,
						POP_GENERICFORMS_PAGE_SHAREBYEMAIL => GD_TEMPLATE_BLOCK_ADDONTABS_SHAREBYEMAIL,
					);
					$blocks[] = $tabs[$page_id];
				}
				elseif ($template_id == GD_TEMPLATE_PAGESECTION_PAGETABS_PAGE && $target == GD_URLPARAM_TARGET_MAIN) {

					$blocks[] = GD_TEMPLATE_BLOCK_PAGETABS_PAGE;
				}
				break;

			// These 2 are open in the hover, so they must not add a pageTab if calling that URL straight
			case POP_GENERICFORMS_PAGE_CONTACTUS:
			case POP_GENERICFORMS_PAGE_NEWSLETTER:
			case POP_GENERICFORMS_PAGE_NEWSLETTERUNSUBSCRIPTION:

				if ($template_id == GD_TEMPLATE_PAGESECTION_ADDONTABS_PAGE && $target == GD_URLPARAM_TARGET_ADDONS) {

					$tabs = array(
						POP_GENERICFORMS_PAGE_CONTACTUS => GD_TEMPLATE_BLOCK_ADDONTABS_CONTACTUS,
						POP_GENERICFORMS_PAGE_NEWSLETTER => GD_TEMPLATE_BLOCK_ADDONTABS_NEWSLETTER,
						POP_GENERICFORMS_PAGE_NEWSLETTERUNSUBSCRIPTION => GD_TEMPLATE_BLOCK_PAGETABS_PAGE,
					);
					$blocks[] = $tabs[$page_id];
				}
				break;

			/*********************************************
			 * Initial load
			 *********************************************/
			case POP_COREPROCESSORS_PAGE_LOADERS_INITIALFRAMES:

				if ($template_id == GD_TEMPLATE_PAGESECTION_ADDONTABS_PAGE) {

					$replicable[] = GD_TEMPLATE_BLOCK_ADDONTABS_CONTACTUSER;

					// Only if enabled
					if (defined('POP_GENERICFORMS_PAGE_VOLUNTEER')) {
						$replicable[] = GD_TEMPLATE_BLOCK_ADDONTABS_VOLUNTEER;
					}
					
					$replicable[] = GD_TEMPLATE_BLOCK_ADDONTABS_FLAG;
				}
				break;		

			// case POP_GENERICFORMS_PAGE_CONTACTUSER:
			// case POP_GENERICFORMS_PAGE_VOLUNTEER:
			// case POP_GENERICFORMS_PAGE_FLAG:

			// 	$add = 
			// 		($template_id == GD_TEMPLATE_PAGESECTION_PAGETABS_PAGE && $target == GD_URLPARAM_TARGET_MAIN) ||
			// 		($template_id == GD_TEMPLATE_PAGESECTION_ADDONTABS_PAGE && $target == GD_URLPARAM_TARGET_ADDONS);
			// 	if ($add) {
					
			// 		$blocks[] = GD_TEMPLATE_BLOCK_PAGETABS_PAGE;
			// 	}
			// 	break;		
		}

		GD_TemplateManager_Utils::add_blocks($ret, $blocks, GD_TEMPLATEBLOCKSETTINGS_MAIN);
		GD_TemplateManager_Utils::add_blocks($ret, $replicable, GD_TEMPLATEBLOCKSETTINGS_REPLICABLE);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new Wassup_GF_PageSectionSettingsProcessor();
