<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_CUSTOM_TEMPLATE_BUTTONINNER_EVENT_CREATE', PoP_TemplateIDUtils::get_template_definition('custom-buttoninner-event-create'));
define ('GD_CUSTOM_TEMPLATE_BUTTONINNER_EVENTLINK_CREATE', PoP_TemplateIDUtils::get_template_definition('custom-buttoninner-eventlink-create'));

class GD_Custom_EM_Template_Processor_ButtonInners extends GD_Template_Processor_ButtonInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_CUSTOM_TEMPLATE_BUTTONINNER_EVENT_CREATE,
			GD_CUSTOM_TEMPLATE_BUTTONINNER_EVENTLINK_CREATE,
		);
	}

	function get_fontawesome($template_id, $atts) {

		$icons = array(
			GD_CUSTOM_TEMPLATE_BUTTONINNER_EVENT_CREATE => POPTHEME_WASSUP_EM_PAGE_ADDEVENT,
			GD_CUSTOM_TEMPLATE_BUTTONINNER_EVENTLINK_CREATE => POPTHEME_WASSUP_EM_PAGE_ADDEVENTLINK,
		);
		if ($icon = $icons[$template_id]) {

			return 'fa-fw '.gd_navigation_menu_item($icon, false);
		}
		
		return parent::get_fontawesome($template_id, $atts);
	}

	function get_btn_title($template_id) {
		
		$titles = array(
			GD_CUSTOM_TEMPLATE_BUTTONINNER_EVENT_CREATE => __('Event', 'poptheme-wassup'),
			GD_CUSTOM_TEMPLATE_BUTTONINNER_EVENTLINK_CREATE => __('as Link', 'poptheme-wassup'),//__('Event link', 'poptheme-wassup'),
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
new GD_Custom_EM_Template_Processor_ButtonInners();