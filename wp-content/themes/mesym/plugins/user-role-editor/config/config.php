<?php

/**---------------------------------------------------------------------------------------------------------------
 * Custom Libraries
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter('gd_header_page_description', 'gd_ure_header_page_description_impl', 10, 2);
function gd_ure_header_page_description_impl($description, $page_id) {

	switch ($page_id) {

		case POP_URE_POPPROCESSORS_PAGE_ORGANIZATIONS:
		case POP_URE_POPPROCESSORS_PAGE_COMMUNITIES:

			$description = sprintf(GD_CONSTANT_PLACEHOLDER_DESCRIPTIONVIEWALL, __('Organizations', 'mesym'));
			break;

		case POP_URE_POPPROCESSORS_PAGE_INDIVIDUALS:

			$description = sprintf(GD_CONSTANT_PLACEHOLDER_DESCRIPTIONVIEWALL, __('People', 'mesym'));
			break;

		case POP_URE_POPPROCESSORS_PAGE_ADDPROFILEORGANIZATION:

			$description = sprintf(GD_CONSTANT_PLACEHOLDER_DESCRIPTIONADDYOUR, __('Organization Profile', 'mesym'));
			break;

		case POP_URE_POPPROCESSORS_PAGE_ADDPROFILEINDIVIDUAL:

			$description = sprintf(GD_CONSTANT_PLACEHOLDER_DESCRIPTIONADDYOUR, __('Individual Profile', 'mesym'));
			break;
	}

	return $description;
}