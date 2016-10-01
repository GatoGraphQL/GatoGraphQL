<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FILTER_AUTHOREVENTS', PoP_ServerUtils::get_template_definition('filter-authorevents'));
define ('GD_TEMPLATE_FILTER_TAGEVENTS', PoP_ServerUtils::get_template_definition('filter-tagevents'));
define ('GD_TEMPLATE_FILTER_AUTHOREVENTSCALENDAR', PoP_ServerUtils::get_template_definition('filter-authoreventscalendar'));
define ('GD_TEMPLATE_FILTER_TAGEVENTSCALENDAR', PoP_ServerUtils::get_template_definition('filter-tageventscalendar'));
define ('GD_TEMPLATE_FILTER_EVENTS', PoP_ServerUtils::get_template_definition('filter-events'));
define ('GD_TEMPLATE_FILTER_EVENTSCALENDAR', PoP_ServerUtils::get_template_definition('filter-eventscalendar'));
define ('GD_TEMPLATE_FILTER_LOCATIONS', PoP_ServerUtils::get_template_definition('filter-locations'));
define ('GD_TEMPLATE_FILTER_MYEVENTS', PoP_ServerUtils::get_template_definition('filter-myevents'));

class GD_EM_Template_Processor_CustomFilters extends GD_Template_Processor_FiltersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FILTER_LOCATIONS,
			GD_TEMPLATE_FILTER_EVENTS,
			GD_TEMPLATE_FILTER_AUTHOREVENTS,
			GD_TEMPLATE_FILTER_TAGEVENTS,
			GD_TEMPLATE_FILTER_EVENTSCALENDAR,
			GD_TEMPLATE_FILTER_AUTHOREVENTSCALENDAR,
			GD_TEMPLATE_FILTER_TAGEVENTSCALENDAR,
			GD_TEMPLATE_FILTER_MYEVENTS,
		);
	}
	
	function get_inner_template($template_id) {

		$inners = array(
			GD_TEMPLATE_FILTER_LOCATIONS => GD_TEMPLATE_FILTERINNER_LOCATIONS,
			GD_TEMPLATE_FILTER_EVENTS => GD_TEMPLATE_FILTERINNER_EVENTS,
			GD_TEMPLATE_FILTER_AUTHOREVENTS => GD_TEMPLATE_FILTERINNER_AUTHOREVENTS,
			GD_TEMPLATE_FILTER_TAGEVENTS => GD_TEMPLATE_FILTERINNER_TAGEVENTS,
			GD_TEMPLATE_FILTER_EVENTSCALENDAR => GD_TEMPLATE_FILTERINNER_EVENTSCALENDAR,
			GD_TEMPLATE_FILTER_AUTHOREVENTSCALENDAR => GD_TEMPLATE_FILTERINNER_AUTHOREVENTSCALENDAR,
			GD_TEMPLATE_FILTER_TAGEVENTSCALENDAR => GD_TEMPLATE_FILTERINNER_TAGEVENTSCALENDAR,
			GD_TEMPLATE_FILTER_MYEVENTS => GD_TEMPLATE_FILTERINNER_MYEVENTS,
		);

		if ($inner = $inners[$template_id]) {

			return $inner;
		}
	
		return parent::get_inner_template($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_EM_Template_Processor_CustomFilters();