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

// Make the tinymce always rich edit, also if user is logged out, or accessing the website using wget (so we can use wget to call /system/popinstall and save the service-workers.js file properly)
add_filter( 'user_can_richedit', '__return_true', 999999);

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



function gd_get_post_description() {

	global $post;
  $excerpt = $post->post_excerpt;
  // Comment Leo 23/05: not using get_the_excerpt because of the applied filters, eg: adding a disclaimer at the very top
  // $excerpt = get_the_excerpt();

	// If the excerpt is empty, return the post content instead
	if (!$excerpt) {

		global $post;
		// 300 characters long is good enough, remove all whitespaces, remove shortcodes
		$excerpt = str_replace(array("\n\r", "\n", "\r", "\t"), array(' ', '', '', ' '), wp_trim_excerpt(limit_string(strip_tags(strip_shortcodes($post->post_content)), 300)));
	}

	return esc_html($excerpt);
}

add_filter('excerpt_more', 'gd_excerpt_more', 10000, 1);
function gd_excerpt_more($excerpt_more) {
  return '...';
}
function gd_header_page_description() {
  global $post;
  return apply_filters('gd_header_page_description', '', $post->ID);
}

function gd_header_site_description() {
  return apply_filters('gd_header_site_description', '');
}

function gd_get_initial_document_title() {
  
  return apply_filters('gd_get_initial_document_title', get_bloginfo('name'));
}

function gd_get_document_thumb($size = 'large') {

  if (is_single() || is_page()) {
    global $post;
    $post_thumb_id = gd_get_thumb_id($post->ID);
    $thumb = wp_get_attachment_image_src( $post_thumb_id, $size);
    $thumb_mime_type = get_post_mime_type($post_thumb_id);
  }
  elseif (is_author()) {

    global $author;
    // $avatar = get_avatar($author, 150);
    // $avatar_original = gd_user_avatar_original_file($avatar, $author, 150);
    $userphoto = gd_get_useravatar_photoinfo($author);
    $thumb = array($userphoto['src'], $userphoto['width'], $userphoto['height']);
    $thumb_mime_type = '';
  }
  else {

    // For pages, use the website logo
    $thumb = gd_logo($size);
    $thumb_mime_type = $thumb[3];
  }

  // If there's no thumb (eg: a page doesn't have Featured Image) then return nothing
  if (!$thumb[0]) {
    return null;
  }

  return array(
    'src' => $thumb[0],
    'width' => $thumb[1],
    'height' => $thumb[2],
    'mime-type' => $thumb_mime_type
  );
}