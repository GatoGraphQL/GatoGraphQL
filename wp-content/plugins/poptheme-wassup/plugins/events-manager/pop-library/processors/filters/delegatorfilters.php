<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_DELEGATORFILTER_AUTHOREVENTS', PoP_TemplateIDUtils::get_template_definition('delegatorfilter-authorevents'));
define ('GD_TEMPLATE_DELEGATORFILTER_TAGEVENTS', PoP_TemplateIDUtils::get_template_definition('delegatorfilter-tagevents'));
define ('GD_TEMPLATE_DELEGATORFILTER_AUTHOREVENTSCALENDAR', PoP_TemplateIDUtils::get_template_definition('delegatorfilter-authoreventscalendar'));
define ('GD_TEMPLATE_DELEGATORFILTER_TAGEVENTSCALENDAR', PoP_TemplateIDUtils::get_template_definition('delegatorfilter-tageventscalendar'));
define ('GD_TEMPLATE_DELEGATORFILTER_EVENTS', PoP_TemplateIDUtils::get_template_definition('delegatorfilter-events'));
define ('GD_TEMPLATE_DELEGATORFILTER_EVENTSCALENDAR', PoP_TemplateIDUtils::get_template_definition('delegatorfilter-eventscalendar'));
define ('GD_TEMPLATE_DELEGATORFILTER_LOCATIONS', PoP_TemplateIDUtils::get_template_definition('delegatorfilter-locations'));
define ('GD_TEMPLATE_DELEGATORFILTER_MYEVENTS', PoP_TemplateIDUtils::get_template_definition('delegatorfilter-myevents'));

class GD_EM_Template_Processor_CustomDelegatorFilters extends GD_Template_Processor_CustomDelegatorFiltersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_DELEGATORFILTER_LOCATIONS,
			GD_TEMPLATE_DELEGATORFILTER_EVENTS,
			GD_TEMPLATE_DELEGATORFILTER_AUTHOREVENTS,
			GD_TEMPLATE_DELEGATORFILTER_TAGEVENTS,
			GD_TEMPLATE_DELEGATORFILTER_EVENTSCALENDAR,
			GD_TEMPLATE_DELEGATORFILTER_AUTHOREVENTSCALENDAR,
			GD_TEMPLATE_DELEGATORFILTER_TAGEVENTSCALENDAR,
			GD_TEMPLATE_DELEGATORFILTER_MYEVENTS,
		);
	}
	
	function get_inner_template($template_id) {

		$inners = array(
			GD_TEMPLATE_DELEGATORFILTER_LOCATIONS => GD_TEMPLATE_SIMPLEFILTERINNER_LOCATIONS,
			GD_TEMPLATE_DELEGATORFILTER_EVENTS => GD_TEMPLATE_SIMPLEFILTERINNER_EVENTS,
			GD_TEMPLATE_DELEGATORFILTER_AUTHOREVENTS => GD_TEMPLATE_SIMPLEFILTERINNER_AUTHOREVENTS,
			GD_TEMPLATE_DELEGATORFILTER_TAGEVENTS => GD_TEMPLATE_SIMPLEFILTERINNER_TAGEVENTS,
			GD_TEMPLATE_DELEGATORFILTER_EVENTSCALENDAR => GD_TEMPLATE_SIMPLEFILTERINNER_EVENTSCALENDAR,
			GD_TEMPLATE_DELEGATORFILTER_AUTHOREVENTSCALENDAR => GD_TEMPLATE_SIMPLEFILTERINNER_AUTHOREVENTSCALENDAR,
			GD_TEMPLATE_DELEGATORFILTER_TAGEVENTSCALENDAR => GD_TEMPLATE_SIMPLEFILTERINNER_TAGEVENTSCALENDAR,
			GD_TEMPLATE_DELEGATORFILTER_MYEVENTS => GD_TEMPLATE_SIMPLEFILTERINNER_MYEVENTS,
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
new GD_EM_Template_Processor_CustomDelegatorFilters();