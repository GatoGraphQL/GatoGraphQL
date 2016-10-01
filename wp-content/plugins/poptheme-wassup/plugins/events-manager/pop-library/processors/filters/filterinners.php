<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FILTERINNER_LOCATIONS', PoP_ServerUtils::get_template_definition('filterinner-locations'));
define ('GD_TEMPLATE_FILTERINNER_EVENTS', PoP_ServerUtils::get_template_definition('filterinner-events'));
define ('GD_TEMPLATE_FILTERINNER_AUTHOREVENTS', PoP_ServerUtils::get_template_definition('filterinner-authorevents'));
define ('GD_TEMPLATE_FILTERINNER_TAGEVENTS', PoP_ServerUtils::get_template_definition('filterinner-tagevents'));
define ('GD_TEMPLATE_FILTERINNER_EVENTSCALENDAR', PoP_ServerUtils::get_template_definition('filterinner-eventscalendar'));
define ('GD_TEMPLATE_FILTERINNER_AUTHOREVENTSCALENDAR', PoP_ServerUtils::get_template_definition('filterinner-authoreventscalendar'));
define ('GD_TEMPLATE_FILTERINNER_TAGEVENTSCALENDAR', PoP_ServerUtils::get_template_definition('filterinner-tageventscalendar'));
define ('GD_TEMPLATE_FILTERINNER_MYEVENTS', PoP_ServerUtils::get_template_definition('filterinner-myevents'));

class GD_EM_Template_Processor_CustomFilterInners extends GD_Template_Processor_FilterInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FILTERINNER_LOCATIONS,
			GD_TEMPLATE_FILTERINNER_EVENTS,
			GD_TEMPLATE_FILTERINNER_AUTHOREVENTS,
			GD_TEMPLATE_FILTERINNER_TAGEVENTS,
			GD_TEMPLATE_FILTERINNER_EVENTSCALENDAR,
			GD_TEMPLATE_FILTERINNER_AUTHOREVENTSCALENDAR,
			GD_TEMPLATE_FILTERINNER_TAGEVENTSCALENDAR,
			GD_TEMPLATE_FILTERINNER_MYEVENTS,
		);
	}
	
	function get_filter($template_id) {

		$filters = array(
			GD_TEMPLATE_FILTERINNER_LOCATIONS => GD_FILTER_LOCATIONS,
			GD_TEMPLATE_FILTERINNER_EVENTS => GD_FILTER_EVENTS,
			GD_TEMPLATE_FILTERINNER_AUTHOREVENTS => GD_FILTER_AUTHOREVENTS,
			GD_TEMPLATE_FILTERINNER_TAGEVENTS => GD_FILTER_TAGEVENTS,
			GD_TEMPLATE_FILTERINNER_EVENTSCALENDAR => GD_FILTER_EVENTSCALENDAR,
			GD_TEMPLATE_FILTERINNER_AUTHOREVENTSCALENDAR => GD_FILTER_AUTHOREVENTSCALENDAR,
			GD_TEMPLATE_FILTERINNER_TAGEVENTSCALENDAR => GD_FILTER_TAGEVENTSCALENDAR,
			GD_TEMPLATE_FILTERINNER_MYEVENTS => GD_FILTER_MYEVENTS,
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
new GD_EM_Template_Processor_CustomFilterInners();