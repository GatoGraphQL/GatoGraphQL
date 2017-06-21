<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Latest Counts Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPThemeWassup_URE_ControlGroup_Hooks {

	function __construct() {

		add_filter(
			'GD_Template_Processor_CustomControlGroups:blockauthorpostlist:layouts', 
			array($this, 'get_layouts')
		);
	}

	function get_layouts($layouts) {

		$vars = GD_TemplateManager_Utils::get_vars();
		$author = $vars['global-state']['author']/*global $author*/;

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
new PoPThemeWassup_URE_ControlGroup_Hooks();
