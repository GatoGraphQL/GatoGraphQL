<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_EM_TEMPLATE_BUTTONINNER_GOOGLECALENDAR', PoP_TemplateIDUtils::get_template_definition('em-buttoninner-googlecalendar'));
define ('GD_EM_TEMPLATE_BUTTONINNER_ICAL', PoP_TemplateIDUtils::get_template_definition('em-buttoninner-ical'));

class GD_EM_Template_Processor_ButtonInners extends GD_Template_Processor_ButtonInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_EM_TEMPLATE_BUTTONINNER_GOOGLECALENDAR,
			GD_EM_TEMPLATE_BUTTONINNER_ICAL,
		);
	}

	function get_fontawesome($template_id, $atts) {
		
		switch ($template_id) {

			case GD_EM_TEMPLATE_BUTTONINNER_GOOGLECALENDAR:

				return 'fa-fw fa-thumb-tack';
			
			case GD_EM_TEMPLATE_BUTTONINNER_ICAL:

				return 'fa-fw fa-download';
		}

		return parent::get_fontawesome($template_id, $atts);
	}

	function get_btn_title($template_id) {
		
		switch ($template_id) {

			case GD_EM_TEMPLATE_BUTTONINNER_GOOGLECALENDAR:

				return __('Google Calendar', 'em-popprocessors');
			
			case GD_EM_TEMPLATE_BUTTONINNER_ICAL:

				return __('iCal', 'em-popprocessors');
		}

		return parent::get_btn_title($template_id);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_EM_Template_Processor_ButtonInners();