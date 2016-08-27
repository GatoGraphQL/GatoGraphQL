<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Events Manager Implementations under Local Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/


add_filter('gd_thumb_defaultlink:link_categories', 'gd_em_link_categories');
function gd_em_link_categories($link_cats) {

	return array_merge(
		$link_cats,
		array_filter(
			array(
				POPTHEME_WASSUP_EM_CAT_EVENTLINKS,
			)
		)
	);
}
