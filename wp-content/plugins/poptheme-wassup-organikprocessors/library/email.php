<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Functions for the MESYM Processors
 *
 * ---------------------------------------------------------------------------------------------------------------*/

/**---------------------------------------------------------------------------------------------------------------
 * Create page on the initial user welcome email
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('sendemail_userwelcome:create_pages', 'op_wassup_createpages');
function op_wassup_createpages($pages) {

	$pages = array_merge(
		$pages,
		array_filter(
			array(
				POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_ADDFARM,
				POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_ADDFARMLINK,
			)
		)
	);

	return $pages;
}
