<?php

/**---------------------------------------------------------------------------------------------------------------
 * Custom Libraries
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter('gd_gf_contactus_topics', 'gd_gf_contactus_topics_impl');
function gd_gf_contactus_topics_impl($topics) {

	return array(
		__('General', 'getpop'),
		__('Website', 'getpop'),
		__('Workshop', 'getpop'),
		__('Others', 'getpop'),
	);
}