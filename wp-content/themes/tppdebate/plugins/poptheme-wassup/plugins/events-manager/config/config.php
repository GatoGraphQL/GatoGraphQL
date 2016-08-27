<?php

/**---------------------------------------------------------------------------------------------------------------
 * Custom Libraries
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('gd_header_page_description', 'gd_em_header_page_description_impl', 10, 2);
function gd_em_header_page_description_impl($description, $page_id) {

	switch ($page_id) {

		case POPTHEME_WASSUP_EM_PAGE_EVENTS:
		case POPTHEME_WASSUP_EM_PAGE_EVENTSCALENDAR:

			$description = sprintf(GD_CONSTANT_PLACEHOLDER_DESCRIPTIONVIEWALL, __('Upcoming Events', 'tppdebate'));
			break;

		case POPTHEME_WASSUP_EM_PAGE_PASTEVENTS:

			$description = sprintf(GD_CONSTANT_PLACEHOLDER_DESCRIPTIONVIEWALL, __('Upcoming and Past Events', 'tppdebate'));
			break;
	}

	return $description;
}