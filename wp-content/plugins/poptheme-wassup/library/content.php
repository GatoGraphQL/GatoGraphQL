<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Custom Implementation Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// Allow posts to have menu_order. This is needed for the TPP Debate website,
// to order the Author Thoughts Carousel, so that it always shows the General thought first, and the then article-related ones
add_action( 'admin_init', 'gd_posts_menuorder' );
function gd_posts_menuorder() {
    add_post_type_support( 'post', 'page-attributes' );
}

// function gd_get_plaincontent_categories() {

// 	// Do not accept html tags or short codes in the Highlights (as they are entered by the user)
// 	return apply_filters(
// 		'gd_get_plaincontent_categories',
// 		array(
// 			POPTHEME_WASSUP_CAT_HIGHLIGHTS,
// 		)
// 	);
// }

// add_filter('pop_content', 'maybe_make_content_plain', 10, 2);
// function maybe_make_content_plain($content, $post_id) {

// 	// Strip tags on the content based on the post type / category
// 	if (get_post_type($post_id) == 'post') {

// 		if (in_array(gd_get_the_main_category($post_id), gd_get_plaincontent_categories())) {

// 			$content = strip_tags(strip_shortcodes($content));
// 		}
// 	}

// 	return $content;
// }

/**---------------------------------------------------------------------------------------------------------------
 * Add Media: do ALWAYS add a link to the image
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('media_send_to_editor', 'wassup_media_send_to_editor', 0, 3);
function wassup_media_send_to_editor($html, $id, $attachment) {

	// If the image has no URL tag...
	if (substr($html, 0, 5) == '<img ') {

		// Add a link to the image. Code copied from function wp_ajax_send_attachment_to_editor()
		$html = sprintf(
			'<a href="%s">%s</a>',
			esc_url(wp_get_attachment_url($id)),
			$html
		);
	}

	return $html;
}