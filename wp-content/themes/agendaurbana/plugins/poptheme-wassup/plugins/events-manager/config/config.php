<?php

/**---------------------------------------------------------------------------------------------------------------
 * Custom Libraries
 * ---------------------------------------------------------------------------------------------------------------*/

/**---------------------------------------------------------------------------------------------------------------
 * locations_map.php
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('gd_locationsmap_latlng', 'gd_locationsmap_latlng_impl');
function gd_locationsmap_latlng_impl($values) {

	// Buenos Aires, Argentina
	return array('lat' => '-34.603684', 'lng' => '-58.381559', 'zoom' => 11, '1marker_zoom' => 12);
}

add_filter('gd_header_page_description', 'gd_em_header_page_description_impl', 10, 2);
function gd_em_header_page_description_impl($description, $page_id) {

	switch ($page_id) {

		case POPTHEME_WASSUP_EM_PAGE_EVENTS:
		case POPTHEME_WASSUP_EM_PAGE_EVENTSCALENDAR:

			$description = sprintf(GD_CONSTANT_PLACEHOLDER_DESCRIPTIONVIEWALL, __('Upcoming Events', 'agendaurbana'));
			break;

		case POPTHEME_WASSUP_EM_PAGE_PASTEVENTS:

			$description = sprintf(GD_CONSTANT_PLACEHOLDER_DESCRIPTIONVIEWALL, __('Upcoming and Past Events', 'agendaurbana'));
			break;
	}

	return $description;
}