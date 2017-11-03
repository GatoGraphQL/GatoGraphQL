<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SIDEBAR_MENU_ABOUT', PoP_TemplateIDUtils::get_template_definition('sidebar-menu-about'));

class GD_Template_Processor_CustomMenuSidebars extends GD_Template_Processor_SidebarsBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SIDEBAR_MENU_ABOUT,
		);
	}

	function get_inner_template($template_id) {

		$sidebarinners = array(
			GD_TEMPLATE_SIDEBAR_MENU_ABOUT => GD_TEMPLATE_SIDEBARINNER_MENU_ABOUT,
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
new GD_Template_Processor_CustomMenuSidebars();