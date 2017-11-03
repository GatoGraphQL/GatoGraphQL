<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_CALENDAR_EVENTS_NAVIGATOR', PoP_TemplateIDUtils::get_template_definition('calendar-events-navigator'));
define ('GD_TEMPLATE_CALENDAR_EVENTS_ADDONS', PoP_TemplateIDUtils::get_template_definition('calendar-events-addons'));
define ('GD_TEMPLATE_CALENDAR_EVENTS_MAIN', PoP_TemplateIDUtils::get_template_definition('calendar-events-main'));
define ('GD_TEMPLATE_CALENDAR_EVENTSMAP', PoP_TemplateIDUtils::get_template_definition('calendar-eventsmap'));

class GD_EM_Template_Processor_Calendars extends GD_Template_Processor_CalendarsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_CALENDAR_EVENTS_NAVIGATOR,
			GD_TEMPLATE_CALENDAR_EVENTS_ADDONS,
			GD_TEMPLATE_CALENDAR_EVENTS_MAIN,
			GD_TEMPLATE_CALENDAR_EVENTSMAP,
			// GD_TEMPLATE_CALENDAR_EVENTS_WIDGET
		);
	}

	function get_inner_template($template_id) {

		$inners = array(
			GD_TEMPLATE_CALENDAR_EVENTS_NAVIGATOR => GD_TEMPLATE_CALENDARINNER_EVENTS_NAVIGATOR,
			GD_TEMPLATE_CALENDAR_EVENTS_ADDONS => GD_TEMPLATE_CALENDARINNER_EVENTS_ADDONS,
			GD_TEMPLATE_CALENDAR_EVENTS_MAIN => GD_TEMPLATE_CALENDARINNER_EVENTS_MAIN,
			GD_TEMPLATE_CALENDAR_EVENTSMAP => GD_TEMPLATE_CALENDARINNER_EVENTSMAP,
		);

		if ($inner = $inners[$template_id]) {

			return $inner;
		}
			
		return parent::get_inner_template($template_id);
	}

	function get_options($template_id, $atts) {

		$ret = parent::get_options($template_id, $atts);

		switch ($template_id) {

			case GD_TEMPLATE_CALENDAR_EVENTS_NAVIGATOR:
			case GD_TEMPLATE_CALENDAR_EVENTS_ADDONS:
			
				// Comment Leo 12/08/2016: if adding directly the first letter, then it can't be translated, so use the full name and get the first letter for each day
				// $ret['dayNamesShort'] = array('S', 'M', 'T', 'W', 'T', 'F', 'S');

				// Comment Leo 23/08/2016: There was a bug, in which the website crashed in Chinese, and it was from using substr on Chinese characters. So use mb_substr which has support for UTF-8
				$ret['dayNamesShort'] = array(
					mb_substr(__('Sunday', 'poptheme-wassup'), 0, 1, 'UTF-8'),
					mb_substr(__('Monday', 'poptheme-wassup'), 0, 1, 'UTF-8'),
					mb_substr(__('Tuesday', 'poptheme-wassup'), 0, 1, 'UTF-8'),
					mb_substr(__('Wednesday', 'poptheme-wassup'), 0, 1, 'UTF-8'),
					mb_substr(__('Thursday', 'poptheme-wassup'), 0, 1, 'UTF-8'),
					mb_substr(__('Friday', 'poptheme-wassup'), 0, 1, 'UTF-8'),
					mb_substr(__('Saturday', 'poptheme-wassup'), 0, 1, 'UTF-8'),
				);
				$ret['titleFormat'] = 'MMM YYYY';
				break;
		}
			
		return $ret;
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);
		
		switch ($template_id) {

			case GD_TEMPLATE_CALENDAR_EVENTSMAP:
			
				$this->add_jsmethod($ret, 'waypointsTheater');
				break;
		}

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_CALENDAR_EVENTS_NAVIGATOR:
			case GD_TEMPLATE_CALENDAR_EVENTS_ADDONS:
			
				// Do not show the Title in the Calendar Navigator, no space
				$this->add_att(GD_TEMPLATE_LAYOUTCALENDAR_CONTENT_POPOVER, $atts, 'show-title', false);
				break;
			
			case GD_TEMPLATE_CALENDAR_EVENTSMAP:

				// Make the offcanvas theater when the scroll reaches top of the page
				$this->append_att($template_id, $atts, 'class', 'waypoint');
				$this->merge_att($template_id, $atts, 'params', array(
					'data-toggle' => 'theater'
				));
				break;
		}

		switch ($template_id) {

			case GD_TEMPLATE_CALENDAR_EVENTS_MAIN:
			case GD_TEMPLATE_CALENDAR_EVENTSMAP:
			
				// Make it activeItem: highlight on viewing the corresponding fullview
				// $this->append_att(GD_TEMPLATE_LAYOUTCALENDAR, $atts, 'class', 'pop-openmapmarkers');
				$this->append_att(GD_TEMPLATE_LAYOUT_POPOVER_EVENT, $atts, 'class', 'pop-openmapmarkers');
				break;
		}
			
		return parent::init_atts($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_EM_Template_Processor_Calendars();