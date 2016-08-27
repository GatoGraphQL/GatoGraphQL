<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_CALENDARCONTROLGROUP_CALENDAR', PoP_ServerUtils::get_template_definition('calendarcontrolgroup-calendar'));

class GD_Template_Processor_CalendarControlGroups extends GD_Template_Processor_ControlGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_CALENDARCONTROLGROUP_CALENDAR
		);
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_CALENDARCONTROLGROUP_CALENDAR:
			
				$ret[] = GD_TEMPLATE_CALENDARCONTROLBUTTONGROUP_CALENDAR;
				break;
		}

		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CalendarControlGroups();