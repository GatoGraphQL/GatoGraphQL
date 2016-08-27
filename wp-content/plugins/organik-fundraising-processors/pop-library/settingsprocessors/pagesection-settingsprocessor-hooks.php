<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class OF_Processors_PageSectionSettingsProcessorHooks {

	function __construct() {

		add_filter(
			'PoPTheme_Wassup_PageSectionSettingsProcessor:sideinfo_home:blockgroup',
			array($this, 'get_sideinfo_home_blockgroup')
		);
	}

	function get_sideinfo_home_blockgroup($blockgroup) {

		// Override the Homepage Sidebar
		return GD_ORGANIKFUNDRAISING_TEMPLATE_BLOCKGROUP_HOMESECTION_ALLCONTENT_SIDEBAR;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new OF_Processors_PageSectionSettingsProcessorHooks();
