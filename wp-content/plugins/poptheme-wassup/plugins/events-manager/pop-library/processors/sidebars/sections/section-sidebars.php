<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SIDEBAR_SECTION_EVENTS', PoP_ServerUtils::get_template_definition('sidebar-section-events'));
define ('GD_TEMPLATE_SIDEBAR_SECTION_PASTEVENTS', PoP_ServerUtils::get_template_definition('sidebar-section-pastevents'));
define ('GD_TEMPLATE_SIDEBAR_SECTION_EVENTS_CALENDAR', PoP_ServerUtils::get_template_definition('sidebar-section-events-calendar'));
define ('GD_TEMPLATE_SIDEBAR_SECTION_MYEVENTS', PoP_ServerUtils::get_template_definition('sidebar-section-myevents'));
define ('GD_TEMPLATE_SIDEBAR_SECTION_MYPASTEVENTS', PoP_ServerUtils::get_template_definition('sidebar-section-mypastevents'));
define ('GD_TEMPLATE_SIDEBAR_SECTION_AUTHOREVENTS', PoP_ServerUtils::get_template_definition('sidebar-section-authorevents'));
define ('GD_TEMPLATE_SIDEBAR_SECTION_AUTHORPASTEVENTS', PoP_ServerUtils::get_template_definition('sidebar-section-authorpastevents'));
define ('GD_TEMPLATE_SIDEBAR_SECTION_AUTHOREVENTSCALENDAR', PoP_ServerUtils::get_template_definition('sidebar-section-authoreventscalendar'));

class GD_EM_Template_Processor_CustomSectionSidebars extends GD_Template_Processor_SidebarsBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SIDEBAR_SECTION_EVENTS,
			GD_TEMPLATE_SIDEBAR_SECTION_PASTEVENTS,
			GD_TEMPLATE_SIDEBAR_SECTION_EVENTS_CALENDAR,
			
			GD_TEMPLATE_SIDEBAR_SECTION_MYEVENTS,
			GD_TEMPLATE_SIDEBAR_SECTION_MYPASTEVENTS,

			GD_TEMPLATE_SIDEBAR_SECTION_AUTHOREVENTS,
			GD_TEMPLATE_SIDEBAR_SECTION_AUTHORPASTEVENTS,
			GD_TEMPLATE_SIDEBAR_SECTION_AUTHOREVENTSCALENDAR,
		);
	}

	function get_inner_template($template_id) {

		$sidebarinners = array(
			GD_TEMPLATE_SIDEBAR_SECTION_EVENTS => GD_TEMPLATE_SIDEBARINNER_SECTION_EVENTS,
			GD_TEMPLATE_SIDEBAR_SECTION_PASTEVENTS => GD_TEMPLATE_SIDEBARINNER_SECTION_PASTEVENTS,
			GD_TEMPLATE_SIDEBAR_SECTION_EVENTS_CALENDAR => GD_TEMPLATE_SIDEBARINNER_SECTION_EVENTS_CALENDAR,
			GD_TEMPLATE_SIDEBAR_SECTION_MYEVENTS => GD_TEMPLATE_SIDEBARINNER_SECTION_MYEVENTS,
			GD_TEMPLATE_SIDEBAR_SECTION_MYPASTEVENTS => GD_TEMPLATE_SIDEBARINNER_SECTION_MYPASTEVENTS,
			
			GD_TEMPLATE_SIDEBAR_SECTION_MYEVENTS => GD_TEMPLATE_SIDEBARINNER_SECTION_MYEVENTS,
			GD_TEMPLATE_SIDEBAR_SECTION_MYPASTEVENTS => GD_TEMPLATE_SIDEBARINNER_SECTION_MYPASTEVENTS,

			GD_TEMPLATE_SIDEBAR_SECTION_AUTHOREVENTS => GD_TEMPLATE_SIDEBARINNER_SECTION_AUTHOREVENTS,
			GD_TEMPLATE_SIDEBAR_SECTION_AUTHORPASTEVENTS => GD_TEMPLATE_SIDEBARINNER_SECTION_AUTHORPASTEVENTS,
			GD_TEMPLATE_SIDEBAR_SECTION_AUTHOREVENTSCALENDAR => GD_TEMPLATE_SIDEBARINNER_SECTION_AUTHOREVENTSCALENDAR,
		);

		if ($inner = $sidebarinners[$template_id]) {
			return $inner;
		}

		return parent::get_inner_template($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_EM_Template_Processor_CustomSectionSidebars();