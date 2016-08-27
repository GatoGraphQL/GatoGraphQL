<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Content functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// Remove shortcodes from the excerpt
add_filter( 'the_excerpt', 'strip_shortcodes', 0);
add_filter( 'the_excerpt', 'make_clickable');
remove_filter( 'the_excerpt', 'wpautop');
add_filter( 'the_content', 'make_clickable');

// Remove empty whitspaces at the end of the article. Eg: when adding a Youtube video at the end, we gotta have the Youtube URL 
// in 1 line so must add a break after it, but when it's the last thing in the article, at adds an ugly whitespace
// Do it before adding filter 'wpautop'
// Taken from https://secure.php.net/manual/en/function.trim.php
add_filter( 'the_content', 'trim', 1);
// add_filter( 'the_content', 'trim_nonbreakingspaces', 1);
// function trim_nonbreakingspaces($str) {
    
//     // turn some HTML with non-breaking spaces (&nbsp;) into a "normal" string 
//     $converted = strtr($str, array_flip(get_html_translation_table(HTML_ENTITIES, ENT_QUOTES)));
//     return trim($converted, chr(0xC2).chr(0xA0));
// }

// // Taken (and modified) from http://snipplr.com/view/75524/wordpress--remove-empty-paragraph-tags-with-nbsp-from-thecontent/
// add_filter( 'the_content', 'remove_empty_tags_recursive', 20, 1); 
// function remove_empty_tags_recursive ($str, $repto = NULL) {
         
//          // $str = force_balance_tags($str);
//          //** Return if string not given or empty.
//          if (!is_string ($str) || trim ($str) == '')
//         return $str;
 
//         //** Recursive empty HTML tags.
//        return preg_replace (
 
//               //** Pattern written by Junaid Atari.
//               '~\s?<p>(\s|&nbsp;)+</p>\s?~',
 
//              //** Replace with nothing if string empty.
//              !is_string ($repto) ? '' : $repto,
 
//             //** Source string
//            $str
// );}

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

/**
 * Adds the word "More" before the Title. Used for adding "More Actions", "More Events", etc blocks after a Single post
 */
// function gd_title_addmore($title, $post_id) {

//   $title = sprintf(__('More %s', 'pop-coreprocessors'), $title);
//   return $title;
// }

/**
 * Adds the word "More" before the Title. Used for adding "More Actions", "More Events", etc blocks after a Single post
 */
// function gd_title_addmember($title, $post_id) {

//   $title = sprintf(__('Member %s', 'pop-coreprocessors'), $title);
//   return $title;
// }

/**
 * Adds the word "Related" before the Title. Used for adding "Related Actions", "Related Events", etc blocks after a Single post
 */
// function gd_title_addrelated($title, $post_id) {

//   $title = sprintf(__('Related %s', 'pop-coreprocessors'), $title);
//   return $title;
// }

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

function gd_get_initial_document_title() {
  
  return apply_filters('gd_get_initial_document_title', get_bloginfo('name'));
}

function gd_get_website_description($addhomelink = true) {
  
  return apply_filters('gd_get_website_description', get_bloginfo('description'), $addhomelink);
}

function gd_content_tag_query() {
  $nice_tag_query = get_query_var('tag'); // tags in current query
  $nice_tag_query = str_replace(' ', '+', $nice_tag_query); // get_query_var returns ' ' for AND, replace by +
  $tag_slugs = preg_split('%[,+]%', $nice_tag_query, -1, PREG_SPLIT_NO_EMPTY); // create array of tag slugs
  $tag_ops = preg_split('%[^,+]*%', $nice_tag_query, -1, PREG_SPLIT_NO_EMPTY); // create array of operators

  $tag_ops_counter = 0;
  $nice_tag_query = '';

  foreach ($tag_slugs as $tag_slug) { 
    $tag = get_term_by('slug', $tag_slug ,'post_tag');
    // prettify tag operator, if any
    if ( isset($tag_ops[$tag_ops_counter]) && $tag_ops[$tag_ops_counter] == ',') {
      $tag_ops[$tag_ops_counter] = ', ';
    } elseif ( isset($tag_ops[$tag_ops_counter]) && $tag_ops[$tag_ops_counter] == '+') {
      $tag_ops[$tag_ops_counter] = ' + ';
    } else {
      $tag_ops[$tag_ops_counter] = '';
    }
    // concatenate display name and prettified operators
    $nice_tag_query = $nice_tag_query.$tag->name.$tag_ops[$tag_ops_counter];
    $tag_ops_counter += 1;
  }
   return $nice_tag_query;
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
