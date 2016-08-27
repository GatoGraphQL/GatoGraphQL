<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Comments
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// Add all WP default filters also into the comments

// Taken from wp-includes/default-filters.php
add_filter( 'gd_comments_content', 'capital_P_dangit', 11 );
add_filter( 'gd_comments_content', 'wptexturize'        );
add_filter( 'gd_comments_content', 'convert_smilies'    );
add_filter( 'gd_comments_content', 'convert_chars'      );
add_filter( 'gd_comments_content', 'wpautop'            );
add_filter( 'gd_comments_content', 'shortcode_unautop'  );
add_filter( 'gd_comments_content', 'prepend_attachment' );

// Taken from wp-includes/shortcodes.php
add_filter('gd_comments_content', 'do_shortcode', 11); // AFTER wpautop()


// Taken from wp-includes/class-wp-embed.php
// add_filter( 'gd_comments_content', array( $GLOBALS['wp_embed'], 'run_shortcode' ), 8 );
// Attempts to embed all URLs in a post
// add_filter( 'gd_comments_content', array( $GLOBALS['wp_embed'], 'autoembed' ), 8 );

function gd_comments_apply_filters($comment_content, $embed = false) {

	// Cannot call filter "the_content" since it also executes unwanted filters
	// So create new filter "gd_fieldprocessor_comment:content" and selectively
	// from WP and all plugins copy the needed filters
	// If needed, Do the autoembed before executing all other shortcodes (check wp-includes/class-wp-embed.php)
	if ($embed) {
		$wp_embed = $GLOBALS['wp_embed'];
		$comment_content = $wp_embed->autoembed($comment_content);
	}
	return apply_filters('gd_comments_content', $comment_content);
}