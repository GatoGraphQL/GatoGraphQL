<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SIMPLEFILTERINNER_LOCATIONS', PoP_ServerUtils::get_template_definition('simplefilterinner-locations'));
define ('GD_TEMPLATE_SIMPLEFILTERINNER_EVENTS', PoP_ServerUtils::get_template_definition('simplefilterinner-events'));
define ('GD_TEMPLATE_SIMPLEFILTERINNER_AUTHOREVENTS', PoP_ServerUtils::get_template_definition('simplefilterinner-authorevents'));
define ('GD_TEMPLATE_SIMPLEFILTERINNER_EVENTSCALENDAR', PoP_ServerUtils::get_template_definition('simplefilterinner-eventscalendar'));
define ('GD_TEMPLATE_SIMPLEFILTERINNER_AUTHOREVENTSCALENDAR', PoP_ServerUtils::get_template_definition('simplefilterinner-authoreventscalendar'));
define ('GD_TEMPLATE_SIMPLEFILTERINNER_MYEVENTS', PoP_ServerUtils::get_template_definition('simplefilterinner-myevents'));

class GD_EM_Template_Processor_CustomSimpleFilterInners extends GD_Template_Processor_SimpleFilterInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SIMPLEFILTERINNER_LOCATIONS,
			GD_TEMPLATE_SIMPLEFILTERINNER_EVENTS,
			GD_TEMPLATE_SIMPLEFILTERINNER_AUTHOREVENTS,
			GD_TEMPLATE_SIMPLEFILTERINNER_EVENTSCALENDAR,
			GD_TEMPLATE_SIMPLEFILTERINNER_AUTHOREVENTSCALENDAR,
			GD_TEMPLATE_SIMPLEFILTERINNER_MYEVENTS,
		);
	}
	
	function get_filter($template_id) {

		$filters = array(
			GD_TEMPLATE_SIMPLEFILTERINNER_LOCATIONS => GD_FILTER_LOCATIONS,
			GD_TEMPLATE_SIMPLEFILTERINNER_EVENTS => GD_FILTER_EVENTS,
			GD_TEMPLATE_SIMPLEFILTERINNER_AUTHOREVENTS => GD_FILTER_AUTHOREVENTS,
			GD_TEMPLATE_SIMPLEFILTERINNER_EVENTSCALENDAR => GD_FILTER_EVENTSCALENDAR,
			GD_TEMPLATE_SIMPLEFILTERINNER_AUTHOREVENTSCALENDAR => GD_FILTER_AUTHOREVENTSCALENDAR,
			GD_TEMPLATE_SIMPLEFILTERINNER_MYEVENTS => GD_FILTER_MYEVENTS,
		);
		if ($filter = $filters[$template_id]) {

			return $filter;
		}
		
		return parent::get_filter($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_EM_Template_Processor_CustomSimpleFilterInners();