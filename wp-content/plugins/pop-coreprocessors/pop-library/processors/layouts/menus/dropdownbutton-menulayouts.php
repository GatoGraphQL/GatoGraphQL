<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_MENU_DROPDOWNBUTTON_TOP', PoP_TemplateIDUtils::get_template_definition('layout-menu-dropdownbutton-top'));
define ('GD_TEMPLATE_LAYOUT_MENU_DROPDOWNBUTTON_SIDE', PoP_TemplateIDUtils::get_template_definition('layout-menu-dropdownbutton-side'));

class GD_Template_Processor_DropdownButtonMenuLayouts extends GD_Template_Processor_DropdownButtonMenuLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_MENU_DROPDOWNBUTTON_TOP,
			GD_TEMPLATE_LAYOUT_MENU_DROPDOWNBUTTON_SIDE,
		);
	}	

	function get_btn_class($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_MENU_DROPDOWNBUTTON_TOP:
				
				return 'btn btn-warning';

			case GD_TEMPLATE_LAYOUT_MENU_DROPDOWNBUTTON_SIDE:
				
				return 'btn btn-warning btn-block btn-addnew-side';
		}
	
		return parent::get_btn_class($template_id, $atts);
	}	

	function get_btn_title($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_MENU_DROPDOWNBUTTON_TOP:
				
				return '<i class="fa fa-fw fa-plus"></i>';
			
			case GD_TEMPLATE_LAYOUT_MENU_DROPDOWNBUTTON_SIDE:
				
				return '<i class="fa fa-fw fa-plus"></i>'.__('Add new', 'pop-coreprocessors').' <span class="caret"></span>';
		}
	
		return parent::get_btn_title($template_id, $atts);
	}

	function get_dropdownbtn_class($template_id, $atts) {
	
		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_MENU_DROPDOWNBUTTON_TOP:
				
				return 'dropdown';
		}
	
		return parent::get_dropdownbtn_class($template_id, $atts);
	}

	function inner_list($template_id, $atts) {
	
		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_MENU_DROPDOWNBUTTON_TOP:
				
				return true;
		}
	
		return parent::inner_list($template_id, $atts);
	}
	
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_DropdownButtonMenuLayouts();