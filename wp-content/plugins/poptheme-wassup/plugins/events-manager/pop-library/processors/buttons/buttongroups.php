<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BUTTONGROUP_CALENDARSECTION', PoP_TemplateIDUtils::get_template_definition('buttongroup-calendarsection'));
define ('GD_TEMPLATE_BUTTONGROUP_TAGCALENDARSECTION', PoP_TemplateIDUtils::get_template_definition('buttongroup-tagcalendarsection'));
define ('GD_TEMPLATE_BUTTONGROUP_AUTHORCALENDARSECTION', PoP_TemplateIDUtils::get_template_definition('buttongroup-authorcalendarsection'));

class GD_Custom_EM_Template_Processor_ButtonGroups extends GD_Template_Processor_CustomButtonGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BUTTONGROUP_CALENDARSECTION,
			GD_TEMPLATE_BUTTONGROUP_TAGCALENDARSECTION,
			GD_TEMPLATE_BUTTONGROUP_AUTHORCALENDARSECTION,
		);
	}

	protected function get_headersdata_screen($template_id, $atts) {

		switch ($template_id) {
			
			case GD_TEMPLATE_BUTTONGROUP_CALENDARSECTION:

				return POP_SCREEN_SECTIONCALENDAR;
			
			case GD_TEMPLATE_BUTTONGROUP_TAGCALENDARSECTION:

				return POP_SCREEN_TAGSECTIONCALENDAR;

			case GD_TEMPLATE_BUTTONGROUP_AUTHORCALENDARSECTION:
			
				return POP_SCREEN_AUTHORSECTIONCALENDAR;
			}

		return parent::get_headersdata_screen($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Custom_EM_Template_Processor_ButtonGroups();