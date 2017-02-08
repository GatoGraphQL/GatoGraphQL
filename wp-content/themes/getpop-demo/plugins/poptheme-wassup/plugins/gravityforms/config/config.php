<?php

/**---------------------------------------------------------------------------------------------------------------
 * Custom Libraries
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter('gd_gf_contactus_topics', 'gd_gf_contactus_topics_impl');
function gd_gf_contactus_topics_impl($topics) {

	return array(
		__('General', 'getpop-demo'),
		__('Website', 'getpop-demo'),
		__('Workshop', 'getpop-demo'),
		__('Others', 'getpop-demo'),
	);
}