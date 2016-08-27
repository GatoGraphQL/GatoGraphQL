<?php

/**---------------------------------------------------------------------------------------------------------------
 * Custom Libraries
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter('gd_gf_contactus_topics', 'gd_gf_contactus_topics_impl');
function gd_gf_contactus_topics_impl($topics) {

	return array(
		__('General', 'tppdebate'),
		__('Website', 'tppdebate'),
		__('Media', 'tppdebate'),
		__('Contributing content', 'tppdebate'),
		__('Others', 'tppdebate'),
	);
}