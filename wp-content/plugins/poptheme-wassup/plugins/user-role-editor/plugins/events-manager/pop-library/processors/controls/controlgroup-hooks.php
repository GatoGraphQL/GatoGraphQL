<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Latest Counts Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPThemeWassup_URE_EM_ControlGroup_Hooks {

	function __construct() {

		add_filter(
			'GD_EM_Template_Processor_CustomControlGroups:blockauthoreventlist:layouts', 
			array($this, 'get_layouts')
		);

		// Also the Past Events link on the Author Events top controlgroup
		add_filter(
			'GD_EM_Template_Processor_CustomAnchorControls:pastevents:url', 
			'gd_ure_add_source_param', 
			10, 
		2);
	}

	function get_layouts($layouts) {

		global $author;

		// Add the Switch Organization/Organization+Members if the author is an organization
		if (gd_ure_is_community($author)) {
				
			array_unshift($layouts, GD_URE_TEMPLATE_CONTROLBUTTONGROUP_CONTENTSOURCE);
		}

		return $layouts;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPThemeWassup_URE_EM_ControlGroup_Hooks();
