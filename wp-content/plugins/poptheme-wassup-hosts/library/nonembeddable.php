<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Thumbnails
 *
 * ---------------------------------------------------------------------------------------------------------------*/

/**---------------------------------------------------------------------------------------------------------------
 * Thumbs for the Links depending on their domain
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter('GD_DataLoad_FieldProcessor_Posts_Utils:link_content:nonembeddable_hosts', 'wassup_nonembeddablehosts');
function wassup_nonembeddablehosts($nonembeddable) {

	return array_merge(
		$nonembeddable,
		array(
			'www.dropbox.com',
			'www.academia.edu',
			'www.slideshare.net',
			'www.facebook.com',
			'www.fb.com',
			'www.theguardian.com',
			'www.eff.org',
			// 'www.techdirt.com',
			// 'techdirt.com',
			'www.reddit.com',
			'reddit.com',
			'theconversation.com',
			'www.google.com',
		)
	);
}

// add_filter('GD_DataLoad_FieldProcessor_Posts_Utils:link_content:embeddable_hosts', 'wassup_embeddablehosts');
// function wassup_embeddablehosts($embeddable) {

// 	return array_merge(
// 		$embeddable,
// 		array(
// 			'us3.campaign-archive1.com', // Mailchimp archive
// 			'www.malaysiakini.com',
// 			'www.thestar.com.my',
// 		)
// 	);
// }