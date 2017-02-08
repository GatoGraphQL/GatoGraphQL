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



function gd_get_website_description($addhomelink = true) {
  
  return apply_filters('gd_get_website_description', get_bloginfo('description'), $addhomelink);
}

// function gd_content_tag_query() {
//   $nice_tag_query = get_query_var('tag'); // tags in current query
//   $nice_tag_query = str_replace(' ', '+', $nice_tag_query); // get_query_var returns ' ' for AND, replace by +
//   $tag_slugs = preg_split('%[,+]%', $nice_tag_query, -1, PREG_SPLIT_NO_EMPTY); // create array of tag slugs
//   $tag_ops = preg_split('%[^,+]*%', $nice_tag_query, -1, PREG_SPLIT_NO_EMPTY); // create array of operators

//   $tag_ops_counter = 0;
//   $nice_tag_query = '';

//   foreach ($tag_slugs as $tag_slug) { 
//     $tag = get_term_by('slug', $tag_slug ,'post_tag');
//     // prettify tag operator, if any
//     if ( isset($tag_ops[$tag_ops_counter]) && $tag_ops[$tag_ops_counter] == ',') {
//       $tag_ops[$tag_ops_counter] = ', ';
//     } elseif ( isset($tag_ops[$tag_ops_counter]) && $tag_ops[$tag_ops_counter] == '+') {
//       $tag_ops[$tag_ops_counter] = ' + ';
//     } else {
//       $tag_ops[$tag_ops_counter] = '';
//     }
//     // concatenate display name and prettified operators
//     $nice_tag_query = $nice_tag_query.$tag->name.$tag_ops[$tag_ops_counter];
//     $tag_ops_counter += 1;
//   }
//    return $nice_tag_query;
// }


