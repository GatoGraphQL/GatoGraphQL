<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SIDEBAR_SECTION_ORGANIZATIONS', PoP_ServerUtils::get_template_definition('sidebar-section-organizations'));
define ('GD_TEMPLATE_SIDEBAR_SECTION_INDIVIDUALS', PoP_ServerUtils::get_template_definition('sidebar-section-individuals'));
define ('GD_TEMPLATE_SIDEBAR_SECTION_MYMEMBERS', PoP_ServerUtils::get_template_definition('sidebar-section-mymembers'));

// class GD_URE_Template_Processor_CustomSectionSidebars extends GD_Template_Processor_SectionSidebarsBase {
class GD_URE_Template_Processor_CustomSectionSidebars extends GD_Template_Processor_SidebarsBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SIDEBAR_SECTION_ORGANIZATIONS, 
			GD_TEMPLATE_SIDEBAR_SECTION_INDIVIDUALS,
			GD_TEMPLATE_SIDEBAR_SECTION_MYMEMBERS,
		);
	}

	function get_inner_template($template_id) {

		$sidebarinners = array(
			GD_TEMPLATE_SIDEBAR_SECTION_ORGANIZATIONS => GD_TEMPLATE_SIDEBARINNER_SECTION_ORGANIZATIONS,
			GD_TEMPLATE_SIDEBAR_SECTION_INDIVIDUALS => GD_TEMPLATE_SIDEBARINNER_SECTION_INDIVIDUALS,
			GD_TEMPLATE_SIDEBAR_SECTION_MYMEMBERS => GD_TEMPLATE_SIDEBARINNER_SECTION_MYMEMBERS,
		);

		if ($inner = $sidebarinners[$template_id]) {
			return $inner;
		}

		return parent::get_inner_template($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_CustomSectionSidebars();