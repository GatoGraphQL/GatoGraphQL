<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SIDEBAR_SECTION_FARMS', PoP_ServerUtils::get_template_definition('sidebar-section-farms'));
define ('GD_TEMPLATE_SIDEBAR_SECTION_TAGFARMS', PoP_ServerUtils::get_template_definition('sidebar-section-tagfarms'));
define ('GD_TEMPLATE_SIDEBAR_SECTION_AUTHORFARMS', PoP_ServerUtils::get_template_definition('sidebar-section-authorfarms'));
define ('GD_TEMPLATE_SIDEBAR_SECTION_MYFARMS', PoP_ServerUtils::get_template_definition('sidebar-section-myfarms'));

class OP_Template_Processor_CustomSectionSidebars extends GD_Template_Processor_SidebarsBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SIDEBAR_SECTION_FARMS, 
			GD_TEMPLATE_SIDEBAR_SECTION_TAGFARMS, 
			GD_TEMPLATE_SIDEBAR_SECTION_AUTHORFARMS,
			GD_TEMPLATE_SIDEBAR_SECTION_MYFARMS,
		);
	}

	function get_inner_template($template_id) {

		$sidebarinners = array(
			GD_TEMPLATE_SIDEBAR_SECTION_FARMS => GD_TEMPLATE_SIDEBARINNER_SECTION_FARMS, 
			GD_TEMPLATE_SIDEBAR_SECTION_TAGFARMS => GD_TEMPLATE_SIDEBARINNER_SECTION_TAGFARMS, 
			GD_TEMPLATE_SIDEBAR_SECTION_AUTHORFARMS => GD_TEMPLATE_SIDEBARINNER_SECTION_AUTHORFARMS, 
			GD_TEMPLATE_SIDEBAR_SECTION_MYFARMS => GD_TEMPLATE_SIDEBARINNER_SECTION_MYFARMS,
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
new OP_Template_Processor_CustomSectionSidebars();