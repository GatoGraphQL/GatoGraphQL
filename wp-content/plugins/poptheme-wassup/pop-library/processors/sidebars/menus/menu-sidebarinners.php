<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SIDEBARINNER_MENU_ABOUT', PoP_TemplateIDUtils::get_template_definition('sidebarinner-menu-about'));

class GD_Template_Processor_CustomMenuSidebarInners extends GD_Template_Processor_SidebarInnersBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SIDEBARINNER_MENU_ABOUT,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_SIDEBARINNER_MENU_ABOUT:

				$ret[] = GD_TEMPLATE_WIDGET_MENU_ABOUT;
				break;
		}
		
		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CustomMenuSidebarInners();