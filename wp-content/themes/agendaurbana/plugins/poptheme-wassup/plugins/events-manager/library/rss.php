<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Configure the RSS Feed
 *
 * ---------------------------------------------------------------------------------------------------------------*/

/**---------------------------------------------------------------------------------------------------------------
 * How to invoke the feed:
 * Events: 
 * - https://www.ripessasia.org/feed/?cat=1398,1108,1110,1109,1107&campaign=weekly
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter('gd_em_rss:author', 'gd_em_rss_author', 10, 2);
function gd_em_rss_author($output, $EM_Event) {

	// Add also the URL of the author, and give it mailchimp's formatting
	if ($_REQUEST[GD_RSS_URLPARAM_CAMPAIGN] == GD_CUSTOM_RSS_URLPARAM_CAMPAIGN_WEEKLY) {

		$url = $EM_Event->output('#_EVENTAUTHORURL');
		// $url = GD_TemplateManager_Utils::add_tab($url, POP_COREPROCESSORS_PAGE_DESCRIPTION);
		$output = sprintf(
			'<a href="%s" style="%s">%s</a>',
			$url,
			GD_CUSTOM_MAILCHIMP_STYLE_ANCHOR,
			$output
		);
	}

	return $output;
}
