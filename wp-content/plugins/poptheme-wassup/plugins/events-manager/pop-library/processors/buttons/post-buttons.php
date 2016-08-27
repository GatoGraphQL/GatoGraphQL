<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_CUSTOM_TEMPLATE_BUTTON_EVENT_CREATE', PoP_ServerUtils::get_template_definition('custom-postbutton-event-create'));
define ('GD_CUSTOM_TEMPLATE_BUTTON_EVENTLINK_CREATE', PoP_ServerUtils::get_template_definition('custom-postbutton-eventlink-create'));

class GD_Custom_EM_Template_Processor_Buttons extends GD_Custom_Template_Processor_ButtonsBase {

	function get_templates_to_process() {
	
		return array(
			GD_CUSTOM_TEMPLATE_BUTTON_EVENT_CREATE,
			GD_CUSTOM_TEMPLATE_BUTTON_EVENTLINK_CREATE,
		);
	}

	function get_buttoninner_template($template_id) {

		$buttoninners = array(
			GD_CUSTOM_TEMPLATE_BUTTON_EVENT_CREATE => GD_CUSTOM_TEMPLATE_BUTTONINNER_EVENT_CREATE,
			GD_CUSTOM_TEMPLATE_BUTTON_EVENTLINK_CREATE => GD_CUSTOM_TEMPLATE_BUTTONINNER_EVENTLINK_CREATE,
		);
		if ($buttoninner = $buttoninners[$template_id]) {

			return $buttoninner;
		}

		return parent::get_buttoninner_template($template_id);
	}

	function get_selectabletypeahead_template($template_id) {

		switch ($template_id) {
					
			case GD_CUSTOM_TEMPLATE_BUTTON_EVENT_CREATE:
			case GD_CUSTOM_TEMPLATE_BUTTON_EVENTLINK_CREATE:

				return GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES;
		}

		return parent::get_selectabletypeahead_template($template_id);
	}

	function get_linktarget($template_id, $atts) {

		switch ($template_id) {
					
			case GD_CUSTOM_TEMPLATE_BUTTON_EVENT_CREATE:
			case GD_CUSTOM_TEMPLATE_BUTTON_EVENTLINK_CREATE:

				if (PoPTheme_Wassup_Utils::get_addcontent_target() == GD_URLPARAM_TARGET_ADDONS) {
					
					return GD_URLPARAM_TARGET_ADDONS;
				}
				break;
		}

		return parent::get_linktarget($template_id, $atts);
	}

	function get_title($template_id) {

		$titles = array(
			GD_CUSTOM_TEMPLATE_BUTTON_EVENT_CREATE => __('Event', 'poptheme-wassup'),
			GD_CUSTOM_TEMPLATE_BUTTON_EVENTLINK_CREATE => __('Event link', 'poptheme-wassup'),
		);
		if ($title = $titles[$template_id]) {

			return $title;
		}
		
		return parent::get_title($template_id);
	}

	function get_url_field($template_id) {

		$fields = array(
			GD_CUSTOM_TEMPLATE_BUTTON_EVENT_CREATE => 'addevent-url',
			GD_CUSTOM_TEMPLATE_BUTTON_EVENTLINK_CREATE => 'addeventlink-url',
		);
		if ($field = $fields[$template_id]) {

			return $field;
		}
		
		return parent::get_url_field($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Custom_EM_Template_Processor_Buttons();