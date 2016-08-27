<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Configure the RSS Feed
 *
 * ---------------------------------------------------------------------------------------------------------------*/
function gd_em_rss() {
	global $post, $wp_query, $wpdb;

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