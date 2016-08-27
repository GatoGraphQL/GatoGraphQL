<?php

add_filter('GD_Core_Template_Processor_Blocks:inviteusers:description', 'ofp_inviteusers_description');
function ofp_inviteusers_description($description) {

	// Modify the description of the Invite New Users block, since for the Organik Fundraising website it is used as "Share by email"
	return sprintf(
		'<p>%s</p>',
		__('Encourage your friends to support this initiative:', 'organik-fundraising-processors')
		// sprintf(
		// 	__('Invite your friends to donate for <em><strong>%s</strong></em>:', 'organik-fundraising-processors'),
		// 	get_bloginfo('name')
		// )
	);
}