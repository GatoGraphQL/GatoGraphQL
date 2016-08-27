<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Styles
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// Do not send email to the user when creating a highlight
// Do not send email to referenced post authors when creating a highlight
add_filter('create_post:skip_sendemail', 'gd_email_skipsending', 10, 2);
add_filter('post_references:skip_sendemail', 'gd_email_skipsending', 10, 2);
function gd_email_skipsending($skip, $post_id) {

	if (get_post_type($post_id) == 'post') {

		$skip_cats = array(
			POPTHEME_WASSUP_CAT_HIGHLIGHTS,
		);
		if (in_array(gd_get_the_main_category($post_id), $skip_cats)) {
			return true;
		}
	}

	return $skip;
}


/**---------------------------------------------------------------------------------------------------------------
 * Create page on the initial user welcome email
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('sendemail_userwelcome:create_pages', 'wassup_createpages');
function wassup_createpages($pages) {

	$pages = array_merge(
		$pages,
		array_filter(
			array(
				POPTHEME_WASSUP_PAGE_ADDWEBPOST,
				POPTHEME_WASSUP_PAGE_ADDWEBPOSTLINK,
			)
		)
	);

	return $pages;
}