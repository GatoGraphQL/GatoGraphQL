<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_MENU_BUTTON', PoP_TemplateIDUtils::get_template_definition('layout-menu-button'));

class GD_Template_Processor_AnchorMenuLayouts extends GD_Template_Processor_AnchorMenuLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_MENU_BUTTON
		);
	}	

	function get_item_class($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_MENU_BUTTON:
				
				return 'btn btn-default btn-block';
		}
	
		return parent::get_item_class($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_AnchorMenuLayouts();