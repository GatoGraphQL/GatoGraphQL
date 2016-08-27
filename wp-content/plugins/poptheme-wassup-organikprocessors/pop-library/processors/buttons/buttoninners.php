<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_CUSTOM_TEMPLATE_BUTTONINNER_FARM_CREATE', PoP_ServerUtils::get_template_definition('custom-buttoninner-farm-create'));
define ('GD_CUSTOM_TEMPLATE_BUTTONINNER_FARMLINK_CREATE', PoP_ServerUtils::get_template_definition('custom-buttoninner-farmlink-create'));

class OP_Template_Processor_ButtonInners extends GD_Template_Processor_ButtonInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_CUSTOM_TEMPLATE_BUTTONINNER_FARM_CREATE,
			GD_CUSTOM_TEMPLATE_BUTTONINNER_FARMLINK_CREATE,
		);
	}

	function get_fontawesome($template_id, $atts) {

		$icons = array(
			GD_CUSTOM_TEMPLATE_BUTTONINNER_FARM_CREATE => POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_ADDFARM,
			GD_CUSTOM_TEMPLATE_BUTTONINNER_FARMLINK_CREATE => POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_ADDFARMLINK,
		);
		if ($icon = $icons[$template_id]) {

			return 'fa-fw '.gd_navigation_menu_item($icon, false);
		}
		
		return parent::get_fontawesome($template_id, $atts);
	}

	function get_btn_title($template_id) {
		
		$titles = array(
			GD_CUSTOM_TEMPLATE_BUTTONINNER_FARM_CREATE => __('Farm', 'poptheme-wassup-organikprocessors'),
			GD_CUSTOM_TEMPLATE_BUTTONINNER_FARMLINK_CREATE => __('as Link', 'poptheme-wassup-organikprocessors'),//__('Farm link', 'poptheme-wassup-organikprocessors'),
		);
		if ($title = $titles[$template_id]) {

			return $title;
		}
		
		return parent::get_btn_title($template_id);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new OP_Template_Processor_ButtonInners();