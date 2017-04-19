<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Configure the RSS Feed
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_URLPARAM_RSSCAMPAIGN', 'campaign');
define ('GD_URLPARAM_RSSCAMPAIGN_DAILYPOSTDIGEST', 'dailypost-digest');

/**---------------------------------------------------------------------------------------------------------------
 * How to invoke the feed:
 * Latest posts: 
 * - https://agendaurbana.org/events/feed/?campaign=weekly
 * ---------------------------------------------------------------------------------------------------------------*/

function pop_get_rss_postlist_campaigns() {

	 return apply_filters(
	 	'pop_get_rss_postlist_campaigns',
	 	array(
	 		GD_URLPARAM_RSSCAMPAIGN_DAILYPOSTDIGEST,
	 	)
	 );
} 

add_filter('pre_get_posts','poptheme_wassup_rss_filter');
function poptheme_wassup_rss_filter($query) {

	 if ($query->is_feed) {
	 
		// If it is the daily feed, then show only posts posted in the last 24 hs
		if ($_REQUEST[GD_URLPARAM_RSSCAMPAIGN] == GD_URLPARAM_RSSCAMPAIGN_DAILYPOSTDIGEST) {

			$query_today = array(
				'column'  => 'post_date',
				'after'   => '- 1 days'
			);
			$query->set('date_query', $query_today);
		}
	 }
	return $query;
} 

/**---------------------------------------------------------------------------------------------------------------
 * Add the author link around the name when invoking 'the author' hook
 * ---------------------------------------------------------------------------------------------------------------*/
add_action('rss2_ns', 'gd_rss_author_addlink');
function gd_rss_author_addlink() {

	if (is_feed()) {
		add_filter('the_author', 'gd_rss_author');
	}
}
function gd_rss_author($output) {

	// // If it's a feed, add also the URL of the author, and give it mailchimp's formatting
	// $campaigns = array(
	// 	GD_URLPARAM_RSSCAMPAIGN_DAILYPOSTDIGEST,
	// 	GD_URLPARAM_RSSCAMPAIGN_WEEKLY,
	// );
	// if (is_feed() && in_array($_REQUEST[GD_URLPARAM_RSSCAMPAIGN], $campaigns)) {

	global $authordata;
	$url = get_author_posts_url($authordata->ID);
	// $url = GD_TemplateManager_Utils::add_tab($url, POP_COREPROCESSORS_PAGE_DESCRIPTION);

	$output = sprintf(
		'<a href="%s" style="%s">%s</a>',
		$url,
		gd_rss_get_author_anchor_style(),
		$output
	);
	// }

	return $output;
}

function gd_rss_get_author_anchor_style() {

	return apply_filters(
		'poptheme_wassup_rss:anchor_style',
		'word-wrap:break-word;color:#7a7a7b;font-weight:normal;text-decoration:underline;'
	);
}

add_filter('gd_rss_print_featured_image:img_attr', 'gd_custom_rss_featuredimage_size');
function gd_custom_rss_featuredimage_size($img_attr) {

	// Change the pic dimensions for the weekly campaign
	// $campaigns = array(
	// 	GD_URLPARAM_RSSCAMPAIGN_DAILYPOSTDIGEST,
	// 	GD_URLPARAM_RSSCAMPAIGN_WEEKLY,
	// );
	// if (in_array($_REQUEST[GD_URLPARAM_RSSCAMPAIGN], $campaigns)) {
	if (in_array($_REQUEST[GD_URLPARAM_RSSCAMPAIGN], pop_get_rss_postlist_campaigns())) {

		$thumb_width = apply_filters(
			'poptheme_wassup_rss:thumb_width',
			132
		);	
		$img_attr[2] = $thumb_width / $img_attr[1] * $img_attr[2];
		$img_attr[1] = $thumb_width;
	}

	return $img_attr;
}
