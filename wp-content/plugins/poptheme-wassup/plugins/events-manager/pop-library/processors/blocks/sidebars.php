<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCK_SECTION_EVENTS_CALENDAR_SIDEBAR', PoP_ServerUtils::get_template_definition('block-events-calendar-sidebar'));
define ('GD_TEMPLATE_BLOCK_SECTION_EVENTS_SIDEBAR', PoP_ServerUtils::get_template_definition('block-section-events-sidebar'));
define ('GD_TEMPLATE_BLOCK_SECTION_PASTEVENTS_SIDEBAR', PoP_ServerUtils::get_template_definition('block-section-pastevents-sidebar'));
define ('GD_TEMPLATE_BLOCK_SECTION_MYEVENTS_SIDEBAR', PoP_ServerUtils::get_template_definition('block-section-myevents-sidebar'));
define ('GD_TEMPLATE_BLOCK_SECTION_MYPASTEVENTS_SIDEBAR', PoP_ServerUtils::get_template_definition('block-section-mypastevents-sidebar'));
define ('GD_TEMPLATE_BLOCK_TAG_EVENTS_CALENDAR_SIDEBAR', PoP_ServerUtils::get_template_definition('block-tag-events-calendar-sidebar'));
define ('GD_TEMPLATE_BLOCK_TAG_EVENTS_SIDEBAR', PoP_ServerUtils::get_template_definition('block-tag-events-sidebar'));
define ('GD_TEMPLATE_BLOCK_TAG_PASTEVENTS_SIDEBAR', PoP_ServerUtils::get_template_definition('block-tag-pastevents-sidebar'));
define ('GD_TEMPLATE_BLOCK_SINGLE_EVENT_SIDEBAR', PoP_ServerUtils::get_template_definition('block-single-event-sidebar'));
define ('GD_TEMPLATE_BLOCK_SINGLE_PASTEVENT_SIDEBAR', PoP_ServerUtils::get_template_definition('block-single-pastevent-sidebar'));
define ('GD_TEMPLATE_BLOCK_AUTHOREVENTS_SIDEBAR', PoP_ServerUtils::get_template_definition('block-authorevents-sidebar'));
define ('GD_TEMPLATE_BLOCK_AUTHORPASTEVENTS_SIDEBAR', PoP_ServerUtils::get_template_definition('block-authorpastevents-sidebar'));
define ('GD_TEMPLATE_BLOCK_AUTHOREVENTSCALENDAR_SIDEBAR', PoP_ServerUtils::get_template_definition('block-authoreventscalendar-sidebar'));

