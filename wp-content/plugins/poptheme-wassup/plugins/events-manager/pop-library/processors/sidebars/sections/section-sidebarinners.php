<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SIDEBARINNER_SECTION_EVENTS', PoP_ServerUtils::get_template_definition('sidebarinner-section-events'));
define ('GD_TEMPLATE_SIDEBARINNER_SECTION_PASTEVENTS', PoP_ServerUtils::get_template_definition('sidebarinner-section-pastevents'));
define ('GD_TEMPLATE_SIDEBARINNER_SECTION_EVENTS_CALENDAR', PoP_ServerUtils::get_template_definition('sidebarinner-section-events-calendar'));
define ('GD_TEMPLATE_SIDEBARINNER_SECTION_MYEVENTS', PoP_ServerUtils::get_template_definition('sidebarinner-section-myevents'));
define ('GD_TEMPLATE_SIDEBARINNER_SECTION_MYPASTEVENTS', PoP_ServerUtils::get_template_definition('sidebarinner-section-mypastevents'));
define ('GD_TEMPLATE_SIDEBARINNER_TAG_EVENTS', PoP_ServerUtils::get_template_definition('sidebarinner-tag-events'));
define ('GD_TEMPLATE_SIDEBARINNER_TAG_PASTEVENTS', PoP_ServerUtils::get_template_definition('sidebarinner-tag-pastevents'));
define ('GD_TEMPLATE_SIDEBARINNER_TAG_EVENTS_CALENDAR', PoP_ServerUtils::get_template_definition('sidebarinner-tag-events-calendar'));
define ('GD_TEMPLATE_SIDEBARINNER_SECTION_AUTHOREVENTS', PoP_ServerUtils::get_template_definition('sidebarinner-section-authorevents'));
define ('GD_TEMPLATE_SIDEBARINNER_SECTION_AUTHORPASTEVENTS', PoP_ServerUtils::get_template_definition('sidebarinner-section-authorpastevents'));
define ('GD_TEMPLATE_SIDEBARINNER_SECTION_AUTHOREVENTSCALENDAR', PoP_ServerUtils::get_template_definition('sidebarinner-section-authoreventscalendar'));

class GD_EM_Template_Processor_CustomSectionSidebarInners extends GD_Template_Processor_SidebarInnersBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SIDEBARINNER_SECTION_EVENTS,
			GD_TEMPLATE_SIDEBARINNER_SECTION_PASTEVENTS,
			GD_TEMPLATE_SIDEBARINNER_SECTION_EVENTS_CALENDAR,
			
			GD_TEMPLATE_SIDEBARINNER_SECTION_MYEVENTS,
			GD_TEMPLATE_SIDEBARINNER_SECTION_MYPASTEVENTS,

			GD_TEMPLATE_SIDEBARINNER_TAG_EVENTS,
			GD_TEMPLATE_SIDEBARINNER_TAG_PASTEVENTS,
			GD_TEMPLATE_SIDEBARINNER_TAG_EVENTS_CALENDAR,
			
			GD_TEMPLATE_SIDEBARINNER_SECTION_AUTHOREVENTS,
			GD_TEMPLATE_SIDEBARINNER_SECTION_AUTHORPASTEVENTS,
			GD_TEMPLATE_SIDEBARINNER_SECTION_AUTHOREVENTSCALENDAR,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		switch ($template_id) {
					
			case GD_TEMPLATE_SIDEBARINNER_SECTION_EVENTS:
			case GD_TEMPLATE_SIDEBARINNER_SECTION_PASTEVENTS:

				$ret[] = GD_TEMPLATE_BUTTONGROUP_SECTIONWITHMAP;//GD_TEMPLATE_BUTTONGROUP_EVENTS;
				$ret[] = GD_TEMPLATE_DELEGATORFILTER_EVENTS;
				break;

			case GD_TEMPLATE_SIDEBARINNER_SECTION_EVENTS_CALENDAR:

				$ret[] = GD_TEMPLATE_BUTTONGROUP_CALENDARSECTION;//GD_TEMPLATE_BUTTONGROUP_EVENTSCALENDAR;
				$ret[] = GD_TEMPLATE_DELEGATORFILTER_EVENTSCALENDAR;
				break;

			case GD_TEMPLATE_SIDEBARINNER_SECTION_MYEVENTS:

				$ret[] = GD_TEMPLATE_BUTTONGROUP_MYCONTENT;//GD_TEMPLATE_BUTTONGROUP_MYEVENTS;
				$ret[] = GD_TEMPLATE_DELEGATORFILTER_MYEVENTS;
				break;

			case GD_TEMPLATE_SIDEBARINNER_SECTION_MYPASTEVENTS:
				
				$ret[] = GD_TEMPLATE_BUTTONGROUP_MYCONTENT;//GD_TEMPLATE_BUTTONGROUP_MYPASTEVENTS;
				$ret[] = GD_TEMPLATE_DELEGATORFILTER_MYEVENTS;
				break;
					
			case GD_TEMPLATE_SIDEBARINNER_TAG_EVENTS:
			case GD_TEMPLATE_SIDEBARINNER_TAG_PASTEVENTS:

				$ret[] = GD_TEMPLATE_BUTTONGROUP_TAGSECTIONWITHMAP;
				$ret[] = GD_TEMPLATE_DELEGATORFILTER_TAGEVENTS;
				break;

			case GD_TEMPLATE_SIDEBARINNER_SECTION_EVENTS_CALENDAR:

				$ret[] = GD_TEMPLATE_BUTTONGROUP_TAGCALENDARSECTION;
				$ret[] = GD_TEMPLATE_DELEGATORFILTER_TAGEVENTSCALENDAR;
				break;

			case GD_TEMPLATE_SIDEBARINNER_SECTION_AUTHOREVENTS:
			case GD_TEMPLATE_SIDEBARINNER_SECTION_AUTHORPASTEVENTS:

				$ret[] = GD_TEMPLATE_BUTTONGROUP_AUTHORSECTIONWITHMAP;//GD_TEMPLATE_BUTTONGROUP_EVENTS;
				$ret[] = GD_TEMPLATE_DELEGATORFILTER_AUTHOREVENTS;
				break;

			case GD_TEMPLATE_SIDEBARINNER_SECTION_AUTHOREVENTSCALENDAR:

				$ret[] = GD_TEMPLATE_BUTTONGROUP_AUTHORCALENDARSECTION;
				$ret[] = GD_TEMPLATE_DELEGATORFILTER_AUTHOREVENTS;
				break;

		}
		
		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_EM_Template_Processor_CustomSectionSidebarInners();