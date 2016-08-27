<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Events Manager Implementations under Local Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

/**---------------------------------------------------------------------------------------------------------------
 * Create page on the initial user welcome email
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('sendemail_userwelcome:create_pages', 'em_wassup_createpages');
function em_wassup_createpages($pages) {

	$pages = array_merge(
		$pages,
		array_filter(
			array(
				POPTHEME_WASSUP_EM_PAGE_ADDEVENT,
				POPTHEME_WASSUP_EM_PAGE_ADDEVENTLINK,
			)
		)
	);

	return $pages;
}