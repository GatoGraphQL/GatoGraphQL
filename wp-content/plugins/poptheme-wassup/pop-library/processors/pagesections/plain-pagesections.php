<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

/**---------------------------------------------------------------------------------------------------------------
 * All IDs
 * ---------------------------------------------------------------------------------------------------------------*/
define ('GD_TEMPLATEID_PAGESECTIONID_OPERATIONAL', 'ps-operational');

/**---------------------------------------------------------------------------------------------------------------
 * All PageSections
 * ---------------------------------------------------------------------------------------------------------------*/
define ('GD_TEMPLATE_PAGESECTION_OPERATIONAL', PoP_ServerUtils::get_template_definition('operational', true));

class GD_Template_Processor_CustomPlainPageSections extends GD_Template_Processor_PlainPageSectionsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_PAGESECTION_OPERATIONAL,
		);
	}

	function get_id($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_PAGESECTION_OPERATIONAL:

				return GD_TEMPLATEID_PAGESECTIONID_OPERATIONAL;
		}

		return parent::get_id($template_id, $atts);
	}

	protected function get_blocks($template_id) {

		$ret = parent::get_blocks($template_id);
		$vars = GD_TemplateManager_Utils::get_vars();

		switch ($template_id) {

			case GD_TEMPLATE_PAGESECTION_OPERATIONAL:
				
				PoPTheme_Wassup_PageSectionSettingsUtils::add_page_blockunits($ret, $template_id);
				break;
		}

		if (GD_TemplateManager_Utils::loading_frame()) {
			
			switch ($template_id) {

				// Add the blockunits to be replicated on runtime. Only when first loading the website
				case GD_TEMPLATE_PAGESECTION_OPERATIONAL:

					// Special case: Add the blocks for Unique blocks directly, without using function add_blocks
					// since that function removes the uniqueblocks
					$ret[GD_TEMPLATEBLOCKSETTINGS_MAIN] = array_merge(
						$ret[GD_TEMPLATEBLOCKSETTINGS_MAIN],
						GD_TemplateManager_Utils::get_unique_blocks()
					);
					$ret[GD_TEMPLATEBLOCKSETTINGS_BLOCKGROUP] = array_merge(
						$ret[GD_TEMPLATEBLOCKSETTINGS_BLOCKGROUP],
						GD_TemplateManager_Utils::get_unique_blockgroups()
					);

					// If the page is not cacheable, then we can already get the state of the logged in user
					// Otherwise, this info will come from calling page LOGGEDINUSER_DATA from the frontend
					if (GD_TemplateManager_Utils::page_requires_user_state()) {
						$ret[GD_TEMPLATEBLOCKSETTINGS_BLOCKGROUP][] = GD_TEMPLATE_BLOCKGROUP_LOGGEDINUSERDATA;
					}
					break;
			}
		}

		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CustomPlainPageSections();
