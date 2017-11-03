<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_EM_TEMPLATE_BUTTON_GOOGLECALENDAR', PoP_TemplateIDUtils::get_template_definition('em-button-googlecalendar'));
define ('GD_EM_TEMPLATE_BUTTON_ICAL', PoP_TemplateIDUtils::get_template_definition('em-button-ical'));

class GD_EM_Template_Processor_Buttons extends GD_Template_Processor_ButtonsBase {

	function get_templates_to_process() {
	
		return array(
			GD_EM_TEMPLATE_BUTTON_GOOGLECALENDAR,
			GD_EM_TEMPLATE_BUTTON_ICAL,
		);
	}

	function get_buttoninner_template($template_id) {

		switch ($template_id) {

			case GD_EM_TEMPLATE_BUTTON_GOOGLECALENDAR:

				return GD_EM_TEMPLATE_BUTTONINNER_GOOGLECALENDAR;

			case GD_EM_TEMPLATE_BUTTON_ICAL:

				return GD_EM_TEMPLATE_BUTTONINNER_ICAL;
		}

		return parent::get_buttoninner_template($template_id);
	}

	function get_url_field($template_id) {
		
		switch ($template_id) {

			case GD_EM_TEMPLATE_BUTTON_GOOGLECALENDAR:

				return 'googlecalendar';
		
			case GD_EM_TEMPLATE_BUTTON_ICAL:

				return 'ical';
		}

		return parent::get_url_field($template_id);
	}

	function get_linktarget($template_id, $atts) {
		
		switch ($template_id) {

			case GD_EM_TEMPLATE_BUTTON_GOOGLECALENDAR:
			case GD_EM_TEMPLATE_BUTTON_ICAL:

				return '_blank';
		}
		
		return parent::get_linktarget($template_id, $atts);
	}

	function get_title($template_id) {
		
		switch ($template_id) {

			case GD_EM_TEMPLATE_BUTTON_GOOGLECALENDAR:

				return __('Google Calendar', 'em-popprocessors');

			case GD_EM_TEMPLATE_BUTTON_ICAL:

				return __('iCal', 'em-popprocessors');
		}
		
		return parent::get_title($template_id);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_EM_Template_Processor_Buttons();