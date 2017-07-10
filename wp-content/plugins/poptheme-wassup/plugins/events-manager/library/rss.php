<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Configure the RSS Feed
 *
 * ---------------------------------------------------------------------------------------------------------------*/
define ('GD_URLPARAM_RSSCAMPAIGN_UPCOMINGEVENTS_3DAYS', 'events-3days');
define ('GD_URLPARAM_RSSCAMPAIGN_UPCOMINGEVENTS_4DAYS', 'events-4days');

function gd_em_rss() {
	
	$vars = GD_TemplateManager_Utils::get_vars();
	$post = $vars['global-state']['post']/*global $post*/;
	global $wp_query, $wpdb;

	// Taken from plugins/events-manager/events-manager.php function em_rss()
	// Calling https://www.mesym.com/events/feed/ does not produce the EM feed
	// So correct it here by checking if we're in the EM_URI
	if (is_feed() && strpos(full_url(), EM_URI) === 0) {
		//events feed - show it all
		$wp_query->is_feed = true; //make is_feed() return true AIO SEO fix
		ob_start();
		em_locate_template('templates/rss.php', true, array('args'=>$args));
		echo apply_filters('em_rss', ob_get_clean());
		die();
	}
}
add_action ( 'template_redirect', 'gd_em_rss' );


/**---------------------------------------------------------------------------------------------------------------
 * Add the Featured Image to the feed
 * ---------------------------------------------------------------------------------------------------------------*/
add_action( 'em:rss2_ns', 'gd_rss_namespace' );
add_action( 'em:rss2_item', 'gd_em_rss_featured_image', 10, 1 );
function gd_em_rss_featured_image($EM_Event) {
    
    gd_rss_print_featured_image($EM_Event->post_id);
}


/**---------------------------------------------------------------------------------------------------------------
 * Author URL for events
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('gd_em_rss:author', 'gd_em_rss_author', 10, 2);
function gd_em_rss_author($output, $EM_Event) {

	// Add also the URL of the author, and give it mailchimp's formatting
	// if ($_REQUEST[GD_URLPARAM_RSSCAMPAIGN] == GD_URLPARAM_RSSCAMPAIGN_) {

	$url = $EM_Event->output('#_EVENTAUTHORURL');
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

add_filter('pop_get_rss_postlist_campaigns', 'pop_em_get_rss_postlist_campaigns');
function pop_em_get_rss_postlist_campaigns($campaigns) {

	 return array_merge(
	 	$campaigns,
	 	array(
	 		GD_URLPARAM_RSSCAMPAIGN_UPCOMINGEVENTS_3DAYS,
	 		GD_URLPARAM_RSSCAMPAIGN_UPCOMINGEVENTS_4DAYS,
	 	)
	 );
} 

/**---------------------------------------------------------------------------------------------------------------
 * Scope for getting events
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('em_rss_template_args', 'pop_em_rss_template_args');
function pop_em_rss_template_args($args) {

	if (isset($_REQUEST[GD_URLPARAM_RSSCAMPAIGN])) {

		// Change the scope
		$scope_days = array(
			GD_URLPARAM_RSSCAMPAIGN_UPCOMINGEVENTS_3DAYS => 3,
			GD_URLPARAM_RSSCAMPAIGN_UPCOMINGEVENTS_4DAYS => 4,
		);
		if ($days = $scope_days[$_REQUEST[GD_URLPARAM_RSSCAMPAIGN]]) {

			$args['scope'] = $days.'-days';
		}
	}

	return $args;
}

// Add the 3-days and 4-days scope
// Taken from http://wp-events-plugin.com/tutorials/create-your-own-event-scope/
add_filter( 'em_events_build_sql_conditions', 'pop_em_rss_events_build_sql_conditions',1,2);
function pop_em_rss_events_build_sql_conditions($conditions, $args){
    
   	// Somehow the scope could be an array, so `preg_match` below would fail, so make sure it is not an array
    if (!empty($args['scope']) && !is_array($args['scope'])) {

    	// Check if it suits the regex, and if so, get how many days
		$regex_pattern = "/^([2-6])-days$/";
		if (preg_match($regex_pattern, $args['scope'], $matches)) {

			$days = $matches[1];

	        // $end_date: if doing 2 days, then must produce +1 day, etc
	        $start_date = date('Y-m-d', POP_CONSTANT_CURRENTTIMESTAMP/*current_time('timestamp')*/);
	        $end_date = date('Y-m-d', strtotime(sprintf("+%s day", $days-1), POP_CONSTANT_CURRENTTIMESTAMP/*current_time('timestamp')*/));
	        $conditions['scope'] = " ((event_start_date BETWEEN CAST('$start_date' AS DATE) AND CAST('$end_date' AS DATE)) OR (event_end_date BETWEEN CAST('$start_date' AS DATE) AND CAST('$end_date' AS DATE)))";
	    }
    }
    return $conditions;
}
add_filter( 'em_get_scopes','pop_em_rss_scopes',1,1);
function pop_em_rss_scopes($scopes){

	// Add scopes 2-days ... 6-days
	for ($i = 2; $i <= 6; $i++) {
		$scopes[$i.'-days'] = sprintf(
			__('%s %s', 'poptheme-wassup'),
			$i,
			__('days', 'poptheme-wassup')
		);
	}
    return $scopes;
}
