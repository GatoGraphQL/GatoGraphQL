<?php

/**---------------------------------------------------------------------------------------------------------------
 * Custom Libraries
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter('gd_gf_contactus_topics', 'gd_gf_contactus_topics_impl');
function gd_gf_contactus_topics_impl($topics) {

	return array(
		__('General', 'mesym'),
		__('Website', 'mesym'),
		__('Documentary Night', 'mesym'),
		__('Volunteering', 'mesym'),
		__('Sponsorship / Partnership', 'mesym'),
		__('Others', 'mesym'),
	);
}