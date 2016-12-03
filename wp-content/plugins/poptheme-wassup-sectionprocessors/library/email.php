<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Functions for the MESYM Processors
 *
 * ---------------------------------------------------------------------------------------------------------------*/

/**---------------------------------------------------------------------------------------------------------------
 * Create page on the initial user welcome email
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('sendemail_userwelcome:create_pages', 'custom_wassup_createpages');
function custom_wassup_createpages($pages) {

	$pages = array_merge(
		$pages,
		array_filter(
			array(
				POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDLOCATIONPOST,
				POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDLOCATIONPOSTLINK,
				POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDSTORY,
				POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDSTORYLINK,
				POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDANNOUNCEMENT,
				POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDANNOUNCEMENTLINK,
				POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDDISCUSSION,
				POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDDISCUSSIONLINK,
			)
		)
	);

	return $pages;
}
