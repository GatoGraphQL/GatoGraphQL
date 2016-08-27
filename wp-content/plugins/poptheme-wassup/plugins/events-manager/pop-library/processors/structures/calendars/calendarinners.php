<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_CALENDARINNER_EVENTS_NAVIGATOR', PoP_ServerUtils::get_template_definition('calendarinner-events-navigator'));
define ('GD_TEMPLATE_CALENDARINNER_EVENTS_ADDONS', PoP_ServerUtils::get_template_definition('calendarinner-events-addons'));
define ('GD_TEMPLATE_CALENDARINNER_EVENTS_MAIN', PoP_ServerUtils::get_template_definition('calendarinner-events-main'));
define ('GD_TEMPLATE_CALENDARINNER_EVENTSMAP', PoP_ServerUtils::get_template_definition('calendarinner-eventsmap'));

class GD_EM_Template_Processor_CalendarInners extends GD_Template_Processor_CalendarInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_CALENDARINNER_EVENTS_NAVIGATOR,
			GD_TEMPLATE_CALENDARINNER_EVENTS_ADDONS,
			GD_TEMPLATE_CALENDARINNER_EVENTS_MAIN,
			GD_TEMPLATE_CALENDARINNER_EVENTSMAP,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_CALENDARINNER_EVENTS_NAVIGATOR:
			case GD_TEMPLATE_CALENDARINNER_EVENTS_ADDONS:
			case GD_TEMPLATE_CALENDARINNER_EVENTS_MAIN:
			case GD_TEMPLATE_CALENDARINNER_EVENTSMAP:
			
				$ret[] = GD_TEMPLATE_LAYOUT_POPOVER_EVENT;
				break;
		}
			
		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_EM_Template_Processor_CalendarInners();