class GD_EM_Template_Processor_CustomSidebarBlocks extends GD_Template_Processor_CustomSidebarBlocksBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCK_SECTION_EVENTS_CALENDAR_SIDEBAR,
			GD_TEMPLATE_BLOCK_SECTION_EVENTS_SIDEBAR,
			GD_TEMPLATE_BLOCK_SECTION_PASTEVENTS_SIDEBAR,

			GD_TEMPLATE_BLOCK_SECTION_MYEVENTS_SIDEBAR,
			GD_TEMPLATE_BLOCK_SECTION_MYPASTEVENTS_SIDEBAR,

			GD_TEMPLATE_BLOCK_TAG_EVENTS_CALENDAR_SIDEBAR,
			GD_TEMPLATE_BLOCK_TAG_EVENTS_SIDEBAR,
			GD_TEMPLATE_BLOCK_TAG_PASTEVENTS_SIDEBAR,

			GD_TEMPLATE_BLOCK_SINGLE_EVENT_SIDEBAR,
			GD_TEMPLATE_BLOCK_SINGLE_PASTEVENT_SIDEBAR,

			GD_TEMPLATE_BLOCK_AUTHOREVENTS_SIDEBAR,
			GD_TEMPLATE_BLOCK_AUTHORPASTEVENTS_SIDEBAR,
			GD_TEMPLATE_BLOCK_AUTHOREVENTSCALENDAR_SIDEBAR,
		);
	}

	function get_filter_template($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_SECTION_EVENTS_CALENDAR_SIDEBAR:

				return GD_TEMPLATE_FILTER_EVENTSCALENDAR;

			case GD_TEMPLATE_BLOCK_SECTION_EVENTS_SIDEBAR:
			case GD_TEMPLATE_BLOCK_SECTION_PASTEVENTS_SIDEBAR:

				return GD_TEMPLATE_FILTER_EVENTS;

			case GD_TEMPLATE_BLOCK_SECTION_MYEVENTS_SIDEBAR:
			case GD_TEMPLATE_BLOCK_SECTION_MYPASTEVENTS_SIDEBAR:

				return GD_TEMPLATE_FILTER_MYEVENTS;

			case GD_TEMPLATE_BLOCK_TAG_EVENTS_CALENDAR_SIDEBAR:

				return GD_TEMPLATE_FILTER_TAGEVENTSCALENDAR;

			case GD_TEMPLATE_BLOCK_TAG_EVENTS_SIDEBAR:
			case GD_TEMPLATE_BLOCK_TAG_PASTEVENTS_SIDEBAR:

				return GD_TEMPLATE_FILTER_TAGEVENTS;

			case GD_TEMPLATE_BLOCK_AUTHOREVENTS_SIDEBAR:
			case GD_TEMPLATE_BLOCK_AUTHORPASTEVENTS_SIDEBAR:

				return GD_TEMPLATE_FILTER_AUTHOREVENTS;

			case GD_TEMPLATE_BLOCK_AUTHOREVENTSCALENDAR_SIDEBAR:

				return GD_TEMPLATE_FILTER_AUTHOREVENTSCALENDAR;
		}
		
		return parent::get_filter_template($template_id);
	}

	protected function get_block_inner_templates($template_id) {

		$ret = parent::get_block_inner_templates($template_id);

		$orientation = apply_filters(POP_HOOK_BLOCKSIDEBARS_ORIENTATION, 'vertical');
		$vertical = ($orientation == 'vertical');

		$block_inners = array(
			GD_TEMPLATE_BLOCK_SECTION_EVENTS_CALENDAR_SIDEBAR => GD_TEMPLATE_SIDEBAR_SECTION_EVENTS_CALENDAR,
			GD_TEMPLATE_BLOCK_SECTION_EVENTS_SIDEBAR => GD_TEMPLATE_SIDEBAR_SECTION_EVENTS,
			GD_TEMPLATE_BLOCK_SECTION_PASTEVENTS_SIDEBAR => GD_TEMPLATE_SIDEBAR_SECTION_PASTEVENTS,
			GD_TEMPLATE_BLOCK_SECTION_MYEVENTS_SIDEBAR => GD_TEMPLATE_SIDEBAR_SECTION_MYEVENTS,
			GD_TEMPLATE_BLOCK_SECTION_MYPASTEVENTS_SIDEBAR => GD_TEMPLATE_SIDEBAR_SECTION_MYPASTEVENTS,

			GD_TEMPLATE_BLOCK_TAG_EVENTS_CALENDAR_SIDEBAR => GD_TEMPLATE_SIDEBAR_TAG_EVENTS_CALENDAR,
			GD_TEMPLATE_BLOCK_TAG_EVENTS_SIDEBAR => GD_TEMPLATE_SIDEBAR_TAG_EVENTS,
			GD_TEMPLATE_BLOCK_TAG_PASTEVENTS_SIDEBAR => GD_TEMPLATE_SIDEBAR_TAG_PASTEVENTS,
			
			GD_TEMPLATE_BLOCK_SINGLE_EVENT_SIDEBAR => $vertical ? GD_TEMPLATE_VERTICALSIDEBAR_SINGLE_EVENT : GD_TEMPLATE_LAYOUT_POSTSIDEBAR_HORIZONTAL_EVENT,
			GD_TEMPLATE_BLOCK_SINGLE_PASTEVENT_SIDEBAR => $vertical ? GD_TEMPLATE_VERTICALSIDEBAR_SINGLE_PASTEVENT : GD_TEMPLATE_LAYOUT_POSTSIDEBAR_HORIZONTAL_PASTEVENT,

			GD_TEMPLATE_BLOCK_AUTHOREVENTS_SIDEBAR => GD_TEMPLATE_SIDEBAR_SECTION_AUTHOREVENTS,
			GD_TEMPLATE_BLOCK_AUTHORPASTEVENTS_SIDEBAR => GD_TEMPLATE_SIDEBAR_SECTION_AUTHORPASTEVENTS,
			GD_TEMPLATE_BLOCK_AUTHOREVENTSCALENDAR_SIDEBAR => GD_TEMPLATE_SIDEBAR_SECTION_AUTHOREVENTSCALENDAR,
		);

		if ($block_inner = $block_inners[$template_id]) {

			$ret[] = $block_inner;
		}
	
		return $ret;
	}

	protected function get_block_hierarchy($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_AUTHOREVENTS_SIDEBAR:
			case GD_TEMPLATE_BLOCK_AUTHORPASTEVENTS_SIDEBAR:
			case GD_TEMPLATE_BLOCK_AUTHOREVENTSCALENDAR_SIDEBAR:
				
				return GD_SETTINGS_HIERARCHY_AUTHOR;
				
			case GD_TEMPLATE_BLOCK_TAG_EVENTS_CALENDAR_SIDEBAR:
			case GD_TEMPLATE_BLOCK_TAG_EVENTS_SIDEBAR:
			case GD_TEMPLATE_BLOCK_TAG_PASTEVENTS_SIDEBAR:
				
				return GD_SETTINGS_HIERARCHY_TAG;

			case GD_TEMPLATE_BLOCK_SINGLE_EVENT_SIDEBAR:
			case GD_TEMPLATE_BLOCK_SINGLE_PASTEVENT_SIDEBAR:

				return GD_SETTINGS_HIERARCHY_SINGLE;
		}
		
		return parent::get_block_hierarchy($template_id);
	}


	function get_dataloader($template_id) {

		switch ($template_id) {
					
			case GD_TEMPLATE_BLOCK_SINGLE_EVENT_SIDEBAR:
			case GD_TEMPLATE_BLOCK_SINGLE_PASTEVENT_SIDEBAR:

				return GD_DATALOADER_EVENTSINGLE;
		}
		
		return parent::get_dataloader($template_id);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_SINGLE_PASTEVENT_SIDEBAR:
			
				$daterange_class = 'daterange-past opens-left';
				break;
		}
		if ($daterange_class) {
			$this->add_att(GD_TEMPLATE_FILTERFORMCOMPONENT_DATERANGEPICKER, $atts, 'daterange-class', $daterange_class);
		}

		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_EM_Template_Processor_CustomSidebarBlocks();