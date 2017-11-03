<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_CALENDARCONTROLBUTTONGROUP_CALENDAR', PoP_TemplateIDUtils::get_template_definition('calendarcontrolbuttongroup-calendar'));

class GD_Template_Processor_CalendarControlButtonGroups extends GD_Template_Processor_ControlButtonGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_CALENDARCONTROLBUTTONGROUP_CALENDAR,
		);
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);
	
		switch ($template_id) {
		
			case GD_TEMPLATE_CALENDARCONTROLBUTTONGROUP_CALENDAR:

				$ret[] = GD_TEMPLATE_CALENDARBUTTONCONTROL_CALENDARPREV;
				$ret[] = GD_TEMPLATE_CALENDARBUTTONCONTROL_CALENDARNEXT;
				break;
		}
		
		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CalendarControlButtonGroups();