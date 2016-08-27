<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_FIELDPROCESSOR_EVENTS', 'events');

class GD_DataLoad_FieldProcessor_Events extends GD_DataLoad_FieldProcessor_Posts {

	function get_name() {
	
		return GD_DATALOAD_FIELDPROCESSOR_EVENTS;
	}

	function cast($post) {

		return em_get_event($post->ID, 'post_id');
	}

	function get_id($resultitem) {
	
		return $resultitem->post_id;		
	}

	function get_value($resultitem, $field) {

		// First Check if there's a hook to implement this field
		$hook_value = $this->get_hook_value(GD_DATALOAD_FIELDPROCESSOR_EVENTS, $resultitem, $field);
		if (!is_wp_error($hook_value)) {
			return $hook_value;
		}	
	
		$event = $resultitem;		
		
		switch ($field) {

			// Override
			// case 'cat' :
			// 	$value = gd_em_get_category($event);
			// 	break;

			// Override the url param to point to the original file
			case 'location' :
				$value = $event->output('#_LOCATIONPOSTID');
				break;

			// Override 'locations' field
			case 'locations' :
				
				// Events can have no location
				$value = array();
				if ($location = $this->get_value($event, 'location')) {
					$value[] = $location;
				}
				break;

			// Override
			case 'cats' :
				$value = array();
				$cats = $event->get_categories();
				foreach ($cats as $cat) {
					$value[] = $cat->term_id;
				}
				break;

			// Override
			case 'cat-slugs' :
				$value = array();
				$cats = $event->get_categories();
				foreach ($cats as $cat) {
					$value[] = $cat->slug;
				}
				break;

			case 'dates' :
				$value = $event->output('#_EVENTDATES');
				break;

			case 'times' :
				$value = $event->output('#_EVENTTIMES');
				break;

			case 'start-date' :
				$value = $event->output('#_EVENTDATESTART');
				break;

			case 'start-date-string' :
				$value = date_i18n('d/m', $event->start);
				break;

			case 'end-date' :
				$value = $event->output('#_EVENTDATEEND');
				break;

			case 'all-day' :
				$value = $event->output('#_EVENTALLDAY');
				break;
			case 'all-day-string' :
				// $value = $this->get_yesno_select_string($resultitem, 'all-day');
				$value = GD_DataLoad_FieldProcessor_Utils::get_boolvalue_string($this->get_value($resultitem, 'all-day'));
				break;

			case 'bookings-html':
				$value = $event->output('{has_bookings}#_BOOKINGFORM{/has_bookings}{has_attendees}<br/>#_ATTENDEES{/has_attendees}');
				break;

			case 'googlecalendar':
				$value = $event->output('#_EVENTGCALURL');
				break;

			case 'ical':
				$value = $event->output('#_EVENTICALURL');
				break;

			case 'daterangetime' :
				$value = array(
					'from' => date_i18n('Y-m-d', $event->start),
					'to' => date_i18n('Y-m-d', $event->end),
					'fromtime' => date_i18n('H:i', $event->start),
					'totime' => date_i18n('H:i', $event->end)
				);
				break;

			case 'daterangetime-string' :
				$value = date_i18n('d/m/Y h:i A', $event->start) . GD_DATERANGE_SEPARATOR . date_i18n('d/m/Y h:i A', $event->end);
				break;

			default:
				$value = parent::get_value($resultitem, $field);
				break;																											
		}

		return $value;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_FieldProcessor_Events();