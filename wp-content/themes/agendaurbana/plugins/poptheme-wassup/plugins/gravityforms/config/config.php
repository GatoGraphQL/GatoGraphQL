<?php

/**---------------------------------------------------------------------------------------------------------------
 * Custom Libraries
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter('gd_gf_contactus_topics', 'gd_gf_contactus_topics_impl');
function gd_gf_contactus_topics_impl($topics) {

	return array(
		__('General', 'agendaurbana'),
		__('Website', 'agendaurbana'),
		__('Workshop', 'agendaurbana'),
		__('Others', 'agendaurbana'),
	);
}